 ' data-title="%s" data-content="%s" data-placement="bottom" data-html="true"',
                    $popoverTitle,
                    \htmlspecialchars($popoverContent, $this->htmlSpecialCharsFlags)
                );
            }

            $lines .= \sprintf(
                '     <tr%s%s><td><div align="right"><a name="%d"></a><a href="#%d">%d</a></div></td><td class="codeLine">%s</td></tr>' . "\n",
                $trClass,
                $popover,
                $i,
                $i,
                $i,
                $line
            );

            $i++;
        }

        return $lines;
    }

    /**
     * @param string $file
     */
    protected function loadFile($file): array
    {
        $buffer              = \file_get_contents($file);
        $tokens              = \token_get_all($buffer);
        $result              = [''];
        $i                   = 0;
        $stringFlag          = false;
        $fileEndsWithNewLine = \substr($buffer, -1) == "\n";

        unset($buffer);

        foreach ($tokens as $j => $token) {
            if (\is_string($token)) {
                if ($token === '"' && $tokens[$j - 1] !== '\\') {
                    $result[$i] .= \sprintf(
                        '<span class="string">%s</span>',
                        \htmlspecialchars($token, $this->htmlSpecialCharsFlags)
                    );

                    $stringFlag = !$stringFlag;
                } else