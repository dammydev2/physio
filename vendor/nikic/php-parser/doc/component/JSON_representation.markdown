  && substr($token[1], 0, 2) === '/*'
            && substr($token[1], -2) !== '*/';
    }

    /**
     * Check whether an error *may* have occurred during tokenization.
     *
     * @return bool
     */
    private function errorMayHaveOccurred() : bool {
        if (defined('HHVM_VERSION')) {
            // In HHVM token_get_all() does not throw warnings, so we need to conservatively
            // assume that an error occurred
            return true;
        }

        return null !== error_get_last();
    }

    protected function handleErrors(ErrorHandler $errorHandler) {
        if (!$this->errorMayHaveOccurred()) {
            return;
        }

        // PHP's error handling for token_get_all() is rather bad, so if we want detailed
        // error information we need to compute it ourselves. Invalid character errors are
        // detected by finding "gaps" in the token array. Unterminated comments are detected
        // by checking if a trailing comment has a "*/" at the end.

        $filePos = 0;
        $line = 1;
        foreach ($this->tokens as $token) {
            $tokenValue = \is_string($token) ? $token : $token[1];
            $tokenLen = \strlen($tokenValue);

            if (substr($this->code, $filePos, $tokenLen) !== $tokenValue) {
                // Something is missing, must be an invalid character
                $nextFilePos = strpos($this->code, $tokenValue, $filePos);
                $this->handleInvalidCharacterRange(
                    $filePos, $nextFilePos, $line, $errorHandler);
                $filePos = (int) $nextFilePos;
            }

            $filePos += $tokenLen;
            $line += substr_count($tokenValue, "\n");
        }

        if ($filePos !== \strlen($this->code)) {
            if (substr($this->code, $filePos, 2) === '/*') {
                // Unlike PHP, HHVM will drop unterminated comments entirely
                $comment = substr($this->code, $filePos);
                $errorHandler->handleError(new Error('Unterminated comment', [
                    'startLine' => $line,
                    'endLine' => $line + substr_count($comment, "\n"),
                    'startFilePos' => $filePos,
                    'endFilePos' => $filePos + \strlen($comment),
                ]));

                // Emulate the PHP behavior
                $isDocComment = isset($comment[3]) && $comment[3] === '*';
                $this->tokens[] = [$isDocComment ? \T_DOC_COMMENT : \T_COMMENT, $comment, $line];
            } else {
                // Invalid characters at the end of the input
                $this->handleInvalidCharacterRange(
                    $filePos, \strlen($this->code), $line, $errorHandler);
            }
            return;
        }

        if (count($this->tokens) > 0) {
            // Check for unterminated comment
            $lastToken = $this->tokens[count($this->tokens) - 1];
            if ($this->isUnterminatedComment($lastToken)) {
                $errorHandler->handleError(new Error('Unterminated comment', [
                    'startLine' => $line - substr_count($lastToken[1], "\n"),
                    'endLine' => $line,
                    'startFilePos' => $filePos - \strlen($lastToken[1]),
                    'endFilePos' => $filePos,
                ]));
            }
        }
    }

    /**
     * Fetches the next token.
     *
     * The available attributes are determined by the 'usedAttributes' option, which can
     * be specified