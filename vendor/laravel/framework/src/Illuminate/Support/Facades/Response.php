ure|string  $extension
     * @return void
     */
    public function addDependentExtension($rule, $extension)
    {
        $this->addExtension($rule, $extension);

        $this->dependentRules[] = Str::studly($rule);
    }

    /**
     * Register an array of custom validator message replacers.
     *
     * @param  array  $replacers
     * @return void
     */
    public function addReplacers(array $replacers)
    {
        if ($replacers) {
            $keys = array_map('\Illuminate\Support\Str::snake', array_keys($replacers));

            $replacers = array_combine($keys, array_values($replacers));
        }

        $this->replacers = array_merge($this->replacers, $replacers);
    }

    /**
     * Register a custom validator message replacer.
     *
     * @param  string  $rule
     * @param  \Closure|string  $replacer
     * @return void
     */
    public function addReplacer($rule, $replacer)
    {
        $this->replacers[Str::snake($rule)] = $replacer;
    }

    /**
     * Set the custom messages for the validator.
     *
     * @param  array  $messages
     * @return $this
     */
    public function setCustomMessages(array $messages)
    {
        $this->customMessages = array_merge($this->customMessages, $messages);

        return $this;
    }

    /**
     * Set the custom attributes on the validator.
     *
     * @param  array  $attributes
     * @return $this
     */
    public function setAttributeNames(array $attributes)
    {
        $this->customAttributes = $attributes;

        return $this;
    }

    /**
     * Add custom attributes to the validator.
     *
     * @param  array  $customAttributes
     * @return $this
     */
    public function addCustomAttributes(array $customAttributes)
    {
        $this->customAttributes = array_merge($this->customAttributes, $customAttributes);

        return $this;
    }

    /**
     * Set the custom values on the validator.
     *
     * @param  array  $values
     * @return $this
     */
    public function setValueNames(array $values)
    {
        $this->customValues = $values;

        return $this;
    }

    /**
     * Add the custom values for the validator.
     *
     * @param  array  $customVal