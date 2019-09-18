e
     * @param bool   $requireSemicolons
     *
     * @return array|false A set of statements, or false if incomplete
     */
    protected function parse($code, $requireSemicolons = false)
    {
        try {
            return $this->parser->parse($code);
        } catch (\PhpParser\Error $e) {
            if ($this->parseErrorIsUnclosedString($e, $code)) {
                return false;
            }

            if ($this->parseErrorIsUnterminatedComment($e, $code)) {
                return false;
            }

            if ($this->parseErrorIsTrailingComma($e, $code)) {
                return false;
            }

            if (!$this->parseErrorIsEOF($e)) {
                throw ParseErrorException::fromParseError($e);
            }

            if ($requireSemicolons) {
                return false;
            }

            try {
                // Unexpected EOF, try again with an implicit semicolon
                return $this->parser->parse($code . ';');
            } catch (\PhpParser\Error $e) {
                return false;
            }
        }
    }

    private function parseErrorIsEOF(\PhpParser\Error $e)
    {
        $msg = $e->getRawMessage();

        return ($msg === 'Unexpected token EOF') || (\strpos($msg, 'Syntax error, unexpected EOF') !== false);
    }

    /**
     * A special test for unclosed single-quoted strings.
     *
     * Unlike (all?) other unclosed statements, single quoted strings have
     * their own special beautiful snowflake syntax error just for
     * themselves.
     *
     * @param \PhpParser\Error $e
     * @param string           $code
     *
     * @return bool
     */
    private function parseErrorIsUnclosedString(\PhpParser\Error $e, $code)
    {
        if ($e->getRawMessage() !== 'Syntax error, unexpected T_ENCAPSED_AND_WHITESPACE') {
            return false;
        }

        try {
            $this->parser->parse($code . "';");
        } catch (\Exception $e) {
            return false;
        }

        return true;
    }

    private function parseErrorIsUnterminatedComment(\PhpParser\Error $e, $code)
    {
        return $e->getRawMessage() === 'Unte