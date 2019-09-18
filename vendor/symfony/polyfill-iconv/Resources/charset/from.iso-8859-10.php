[$i] < "\x80" ? 1 : $ulenMask[$s[$i] & "\xF0"];
                $uchr = substr($s, $i, $ulen);
                $i += $ulen;

                if (isset($map[$uchr])) {
                    $uchr = $map[$uchr];
                    $nlen = \strlen($uchr);

                    if ($nlen == $ulen) {
                        $nlen = $i;
                        do {
                            $s[--$nlen] = $uchr[--$ulen];
                        } while ($ulen);
                    } else {
                        $s = substr_replace($s, $uchr, $i - $ulen, $ulen);
                        $len += $nlen - $ulen;
                        $i += $nlen - $ulen;
                    }
                }
            }
        }

        if (null === $encoding) {
            return $s;
        }

        return iconv('UTF-8', $encoding.'//IGNORE', $s);
    }

    public static function mb_internal_encoding($encoding = null)
    {
        if (null === $encoding) {
            return self::$internalEncoding;
        }

        $encoding = self::getEncoding($encoding);

        if ('UTF-8' === $encoding || false !== @iconv($encoding, $encoding, ' ')) {
            self::$internalEncoding = $encoding;

            return true;
        }

        return false;
    }

    public static function mb_language($lang = null)
    {
        if (null === $lang) {
            return self::$language;
        }

        switch ($lang = strtolower($lang)) {
            case 'uni':
            case 'neutral':
                self::$language = $lang;

                return true;
        }

        return false;
    }

    public static function mb_list_encodings()
    {
        return array('UTF-8');
    }

    public static function mb_encoding_aliases($encoding)
    {
        switch (strtoupper($encoding)) {
            case 'UTF8':
            case 'UTF-8':
                return array('utf8');
        }

        return false;
    }

    public static function mb_check_encoding($var = null, $encoding = null)
    {
        if (null === $encoding) {
            if (null === $var) {
                return false;
            }
            $encoding = self::$internalEncoding;
        }

        return self::mb_detect_encoding($var, array($encoding)) || false !== @iconv($encoding, $encoding, $var);
    }

    public static function mb_detect_encoding($str, $encodingList = null, $strict = false)
    {
        if (null === $encodingList) {
            $encodingList = self::$encodingList;
        } else {
            if (!\is_array($encodingList)) {
                $encodingList = array_map('trim', explode(',', $encodingList));
            }
            $encodingList = array_map('strtoupper', $encodingList);
        }

        foreach ($encodingList as $enc) {
            switch ($enc) {
                case 'ASCII':
                    if (!preg_match('/[\x80-\xFF]/', $str)) {
                        return $enc;
                    }
                    break;

                case 'UTF8':
                case 'UTF-8':
                    if (preg_match('//u', $str)) {
                        return 'UTF-8';
                    }
                    break;

                default:
                    if (0 === strncmp($enc, 'ISO-8859-', 9)) {
                        return $enc;
                    }
            }
        }

        return false;
    }

    public static function mb_detect_order($encodingList = null)
    {
        if (null === $encodingList) {
            return self::$encodingList;
        }

        if (!\is_array($encodingList)) {
            $encodingList = array_map('trim', explode(',', $encodingList));
        }
        $encodingList = array_map('strtoupper', $encodingList);

        foreach ($encodingList as $enc) {
            