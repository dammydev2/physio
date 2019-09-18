_DELIM_CAPTURE
        );
    }

    /**
     * Parses the stream of tokens in to a new set of tokens containing Tags.
     *
     * @param string[] $tokens
     * @param TypeContext $context
     *
     * @return string[]|Tag[]
     */
    private function parse($tokens, TypeContext $context)
    {
        $count = count($tokens);
        $tagCount = 0;
        $tags  = [];

        for ($i = 1; $i < $count; $i += 2) {
            $tags[] = $this->tagFactory->create($tokens[$i], $context);
            $tokens[$i] = '%' . ++$tagCount . '$s';
        }

        //In order to allow "literal" inline tags, the otherwise invalid
        //sequence "{@}" is changed to "@", and "{}" is changed to "}".
        //"%" is escaped to "%%" because of vsprintf.
        //See unit tests for examples.
        for ($i = 0; $i < $count; $i += 2) {
            $tokens[$i] = str_replace(['{@}', '{}', '%'], ['@', '}', '%%'], $tokens[$i]);
        }

        return [implode('', $tokens), $tags];
    }

    /**
     * Removes the superfluous from a multi-line description.
     *
     * When a description has more than one line then it can happen that the second and subsequent lines have an
     * additional indentation. This is commonly in use with tags like this:
     *
     *     {@}since 1.1.0 This is an example
     *         description where we have an
     *         indentation in the second and
     *         subsequent lines.
     *
     * If we do not normalize the indentation then we have superfluous whitespace on the second and subsequent
     * lines and this may cause rendering issues when, for example, using a