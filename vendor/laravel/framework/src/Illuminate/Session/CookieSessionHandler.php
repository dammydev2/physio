nsform it and return
        // the message. Otherwise, we will check if the key is implicit & collect
        // all the messages that match the given key and output it as an array.
        if (array_key_exists($key, $this->messages)) {
            return $this->transform(
                $this->messages[$key], $this->checkFormat($format), $key
            );
        }

        if (Str::contains($key, '*')) {
            return $this->getMessagesForWildcardKey($key, $format);
        }

        return [];
    }

    /**
     * Get the messages for a wildcard key.
     *
     * @param  string  $key
     * @param  string|null  $format
     * @return array
     */
    protected function getMessagesForWildcardKey($key, $format)
    {
        return collect($this->messages)
                ->filter(function ($messages, $messageKey) use ($key) {
                    return Str::is($key, $messageKey);
                })
                ->map(function ($messages, $messageKey) use ($format) {
                    return $this->transform(
                        $messages, $this->checkFormat($format), $messageKey
                    );
                })->all();
    }

    /**
     * Get all of the messages for every key in the message bag.
     *
     * @param  string  $format
     * @return array
     */
    public function all($format = null)
    {
        $format = $this->checkFormat($format);

        $all = [];

        foreach ($this->messages as $key => $messages) {
            $all = array_merge($all, $this->transform($messages, $format, $key));
        }

        return $all;
    }

    /**
     * Get all of the unique messages for every key in the message bag.
     *
     * @param  string  $format
     * @return array
     */
    public function unique($format = null)
    {
        return array_unique($this->all($format));
    }

    /**
     * Format an array of messages.
     *
     * @param  array   $messages
     * @param  string  $format
     * @param  string  $messageKey
     * @return array
     */
    protected function transform($messages, $format, $messageKey)
    {
        return collect((array) $messages)
            ->map(function ($message) use ($format, $messageKey) {
                // We will simply spin through the given messages and transform each one
                // replacing the :message place holder with the real message allowing
                // the messages to be easily formatted to each developer's desires.
