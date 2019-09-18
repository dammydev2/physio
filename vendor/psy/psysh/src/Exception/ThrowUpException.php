s as $block => $body) {
            $body = \trim(\implode("\n", $body));

            if ($block === 0 && !self::isTagged($body)) {
                // This is the description block
                $this->desc = $body;
            } else {
                // This block is tagged
                $tag  = \substr(self::strTag($body), 1);
                $body = \ltrim(\substr($body, \strlen($tag) + 2));

                if (isset(self::$vectors[$tag])) {
                    // The tagged block is a vector
                    $count = \count(self::$vectors[$tag]);
                    if ($body) {
                        $parts = \preg_split('/\s+/', $body, $count);
                    } else {
                        $parts = [];
                    }

                    // Default the trailing values
                    $parts = \array_pad($parts, $count, null);

                    // Store as a mapped array
                    $this->tags[$tag][] = \array_combine(self::$vectors[$tag], $parts);
                } else {
                    // The tagged block is only text
                    $this->tags[$tag][] = $body;
                }
            }
        }
    }

    /**
     * Whether or not a docblock contains a given @tag.
     *
     * @param string $tag The name of the @tag to check for
     *
     * @return bool
     */
    public function hasTag($tag)
    {
        return \is_array($this->