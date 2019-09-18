   $upperLimit = \count($diff);

        if (0 === $diff[$upperLimit - 1][1]) {
            $lc = \substr($diff[$upperLimit - 1][0], -1);

            if ("\n" !== $lc) {
                \array_splice($diff, $upperLimit, 0, [["\n\\ No newline at end of file\n", Differ::NO_LINE_END_EOF_WARNING]]);
            }
        } else {
            // search back for the last `+` and `-` line,
            // check if has trailing linebreak, else add under it warning under it
            $toFind = [1 => true, 2 => true];

            for ($i = $upperLimit - 1; $i >= 0; --$i) {
                if (isset($toFind[$diff[$i][1]])) {
                    unset($toFind[$diff[$i][1]]);
                    $lc = \substr($diff[$i][0], -1);

                    if ("\n" !== $lc) {
                        \array_splice($diff, $i + 1, 0, [["\n\\ No newline at end of file\n", Differ::NO_LINE_END_EOF_WARNING]]);
                    }

                    if (!\count($toFind)) {
                        break;
                    }
                }
            }
        }

        // write hunks to output buffer

        $cutOff      = \max($this->commonLineThreshold, $this->contextLines);
        $hunkCapture = false;
        $sameCount   = $toRange = $fromRange = 0;
        $toStart     = $fromStart = 1;

        foreach ($diff as $i => $entry) {
            if (0 === $entry[1]) { // same
                if (false === $hunkCapture) {
                    ++$fromStart;
                    ++$toStart;

                    continue;
                }

                ++$sameCount;
                ++$toRange;
                ++$fromRange;

                if ($sameCount === $cutOff) {
                    $contextStartOffset = ($hunkCapture - $this->contextLines) < 0
                        ? $hunkCapture
                        : $this->contextLines
                    ;

                    // note: $contextEndOffset = $this->contextLines;
                    //
                    // because we never go beyond the end of the diff.
                    // with the cutoff/contextlines here the follow is never true;
                    //
                    // if ($i - $cutOff + $this->contextLines + 1 > \count($diff)) {
                    //    $contextEndOffset = count($diff) - 1;
                    // }
                    //
                    // ; that would be true for a trailing incomplete hunk case which is dealt with after this loop

                    $this->writeHunk(
                        $diff,
                        $hunkCapture - $contextStartOffset,
                        $i - $cutOff + $this->contextLines + 1,
                        $fromStart - $contextStartOffset,
                        $fromRange - $cutOff + $contextStartOffset + $this->contex