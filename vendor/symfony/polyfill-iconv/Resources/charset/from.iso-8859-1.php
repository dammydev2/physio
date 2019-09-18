i + 2]);
                }
            }

            return $m[0];
        }, $s);

        if (null === $encoding) {
            return $s;
        }

        return iconv('UTF-8', $encoding.'//IGNORE', $s);
    }

    public static function mb_encode_numericentity($s, $convmap, $encoding = null, $is_hex = false)
    {
        if (null !== $s && !\is_scalar($s) && !(\is_object($s) && \method_exists($s, '__toString'))) {
            trigger_error('mb_encode_numericentity() expects parameter 1 to be string, '.\gettype($s).' given', E_USER_WARNING);

            return null;
        }

        if (!\is_array($convmap) || !$convmap) {
            return false;
        }

        if (null !== $encoding && !\is_scalar($encoding)) {
            trigger_error('mb_encode_numericentity() expects parameter 3 to be string, '.\gettype($s).' given', E_USER_WARNING);

            return null;  // Instead of '' (cf. mb_decode_numericentity).
        }

        if (null !== $is_hex && !\is_scalar($is_hex)) {
            trigger_error('mb_encode_numericentity() expects parameter 4 to be boolean, '.\gettype($s).' given', E_USER_WARNING);

            return null;
        }

        $s = (string) $s;
        if ('' === $s) {
            return '';
        }

        $encoding = self::getEncoding($encoding);

        if ('UTF-8' === $encoding) {
            $encoding = null;
            if (!preg_match('//u', $s)) {
                $s = @iconv('UTF-8', 'UTF-8//IGNORE', $s);
            }
        } else {
            $s = iconv($encoding, 'UTF-8//IGNORE', $s);
        }

        static $ulenMask = array("\xC0" => 2, "\xD0" => 2, "\xE0" => 3, "\xF0" => 4);

        $cnt = floor(\count($convmap) / 4) * 4;
        $i = 0;
        $len = \strlen($s);
        $result = '';

        while ($i < $len) {
            $ulen = $s[$i] < "\x80" ? 1 : $ulenMask[$s[$i] & "\xF0"];
            $uchr = substr($s, $i, $ulen);
            $i += $ulen;
            $c = self::mb_ord($uchr);

            for ($j = 0; $j < $cnt; $j += 4) {
                if ($c >= $convmap[$j] && $c <= $convmap[$j + 1]) {
                    $cOffset = ($c + $convmap[$j + 2]) & $convmap[$j + 3];
                    $result .= $is_hex ? sprintf('&#x%X;', $cOffset) : '&#'.$cOffset.';';
                    continue 2;
                }
            }
            $result .= $uchr;
        }

        if (null === $encoding) {
            return $result;
        }

        return iconv('UTF-8', $encoding.'//IGNORE', $result);
    }

    public static function mb_convert_case($s, $mode, $encoding = null)
    {
        $s = (string) $s;
        if ('' === $s) {
            return '';
        }

        $encoding = self::getEncoding($encoding);

        if ('UTF-8' === $encoding) {
            $encoding = null;
            if (!preg_match('//u', $s)) {
                $s = @iconv('UTF-8', 'UTF-8//IGNORE', $s);
            }
        } else {
            $s = iconv($encoding, 'UTF-8//IGNORE', $s);
        }

        if (MB_CASE_TITLE == $mode) {
            static $titleRegexp = null;
            if (null === $titleRegexp) {
                $titleRegexp = self::getData('titleCaseRegexp');
            }
            $s = preg_replace_callback($titleRegexp, array(__CLASS__, 'title_case'), $s);
        } else {
            if (MB_CASE_UPPER == $mode) {
                static $upper = null;
                if (null === $upper) {
                    $upper = self::getData('upperCase');
                }
                $map = $upper;
            } else {
                if (self::MB_CASE_FOLD === $mode) {
                    $s = str_replace(self::$caseFold[0], self::$caseFold[1], $s);
                }

                static $lower = null;
                if (null === $lower) {
      