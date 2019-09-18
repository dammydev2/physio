Replace all place-holders for the gte rule.
     *
     * @param  string  $message
     * @param  string  $attribute
     * @param  string  $rule
     * @param  array   $parameters
     * @return string
     */
    protected function replaceGte($message, $attribute, $rule, $parameters)
    {
        if (is_null($value = $this->getValue($parameters[0]))) {
            return str_replace(':value', $parameters[0], $message);
        }

        return str_replace(':value', $this->getSize($attribute, $value), $message);
    }

    /**
     * Replace all place-holders for the lte rule.
     *
     * @param  string  $message
     * @param  string  $attribute
     * @param  string  $rule
     * @param  array   $parameters
     * @return string
     */
    protected function replaceLte($message, $attribute, $rule, $parameters)
    {
        if (is_null($value = $this->getValue($parameters[0]))) {
            return str_replace(':value', $parameters[0], $message);
        }

        return str_replace(':value', $this->getSize($attribute, $value), $message);
    }

    /**
     * Replace all place-holders for the required_if rule.
     *
     * @param  string  $message
     * @param  string  $attribute
     * @param  string  $rule
     * @param  array   $parameters
     * @return string
     */
    protected function replaceRequiredIf($message, $attribute, $rule, $parameters)
    {
        $parameters[1] = $this->getDisplayableValue($parameters[0], Arr::get($this->data, $parameters[0]));

        $parameters[0] = $this->getDisplayableAttribute($parameters[0]);

        return str_replace([':other', ':value'], $parameters, $message);
    }

    /**
     * Replace all place-holders for the required_unless rule.
     *
     * @param  string  $message
     * @param  string  $attribute
     * @param  string  $rule
     * @param  array   $parameters
     * @return string
     */
    protected function replaceRequiredUnless($message, $attribute, $rule, $parameters)
    {
        $other = $this->getDisplayableAttribute($parameters[0]);

        $values = [];

        foreach (array_slice($parameters, 1) as $value) {
            $values[] = $this->getDisplayableValue($parameters[0], $value);
        }

        return str_replace([':other', ':values'], [$other, implode(', ', $values)], $message);
    }

    /**
     * Replace all place-holders for the same rule.
     *
     * @param  string  $message
     * @param  string  $attribute
     * @param  string  $rule
     * @param  array   $parameters
     * @return string
     */
    protected function replaceSame($message, $attribute, $rule, $parameters)
    {
        return str_replace(':other', $this->getDisplayableAttribute($parameters[0]), $message);
    }

    /**
     * Replace all place-holders for the before rule.
     *
     * @param  string  $message
     * @param  string  $attribute
     * @param  string  $rule
     * @param  array   $parameters
     * @return string
     */
    protected function replaceBefore($message, $attribute, $rule, $parameters)
    {
        if (! strtotime($parameters[0])) {
            return str_replace(':date', $this->getDisplayableAttribute($parameters[0]), $message);
        }

        return str_replace(':date', $this->getDisplayableValue($attribute, $parameters[0]), $message);
    }

    /**
     * Replace all place-holders for the before_or_equal rule.
     *
     * @param  string  $message
     * @param  string  $attribute
     * @param  string  $rule
     * @param  array   $parameters
     * @return string
     */
    protected function replaceBeforeOrEqual($message, $attribute, $rule, $parameters)
    {
        return $this->replaceBefore($message, $attribute, $rule, $parameters);
    }

    /**
     * Replace all place-holders for the after rule.
     *
     * @param  string  $message
     * @param  string  $attribute
     * @param  string  $rule
     * @param  array   $parameters
     * @return string
     