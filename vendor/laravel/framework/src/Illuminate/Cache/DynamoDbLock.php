RL after the job runs if the given condition is true.
     *
     * @param  bool  $value
     * @param  string  $url
     * @return $this
     */
    public function thenPingIf($value, $url)
    {
        return $value ? $this->thenPing($url) : $this;
    }

    /**
     * Register a callback to ping a given URL if the operation succeeds.
     *
     * @param  string  $url
     * @return $this
     */
    public function pingOnSuccess($url)
    {
        return $this->onSuccess(function () use ($url) {
            (new HttpClient)->get($url);
        });
    }

    /**
     * Register a callback to ping a given URL if the operation fails.
     *
     * @param  string  $url
     * @return $this
     */
    public function pingOnFailure($url)
    {
        return $this->onFailure(function () use ($url) {
            (new HttpClient)->get($url);
        });
    }

    /**
     * State that the command should run in background.
     *
     * @return $this
     */
    public function runInBackground()
    {
        $this->runInBackground = true;

        return $this;
    }

    /**
     * Set which user the command should run as.
     *
     * @param  string  $user
     * @return $this
     */
    public function user($user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Limit the environments the command should run in.
     *
     * @param  array|mixed  $environments
     * @return $this
     */
    pub