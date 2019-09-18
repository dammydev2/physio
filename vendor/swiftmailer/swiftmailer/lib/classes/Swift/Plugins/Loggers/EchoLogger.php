nt
     */
    protected function uRShift($a, $b)
    {
        if (0 == $b) {
            return $a;
        }

        return ($a >> $b) & ~(1 << (8 * PHP_INT_SIZE - 1) >> ($b - 1));
    }

    /**
     * Right padding with 0 to certain length.
     *
     * @param string $input
     * @param int    $bytes Length of bytes
     * @param bool   $isHex Did we provided hex value
     *
     * @return string
     */
    protected function createByte($input, $bytes = 4, $isHex = true)
    {
        if ($isHex) {
            $byte = hex2bin(str_pad($input, $bytes * 2, '00'));
        } else {
            $byte = str_pad($input, $bytes, "\x00");
        }

        return $byte;
    }

    /** ENCRYPTION ALGORITHMS */

    /**
     * DES Encryption.
     *
     * @param string $value An 8-byte string
     * @param string $key
     *
     * @return string
     */
    protected function desEncrypt($value, $key)
    {
        return substr(openssl_encrypt($value, 'DES-ECB', $key, \OPENSSL_RAW_DATA), 0, 8);
    }

    /**
     * MD5 Encryption.
     *
     * @param string