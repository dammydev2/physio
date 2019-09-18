 $this->rawFormat('Y-m-d H:i:s');
    }

    /**
     * Format the instance as date and time T-separated with no timezone
     *
     * @example
     * ```
     * echo Carbon::now()->toDateTimeLocalString();
     * ```
     *
     * @return string
     */
    public function toDateTimeLocalString()
    {
        return $this->rawFormat('Y-m-d\TH:i:s');
    }

    /**
     * Format the instance with day, date and time
     *
     * @example
     * ```
     * echo Carbon::now()->toDayDateTimeString();
     * ```
     *
     * @return string
     */
    public function toDayDateTimeString()
    {
        return $this->rawFormat('D, M j, Y g:i A');
    }

    /**
     * Format the instance as ATOM
     *
     * @example
     * ```
     * echo Carbon::now()->toAtomString();
     * ```
     *
     * @return string
     */
    public function toAtomString()
    {
        return $this->rawFormat(DateTime::ATOM);
    }

    /**
     * Format the instance as COOKIE
     *
     * @example
     * ```
     * echo Carbon::now()->toCookieString();
     * ```
     *
     * @return string
     */
    public function toCookieString()
    {
        return $this->r