:mb_substr($needle, 0, 1, $encoding);
        $pos = self::mb_strripos($haystack, $needle, $encoding);

        return self::getSubpart($pos, $part, $haystack, $encoding);
    }

    public static function mb_strripos($haystack, $needle, $offset = 0, $encoding = null)
    {
        $haystack = self::mb_convert_case($haystack, self::MB_CASE_FOLD, $encoding);
        $needle = self::mb_convert_case($needle, self::MB_CASE_FOLD, $encoding);

        return self::mb_strrpos($haystack, $needle, $offset, $encoding);
    }

    public static function mb_strstr($haystack, $needle, $part = false, $encoding = null)
    {
        $pos = strpos($haystack, $needle);
        if (false === $pos) {
            return false;
        }
        if ($part) {
            return substr($haystack, 0, $pos);
        }

        return substr($haystack, $pos);
    }

    public static function mb_get_info($type = 'all')
    {
        $info = array(
            'internal_encoding' => self::$internalEncoding,
            'http_output' => 'pass',
            'http_output_conv_mimetypes' => '^(text/|application/xhtml\+xml)',
            'func_overload' => 0,
            'func_overload_list' => 'no overload',
            'mail_charset' => 'UTF-8',
            'mail_header_encoding' => 'BASE64',
            'mail_body_encoding' => 'BASE64',
            'illegal_chars' => 0,
            'encoding_translation' => 'Off',
            'language' => self::$language,
            'detect_order' => self::$encodingList,
            'substitute_character' => 'none',
            'strict_detection' => 'Off',
        );

        if ('all' === $type) {
            return $info;
        }
        if (isset($info[$type])) {
            return $info[$type];
        }

        return false;
    }

    public static function mb_http_input($type = '')
    {
        return false;
    }

    public static function mb_http_output($encoding = null)
    {
        return null !== $encoding ? 'pass' === $encoding : 'pass';
    }

    public static function mb_strwidth($s, $encoding = null)
    {
        $encoding = self::getEncoding($encoding);

        if ('UTF-8' !== $encoding) {
            $s = iconv($encoding, 'UTF-8//IGNORE', $s);
        }

        $s = preg_replace('/[\x{1100}-\x{115F}\x{2329}\x{232A}\x{2E80}-\x{303E}\x{3040}-\x{A4CF}\x{AC00}-\x{D7A3}\x{F900}-\x{FAFF}\x{FE10}-\x{FE19}\x{FE30}-\x{FE6F}\x{FF00}-\x{FF60}\x{FFE0}-\x{FFE6}\x{20000}-\x{2FFFD}\x{30000}-\x{3FFFD}]/u', '', $s, -1, $wide);

        return ($wide << 1) + iconv_strlen($s, 'UTF-8');
    }

    public static function mb_substr_count($haystack, $needle, $encoding = null)
    {
        return substr_count($haystack, $needle);
    }

    public static function mb_output_handler($contents, $status)
    {
        return $contents;
    }

    public static function mb_chr($code, $encoding = null)
    {
        if (0x80 > $code %= 0x200000) {
            $s = \chr($code);
        } elseif (0x800 > $code) {
            $s = \chr(0xC0 | $code >> 6).\chr(0x80 | $code & 0x3F);
        } elseif (0x10000 > $code) {
            $s = \chr(0xE0 | $code >> 12).\chr(0x80 | $code >> 6 & 0x3F).\chr(0x80 | $code & 0x3F);
        } else {
            $s = \chr(0xF0 | $code >> 18).\chr(0x80 | $code >> 12 & 0x3F).\chr(0x80 | $code >> 6 & 0x3F).\chr(0x80 | $code & 0x3F);
        }

        if ('UTF-8' !== $encoding = self::getEncoding($encoding)) {
            $s = mb_convert_encoding($s, $encoding, 'UTF-8');
        }

        return $s;
    }

    public static function mb_ord($s, $encoding = null)
    {
        if ('UTF-8' !== $encoding = self::getEncoding($encoding)) {
            $s = mb_convert_encoding($s, 'UTF-8', $encoding);
        }

        if (1 === \strlen($s)) {
            return \ord($s);
        }

    