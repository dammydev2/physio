   } else {
                throw SyntaxErrorException::unexpectedToken('selector', $peek);
            }
        }

        if (\count($stream->getUsed()) === $selectorStart) {
            throw SyntaxErrorException::unexpectedToken('selector', $stream->getPeek());
        }

        return [$result, $pseudoElement];
    }

    private function parseElementNode(TokenStream $stream): Node\ElementNode
    {
        $peek = $stream->getPeek();

        if ($peek->isIdentifier() || $peek->isDelimiter(['*'])) {
            if ($peek->isIdentifier()) {
                $namespace = $stream->getNext()->getValue();
            } else {
                $stream->getNext();
                $namespace = null;
            }

            if ($stream->getPeek()->isDelimiter(['|'])) {
                $stream->getNext();
                $element = $stream->getNextIdentifierOrStar();
            } else {
                $element = $namespace;
                $namespace = null;
            }
        } else {
            $element = $namespace = null;
        }

        return new Node\ElementNode($namespace, $element);
    }

    private function parseAttributeNode(Node\NodeInterface $selector, TokenStream $stream): Node\AttributeNode
    {
        $stream->skipWhitespace();
        $attribute = $stream->getNextIdentifierOrStar();

        if (null === $attribute && !$stream->getPeek()->isDelimiter(['|'])) {
            throw SyntaxErrorException::unexpectedToken('"|"', $stream->getPeek());
        }

        if ($stream->getPeek()->isDelimiter(['|'])) {
            $stream->getNext();

            if ($stream->getPeek()->isDelimiter(['='])) {
                $namespace = null;
                $stream->getNext();
                $operator = '|=';
            } else {
                $namespace = $attribute;
                $attribute = $stream->getNextIdentifier();
                $operator = null;
            }
        } else {
            $namespace = $operator = null;
        }

        if (null === $operator) {
            $stream->skipWhitespace();
            $next = $stream->getNext();

            if ($next->isDelimiter([']'])) {
                return new Node\AttributeNode($selector, $namespace, $attribute, 'exists', null);
            } elseif ($next->isDelimiter(['='])) {
                $operator = '=';
            } elseif ($next->isDelimiter(['^', '$', '*', '~', '|', '!'])
                && $stream->getPeek()->isDelimiter(['='])
            ) {
                $operator = $next->getValue().'=';
                $stream->getNext();
            