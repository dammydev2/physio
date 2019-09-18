  $parameters
     * @return bool
     */
    public function validateGt($attribute, $value, $parameters)
    {
        $this->requireParameterCount(1, $parameters, 'gt');

        $comparedToValue = $this->getValue($parameters[0]);

        $this->shouldBeNumeric($attribute, 'Gt');

        if (is_null($comparedToValue) && (is_numeric($value) && is_numeric($parameters[0]))) {
            return $this->getSize($attribute, $value) > $parameters[0];
        }

        if (! $this->isSameType($value, $comparedToValue)) {
            return false;
        }

        return $this->getSize($attribute, $value) > $this->getSize($attribute, $comparedToValue);
    }

    /**
     * Validate that an attribute is less than another attribute.
     *
     * @param  string  $attribute
     * @param  mixed   $value
     * @param  array   $parameters
     * @return bool
     */
    public function validateLt($attribute, $value, $parameters)
    {
        $this->requireParameterCount(1, $parameters, 'lt');

        $comparedToValue = $this->getValue($parameters[0]);

        $this->shouldBeNumeric($attribute, 'Lt');

        if (is_null($comparedToValue) && (is_numeric($value) && is_nu