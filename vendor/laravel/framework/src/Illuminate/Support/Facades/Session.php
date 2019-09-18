d try to find a wildcard match for the
        // given key. Otherwise, we will simply return the key's value back out.
        $shortKey = preg_replace(
            '/^validation\.custom\./', '', $key
        );

        return $this->getWildcardCustomMessages(Arr::dot(
            (array) $this->translator->trans('validation.custom')
        ), $shortKey, $key);
    }

    /**
     * Check the given messages for a wildcard key.
     *
     * @param  array  $messages
     * @param  string  $search
     * @param  string  $default
     * @return string
     */
    protected function getWildcardCustomMessages($messages, $search, $default)
    {
        foreach ($messages as $key => $message) {
            if ($search === $key || (Str::contains($key, ['*']) && Str::is($key, $search))) {
                return $message;
            }
        }

        return $default;
    }

    /**
     * Get the proper error message for an attribute and size rule.
     *
     * @param  string  $attribute
     * @param  string  $rule
     * @return string
     */
    protected function getSizeMessage($attribute, $rule)
    {
        $lowerRule = Str::snake($rule);

        // There are three different types of size validations. The attribute may be
        // either a number, file, or string so we will check a few things to know
        // 