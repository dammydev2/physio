   public function tld()
    {
        return static::randomElement(static::$tld);
    }

    /**
     * @example 'http://www.runolfsdottir.com/'
     */
    public function url()
    {
        $format = static::randomElement(static::$urlFormats);

        return $this->generator->parse($format);
    }

    /**
     * @example 'aut-repellat-commodi-vel-itaque-nihil-id-saepe-nostrum'
     */
    public function slug($nbWords = 6, $variableNbWords = true)
    {
        if ($nbWords <= 0) {
            return '';
        }
        if ($variableNbWords) {
            $nbWords = (int) ($nbWords * mt_rand(60, 140) / 100) + 1;
        }
        $words = $this->generator->words($nbWords);

        return join($words, '-');
    }

    /**
     * @example '237.149.115.38'
     */
    public function ipv4()
    {
        return long2ip(mt_rand(0, 1) == 0 ? mt_rand(-2147483648, -2) : mt_rand(16777216, 2147483647));
    }

    /**
     * @example '35cd:186d:3e23:2986:ef9f:5b41:42a4:e6f1'
     */
    public function ipv6()
    {
       