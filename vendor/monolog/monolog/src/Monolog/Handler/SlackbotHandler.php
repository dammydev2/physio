am string $expected
     * @param string $actual
     *
     * @internal param string $exception
     */
    private function assertContextContainsFormattedException($expected, $actual)
    {
        $this->assertEquals(
            '{"level_name":"CRITICAL","channel":"core","context":{"exception":'.$expected.'},"datetime":null,"extra":[],"message":"foobar"}'."\n",
            $actual
        );
    }

    /**
     * @param JsonFormatter $formatter
     * @param \Exception|\Throwable $exception
     *
     * @return string
     */
    private function formatRecordWithExceptionInContext(JsonFormatter $formatter, $exception)
    {
        $message = $formatter->format(array(
            'level_name' => 'CRITICAL',
            'channel' => 'core',
            'context' => array('exception' => $exception),
            'datetime' => null,
            'extra' => array(),
            'message' => 'foobar',
        ));
        return $message;
    }

    /**
     * @param \Exception|\Throwable $exception
     *
     * @return string
     */
    private function formatExceptionFilePathWithLine($exception)
    {
        $options = 0;
        if (version_compare(PHP_VERSION, '5.4.0', '>=')) {
            $options = JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE;
        }
        $path = substr(json_encode($exception->getFile(), $options), 1, -1);
        return $path . ':' . $exception->getLine();
    }

    /**
     * @param \Exception|\Throwable $exception
     *
     * @param null|string $previous
     *
     * @return string
     */
    private function formatException($exception, $previous = null)
    {
        $formattedException =
            '{"class":"' . get_class($exception) .
            '","message":"' . $exception->getMessage() .
            '","code":' . $exception->getCode() .
            ',"file":"' . $this->formatExceptionFilePathWithLine($exception) .
            ($previous ? '","previous":' . $previous : '"') .
            '}';
        return $formattedException;
    }

    public fu