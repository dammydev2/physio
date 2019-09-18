    }

        return ! is_array($value) && in_array((string) $value, $parameters);
    }

    /**
     * Validate that the values of an attribute is in another attribute.
     *
     * @param  string  $attribute
     * @param  mixed   $value
     * @param  array   $parameters
     * @return bool
     */
    public function validateInArray($attribute, $value, $parameters)
    {
        $this->requireParameterCount(1, $parameters, 'in_array');

        $explicitPath = ValidationData::getLeadingExplicitAttributePath($parameters[0]);

        $attributeData = ValidationData::extractDataFromPath($explicitPath, $this->data);

        $otherValues = Arr::where(Arr::dot($attributeData), function ($value, $key) use ($parameters) {
            return Str::is($parameters[0], $key);
        });

        return in_array($value, $otherValues);
    }

    /**
     * Validate that an attribute is an integer.
     *
     * @param  string  $attribute
     * @param  mixed   $value
     * @return bool
     */
    public function validateInteger($attribute, $value)
    {
        return filter_var($value, FILTER_VALIDATE_INT) !== false;
    }

    /**
     * Validate that an attribute is a valid IP.
     *
     * @param  string  $attribute
     * @param  mixed   $value
     * @return bool
     */
    public function validateIp($attribute, $value)
    {
        return filter_var($value, FILTER_VALIDATE_IP) !== false;
    }

    /**
     * Validate that an attribute is a valid IPv4.
     *
     * @param  string  $attribute
     * @param  mixed   $value
     * @return bool
     */
    public function validateIpv4($attribute, $value)
    {
        return filter_var($value, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4) !== false;
    }

    /**
     * Validate that an attribute is a valid IPv6.
     *
     * @param  string  $attribute
     * @param  mixed   $value
     * @return bool
     */
    public function validateIpv6($attribute, $value)
    {
        return filter_var($value, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6) !== false;
    }

    /**
     * Validate the attribute is a valid JSON string.
     *
     * @param  string  $attribute
     * @param  mixed   $value
     * @return bool
     */
    public function validateJson($attribute, $value)
    {
        if (! is_scalar($value) && ! method_exists($value, '__toString')) {
            return false;
        }

        json_decode($value);

        return json_last_error() === JSON_ERROR_NONE;
    }

    /**
     * Validate the size of an attribute is less than a maximum value.
     *
     * @param  string  $attribute
     * @param  mixed   $value
     * @param  array   $parameters
     * @return bool
     */
    public function validateMax($attribute, $value, $parameters)
    {
        $this->requireParameterCount(1, $parameters, 'max');

        if ($value instanceof UploadedFile && ! $value->isValid()) {
            return false;
        }

        return $this->getSize($attribute, $value) <= $parameters[0];
    }

    /**
     * Validate the guessed extension of a file upload is in a set of file extensions.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @param  array   $parameters
     * @return bool
     */
    public function validateMimes($attribute, $value, $parameters)
    {
        if (! $this->isValidFileInstance($value)) {
            return false;
        }

        if ($this->shouldBlockPhpUpload($value, $parameters)) {
            return false;
        }

        return $value->getPath() !== '' && in_array($value->guessExtension(), $parameters);
    }

    /**
     * Validate the MIME type of a file upload attribute is in a set of MIME types.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @param  array  $parameters
     * @return bool
     */
    public function validateMimetypes($attribute, $value, $parameters)
    {
        if (! $this->isValidFileInstance($value)) {
            return false;
        }

        if ($this->shouldBlockPhpUpload($value, $parameters)) {
            return false;
        }

        return $value->getPath() !== '' &&
                (in_array($value->getMimeType(), $parameters) ||
                 in_array(explode('/', $value->getMimeType())[0].'/*', $parameters));
    }

    /**
     * Check if PHP uploads are explicitly allowed.
     *
     * @param  mixed  $value
     * @param  array  $parameters
     * @return bool
     */
    protected function shouldBlockPhpUpload($value, $parameters)
    {
        if (in_array('php', $parameters)) {
            return false;
        }

        $phpExtensions = [
            'php', 'php3', 'php4', 'php5', 'phtml',
        ];

        return ($value instanceof UploadedFile)
           ? in_array(trim(strtolower($value->getClientOriginalExtension())), $phpExtensions)
           : in_array(trim(strtolower($value->getExtension())), $phpExtensions);
    }

    /**
     * Validate the size of an attribute is greater than a minimum value.
     *
     * @param  string  $attribute
     * @param  mixed   $value
     * @param  array   $parameters
     * @return bool
     */
    public function validateMin($attribute, $value, $parameters)
    {
        $this->requireParameterCount(1, $parameters, 'min');

        return $this->getSize($attribute, $value) >= $parameters[0];
    }

    /**
     * "Indicate" validation should pass if value is null.
     *
     * Always returns true, just lets us put "nullable" in rules.
     *
     * @return bool
     */
    public function validateNullable()
    {
        return true;
    }

    /**
     * Validate an attribute is not contained within a list of values.
     *
     * @param  string  $attribute
     * @param  mixed   $value
     * @param  array   $parameters
     * @return bool
     */
    public function validateNotIn($attribute, $value, $parameters)
    {
        return ! $this->validateIn($attribute, $value, $parameters);
    }

    /**
     * Validate that an attribute is numeric.
     *
     * @param  string  $attribute
     * @param  mixed   $value
     * @return bool
     */
    public function validateNumeric($attribute, $value)
    {
        return is_numeric($value);
    }

    /**
     * Validate that an attribute exists even if not filled.
     *
     * @param  string  $attribute
     * @param  mixed   $value
     * @return bool
     */
    public function validatePresent($attribute, $value)
    {
        return Arr::has($this->data, $attribute);
    }

    /**
     * Validate that an attribute passes a regular expression check.
     *
     * @param  string  $attribute
     * @param  mixed   $value
     * @param  array   $parameters
     * @return bool
     */
    public function validateRegex($attribute, $value, $parameters)
    {
        if (! is_string($value) && ! is_numeric($value)) {
            return false;
        }

        $this->requireParameterCount(1, $parameters, 'regex');

        return preg_match($parameters[0], $value) > 0;
    }

    /**
     * Validate that an attribute does not pass a regular expression check.
     *
     * @param  string  $attribute
     * @param  mixed   $value
     * @param  array   $parameters
     * @return bool
     */
    public function validateNotRegex($attribute, $value, $parameters)
    {
        if (! is_string($value) && ! is_numeric($value)) {
            return false;
        }

        $this->requireParameterCount(1, $parameters, 'not_regex');

        return preg_match($parameters[0], $value) < 1;
    }

    /**
     * Validate that a required attribute exists.
     *
     * @param  string  $attribute
     * @param  mixed   $value
     * @return bool
     */
    public function validateRequired($attribute, $value)
    {
        if (is_null($value)) {
            return false;
        } elseif (is_string($value) && trim($value) === '') {
            return false;
        } elseif ((is_array($value) || $value instanceof Countable) && count($value) < 1) {
            return false;
        } elseif ($value instanceof File) {
            return (string) $value->getPath() !== '';
        }

        return true;
    }

    /**
     * Validate that an attribute exists when another attribute has a given value.
     *
     * @param  string  $attribute
     * @param  mixed   $value
     * @param  mixed   $parameters
     * @return bool
     */
    public function validateRequiredIf($attribute, $value, $parameters)
    {
        $this->requireParameterCount(2, $parameters, 'required_if');

        $other = Arr::get($this->data, $parameters[0]);

        $values = array_slice($parameters, 1);

        if (is_bool($other)) {
            $values = $this->convertValuesToBoolean($values);
        }

        if (in_array($other, $values)) {
            return $this->validateRequired($attribute, $value);
        }

        return true;
    }

    /**
     * Convert the given values to boolean if they are string "true" / "false".
     *
     * @param  array  $values
     * @return array
     */
    protected function convertValuesToBoolean($values)
    {
        return array_map(function ($value) {
            if ($value === 'true') {
                return true;
            } elseif ($value === 'false') {
                return false;
            }

            return $value;
        }, $values);
    }

    /**
     * Validate that an attribute exists when another attribute does not have a given value.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * 