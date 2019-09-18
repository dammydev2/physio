::$encodingList = $encodingList;

        return true;
    }

    public static function mb_strlen($s, $encoding = null)
    {
        $encoding = self::getEncoding($encoding);
        if ('CP850' === $encoding || 'ASCII' === $encoding) {
            return \strlen($s);
        }

        return @iconv_strlen($s, $encoding);
    }

    public static function mb_strpos($haystack, $needle, $offset = 0, $encoding = null)
    {
        $encoding = self::getEncoding($encoding);
        if ('CP850' === $encoding || 'ASCII' === $encoding) {
            return strpos($haystack, $needle, $offset);
        }

        $needle = (string) $needle;
        if ('' === $needle) {
            trigger_error(__METHOD__.': Empty delimiter', E_USER_WARNING);

            return false;
        }

        return iconv_strpos($haystack, $needle, $offset, $encoding);
    }

    public static function mb_strrpos($haystack, $needle, $offset = 0, $encoding = null)
    {
        $encoding = self::getEncoding($encoding);
        if ('CP850' === $encoding || 'ASCII' === $encoding) {
            return strrpos($haystack, $needle, $offset);
        }

        if ($offset != (int) $offset) {
            $offset = 0;
        } elseif ($offset = (int) $offset) {
            if ($offset < 0) {
                $haystack = self::mb_substr($haystack, 0, $offset, $encoding);
                $offset = 0;
            } else {
                $haystack = self::mb_substr($haystack, $offset, 2147483647, $encoding);
            }
        }

        $pos = iconv_strrpos($haystack, $needle, $encoding);

        return false !== $pos ? $offset + $pos : false;
    }

    public static function mb_strtolower($s, $encoding = null)
    {
        return self::mb_convert_case($s, MB_CASE_LOWER, $encoding);
    }

    public static function mb_strtoupper($s, $encoding = null)
    {
        return self::mb_convert_case($s, MB_CASE_UPPER, $encoding);
    }

    public static function mb_substitute_character($c = null)
    {
        if (0 === strcasecmp($c, 'none')) {
            return true;
        }

        return null !== $c ? false : 'none';
    }

    public static function mb_substr($s, $start, $length = null, $encoding = null)
    {
        $encoding = self::getEncoding($encoding);
        if ('CP850' === $encoding || 'ASCII' === $encoding) {
            return substr($s, $start, null === $length ? 2147483647 : $length);
        }

        if ($start < 0) {
            $start = iconv_strlen($s, $encoding) + $start;
            if ($start < 0) {
                $start = 0;
            }
        }

        if (null === $length) {
            $length = 2147483647;
        } elseif ($length < 0) {
            $length = iconv_strlen($s, $encoding) + $length - $start;
            if ($length < 0) {
                return '';
            }
        }

        return (string) iconv_substr($s, $start, $length, $encoding);
    }

    public static function mb_stripos($haystack, $needle, $offset = 0, $encoding = null)
    {
        $haystack = self::mb_convert_case($haystack, self::MB_CASE_FOLD, $encoding);
        $needle = self::mb_convert_case($needle, self::MB_CASE_FOLD, $encoding);

        return self::mb_strpos($haystack, $needle, $offset, $encoding);
    }

    public static function mb_stristr($haystack, $needle, $part = false, $encoding = null)
    {
        $pos = self::mb_stripos($haystack, $needle, 0, $encoding);

        return self::getSubpart($pos, $part, $haystack, $encoding);
    }

    public static function mb_strrchr($haystack, $needle, $part = false, $encoding = null)
    {
        $encoding = self::getEncoding($encoding);
        if ('CP850' === $encoding || 'ASCII' === $encoding) {
            return strrchr($haysta