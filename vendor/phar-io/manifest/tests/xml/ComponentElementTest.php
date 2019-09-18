$key] = $this->tagFactory->create(trim($tagLine), $context);
        }

        return $result;
    }

    /**
     * @param string $tags
     *
     * @return string[]
     */
    private function splitTagBlockIntoTagLines($tags)
    {
        $result = [];
        foreach (explode("\n", $tags) as $tag_line) {
            if (isset($tag_line[0]) && ($tag_line[0] === '@')) {
                $result[] = $tag_line;
            } else {
                $result[count($result) - 1] .= "\n" . $tag_line;
            }
        }

        return $result;
    }

    /**
     * @param $tags
     * @return string
     */
    private function filterTagBlock($tags)
    {
        $tags = trim($tags);
        if (!$tags