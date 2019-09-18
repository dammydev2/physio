  $strlen = \strlen($string);
        $charPos = \count($this->map['p']);
        $foundChars = 0;
        $invalid = false;
        for ($i = 0; $i < $strlen; ++$i) {
            $char = $string[$i];
            $size = self::UTF8_LENGTH_MAP[$char];
            if (0 == $size) {
                /* char is invalid, we must wait for a resync */
                $invalid = true;
                continue;
            }

            if ($invalid) {
                /* We mark the chars as invalid and start a new char */
                $this->map['p'][$charPos + $foundChars] = $startOffset + $i;
                $this->map['i'][$charPos + $foundChars] = true;
                ++$foundChars;
       