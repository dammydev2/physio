S_CR ||
            $this->lexer->token['type'] === EmailLexer::S_LF ||
            $this->lexer->token['type'] === EmailLexer::CRLF
        ) {
            return true;
        }

        return false;
    }

    protected function escaped()
    {
        $previous = $this->lexer->getPrevious();

        if ($previous['type'] === EmailLexer::S_BACKSLASH
            &&
            $this->lexer->token['type'] !== EmailLexer::GENERIC
        ) {
            return true;
        }

        return false;
    }

    protected function warnEscaping()
    {
        if ($this->lexer->token['type'] !== EmailLexer::S_BACKSLASH) {
            return false;
        }

        if ($this->lexer->isNextToken(EmailLexer::GENERIC)) {
            throw new ExpectingATEXT();
        }

        if (!$this->lexer->isNextTokenAny(array(EmailLexer::S_SP, EmailLexer::S_HTAB, EmailLexer::C_DEL))) {
            return false;
        }

        $this->warnings[QuotedPart::CODE] =
            new QuotedPart($this->lexer->getPrevious()['type'], $this->lexer->token['type']);
        return true;

    }

    protected function checkDQUOTE($hasClosingQuote)
    {
        if ($this->lexer->token['type'] !== EmailLexer::S_DQUOTE) {
            return $hasClosingQuote;
        }
        if ($hasClosingQuote) {
            return $hasClosingQuote;
        }
        $previous = $this->lexer->getPrevious();
        if ($this->lexer->isNextToken(EmailLexer::GENERIC) && $previous['type'] === EmailLexer::GENERIC) {
            throw new ExpectingATEXT();
        }

        try {
            $this->lexer->find(EmailLexer::S_DQUOTE);
            $hasClosingQuote = true;
        } catch (\Exception $e) {
            throw new UnclosedQuotedString();
        }
        $this->warnings[QuotedString::CODE] = new QuotedString($previous['value'], $this->lexer->token['value']);

        return $hasClosingQuote;
    }

    protected function checkCRLFInFWS()
    {
        if ($this->lexer->token['type'] !== EmailLexer::CRLF) {
            return;
        }

        if (!$this->lexer->isNextTokenAny(array(EmailLexer::S_SP, EmailLexer::S_HTAB))) {
            throw new 