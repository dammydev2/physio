=== $needle = self::iconv($encoding, 'utf-8', $needle)) {
                return false;
            }
        }

        $pos = isset($needle[0]) ? strrpos($haystack, $needle) : false;

        return false === $pos ? false : self::iconv_strlen($pos ? substr($haystack, 0, $pos) : $haystack, 'utf-8');
    }

    public static function iconv_substr($s, $start, $length = 2147483647, $encoding = null)
    {
        if (null === $encoding) {
            $encoding = self::$internalEncoding;
        }
        if (0 !== stripos($encoding, 'utf-8')) {
            $encoding = null;
        } elseif (false === $s = self::iconv($encoding, 'utf-8', $s)) {
            return false;
        }

        $s = (string) $s;
        $slen = self::iconv_strlen($s, 'utf-8');
        $start = (int) $start;

        if (0 > $start) {
            $start += $slen;
        }
        if (0 > $start) {
            return false;
        }
        if ($start >= $slen) {
            return false;
        }

        $rx = $slen - $start;

        if (0 > $length) {
            $length += $rx;
        }
        if (0 === $length) {
            return '';
        }
        if (0 > $length) {
            return false;
        }

        if ($length > $rx) {
            $length = $rx;
        }

        $rx = '/^'.($start ? self::pregOffset($start) : '').'('.self::pregOffset($length).')/u';

        $s = preg_match($rx, $s, $s) ? $s[1] : '';

        if (null === $encoding) {
            return $s;
        }

        return self::iconv('utf-8', $encoding, $s);
    }

    private static function loadMap($type, $charset, &$map)
    {
        if (!isset(self::$convertMap[$type.$charset])) {
            if (false === $map = self::getData($type.$charset)) {
                if ('to.' === $type && self::loadMap('from.', $charset, $map)) {
                    $map = array_flip($map);
                } else {
                    return false;
                }
            }

            self::$convertMap[$type.$charset] = $map;
        } else {
            $map = self::$convertMap[$type.$charset];
        }

        return true;
    }

    private static function utf8ToUtf8($str, $ignore)
    {
        $ulenMask = self::$ulenMask;
        $valid = self::$isValidUtf8;

        $u = $str;
        $i = $j = 0;
        $len = \strlen($str);

        while ($i < $len) {
            if ($str[$i] < "\x80") {
                $u[$j++] = $str[$i++];
            } else {
                $ulen = $str[$i] & "\xF0";
                $ulen = isset($ulenMask[$ulen]) ? $ulenMask[$ulen] : 1;
                $uchr = substr($str, $i, $ulen);

                if (1 === $ulen || !($valid || preg_match('/^.$/us', $uchr))) {
                    if ($ignore) {
                        ++$i;
                        continue;
                    }

                    trigger_error(self::ERROR_ILLEGAL_CHARACTER);

                    return false;
                } else {
                    $i += $ulen;
                }

                $u[$j++] = $uchr[0];

