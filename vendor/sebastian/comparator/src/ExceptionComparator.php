 true;
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
                        $fromRange - $cutOff + $contextStartOffset + $this->contextLines,
                        $toStart - $contextStartOffset,
                        $toRange - $cutOff + $contextStartOffset + $this->contextLines,
                        $output
                    );

                    $fromStart += $fromRange;
                    $toStart += $toRange;

                    $hunkCapture = false;
                    $sameCount   = $toRange = $fromRange = 0;
                }

                continue;
            }

            $sameCount = 0;

            if ($entry[1] === Differ::NO_LINE_END_EOF_WARNING) {
               