     }

            if (isset($e->detail)) {
                $data['detail'] = $e->detail;
            }
        }

        $trace = $e->getTrace();
        foreach ($trace as $frame) {
            if (isset($frame['file'])) {
                $data['trace'][] = $frame['file'].':'.$frame['line'];
            } elseif (isset($frame['function']) && $frame['function'] === '{closure}') {
                // Simplify closures handling
                $data['trace'][] = $frame['function'];
            } else {
                if (isset($frame['args'])) {
                    // Make sure that objects present as arguments are not serialized nicely but rather only
                    // as a class name to avoid any unexpected leak of sensitive information
                    $frame['args'] = array_map(function ($arg) {
                        if (is_object($arg) && !($arg instanceof \DateTime || $arg instanceof \DateTimeInterface)) {
                            return sprintf("[object] (%s)", Utils::getClass($arg));
                        }

                        return $arg;
                    }, $frame['args']);
                }
                // We should again normalize the frames, because it might contain invalid items
                $data['trace'][] = $this->toJson($this->normalize($frame), true);
            }
        }

        if ($previous = $e->getPrevious()) {
            $data['previous'] = $this->normalizeException($previous);
        }

        return $data;
    }

    /**
     * Return the JSON representation of a value
     *
     * @param  mixed             $data
     * @param  bool              $ignoreErrors
     * @throws \RuntimeException if encoding fails and errors are not ignored
     * @return string
     */
    protected function toJson($data, $ignoreErrors = false)
    {
        // suppress json_encode errors since it's twitchy with some inputs
        if ($ignoreErrors) {
            return @$this->jsonEncode($data);
        }

        $json = $this->jsonEncode($data);

        if ($json === false) {
            $json = $this->handleJsonError(json_last_error(), $data);
        }

        return $json;
    }

    /**
     * @param  mixed  $data
     * @return string JSON encoded data or null on failure
     */
    private function jsonEncode($data)
    {
        if (version_compare(PHP_VERSION, '5.4.0', '>=')) {
            return json_encode($data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        }

        return json_encode($data);
    }

    /**
     * Handle a json_encode failure.
     *
     * If the failure is due to invalid string encoding, try to clean the
     * input and encode again. If the second encoding attempt fails, the
     * inital error is not encoding related or the input can't be cleaned then
     * raise a descriptive exception.
     *
     * @param  int               $code return code of json_last_error function
     * @param  mixed             $data data that was meant to be encoded
     * @throws \RuntimeException if failure can't be corrected
     * @return string            JSON encoded data after error correction
     */
    private function handleJsonError($code, $data)
    {
        if ($code !== JSON_ERROR_UTF8) {
            $this->throwEncodeError($code, $data);
        }

        if (is_string($data)) {
            $this->detectAndCleanUtf8($data);
        } elseif (is_array($data)) {
            array_walk_recursive($data, array($this, 'detectAndCleanUtf8'));
        } else {
            $this->throwEncodeError($code, $data);
        }

        $json = $this->jsonEncode($data);

        if ($json === false) {
            $this->throwEncodeError(json_last_error(), $data);
        }

        return $json;
    }

    /**
     * Throws an exception according to a given code with a customized message
     *
     * @param  int               $code return code of json_last_error function
     * @param  mixed             $data data that was meant to be encoded
     * @throws \RuntimeException
     */
    private function throwEncodeError($code, $data)
    {
        switch ($code) {
            case JSON_ERROR_DEPTH:
                $msg = 'Maximum stack depth exceeded';
                break;
            case JSON_ERROR_STATE_MISMATCH:
                $msg = 'Underflow or the modes mismatch';
                break;
            case JSON_ERROR_CTRL_CHAR:
                $msg = 'Unexpected control character found';
                break;
            case JSON_ERROR_UTF8:
                $msg = 'Malformed UTF-8 characters, possibly incorrectly encoded';
                break;
            default:
                $msg = 'Unknown error';
        }

        throw new \RuntimeException('JSON encoding failed: '.$msg.'. Encoding: '.var_export($data, true));
    }

    /**
     * Detect invalid UTF-8 string characters and convert to valid UTF-8.
     *
     * Valid UTF-8 input will be left unmodified, but strings containing
     * invalid UTF-8 codepoints will be reencoded as UTF-8 with an assumed
     * original encoding of ISO-8859-15. This conversion may result in
     * incorrect output if the actual encoding was not ISO-8859-15, but it
     * will be clean UTF-8 output and will not rely on expensi