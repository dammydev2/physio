 $comment = trim(substr($comment, 0, -2));
        }

        return str_replace(["\r\n", "\r"], "\n", $comment);
    }

    /**
     * Splits the DocBlock into a template marker, summary, description and block of tags.
     *
     * @param string $comment Comment to split into the sub-parts.
     *
     * @author Richard van Velzen (@_richardJ) Special thanks to Richard for the regex responsible for the split.
     * @author Mike van Riel <me@mikevanriel.com> for extending the regex with template marker support.
     *
     * @return string[] containing the template marker (if any), summary, description and a string containing the tags.
     */
    private function splitDocBlock($comment)
    {
        // Performance improvement cheat: if the first character is an @ then only tags are in this DocBlock. This
        // method does not split tags so we return this verbatim as the fourth result (tags). This saves us the
        // performance impact of running a regular expression
        if (strpos($comment, '@') === 0) {
            return ['', '', '', $comment];
        }

        // clears all extra horizontal whitespace from the line endings