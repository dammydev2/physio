)"
                    ." or name(.) = 'button'"
                    ." or name(.) = 'select'"
                    ." or name(.) = 'textarea'"
                    ." or name(.) = 'keygen'"
                .')'
                .' and not (@disabled or ancestor::fieldset[@disabled])'
            .') or ('
                ."name(.) = 'option' and not("
                    .'@disabled or ancestor::optgroup[@disabled]'
                .')'
            .')'
        );
    }

    /**
     * @return XPathExpr
     *
     * @throws ExpressionErrorException
     */
    public function translateLang(XPathExpr $xpath, FunctionNode $function)
    {
        $arguments = $function->getArguments();
        foreach ($arguments as $token) {
            if (!($token->isString() || $token->isIdentifier())) {
                throw new ExpressionErrorException('Expected a single string or identifier for :lang(), got '.implode(', ', $arguments));
            }
        }

        return $xpath->addCondition(sprintf(
            'ancestor-or-self::*[@lang][1][starts-with(concat('
            ."translate(@%s, 'ABCDEFGHIJKLMNOPQRSTUVWXYZ', 'abcdefghijklmnopqrstuvwxyz'), '-')"
            .', %s)]',
            'lang',
            Translator::getXpathLiteral(strtolower($arguments[0]->getValue()).'-')
        ));
    }

    /**
     * @return XPathExpr
     */
    public function translateSelected(XPathExpr $xpath)
    {
        return $xpath->addCondition("(@selected and name(.) = 'option')");
    }

    /**
     * @return XPathExpr
     */
    public function translateInvalid(XPathExpr $xpath)
  