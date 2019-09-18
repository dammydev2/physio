    return str_replace(':other', $this->getDisplayableAttribute($parameters[0]), $message);
    }

    /**
     * Replace all place-holders for the mimetypes rule.
     *
     * @param  string  $message
     * @param  string  $attribute
     * @param  string  $rule
     * @param  array   $parameters
     * @return string
     */
    protected function replaceMimetypes($message, $attribute, $rule, $parameters)
    {
        return str_replace(':values', implode(', ', $parameters), $message);
    }

    /**
     * Replace all place-holders for the mimes rule.
     *
     * @param  string  $message
     * @param  string  $attribute
     * @param  string  $rule
     * @param  array   $parameters
     * @return string
     */
    protected function replaceMimes($message, $attribute, $rule, $parameters)
    {
        return str_replace(':values', implode(', ', $parameters), $message);
    }

    /**
     * Replace all place-holders for the required_with rule.
     *
     * @param  string  $messa