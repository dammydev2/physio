f (is_numeric($value) && $hasNumeric) {
            return $value;
        } elseif (is_array($value)) {
            return count($value);
        } elseif ($value instanceof File) {
            return $value->getSize() / 1024;
        }

        return mb_strlen($value);
    }

    /**
     * Check that the given value is a valid file instance.
     *
     * @param  mixed  $value
     * @return bool
     */
    public function isValidFileInstance($value)
    {
        if ($value instanceof UploadedFile && ! $value->isValid()) {
            return false;
        }

        return $value instanceof File;
    }

    /**
     * Determine if a comparison passes between the given values.
     *
     * @param  mixed  $first
     * @param  mixed  $second
     * @param  string  $operator
     * @return bool
     *
     * @throws \InvalidArgumentException
     */
    protected function compare($first, $second, $operator)
    {
        switch ($operator) {
            case '<':
                return $first < $second;
            case '>':
                return $first > $second;
            case '<=':
                return $first <= $second;
            case '>=':
                return $first >= $second;
            case '=':
                return $first == $second;
            default:
                throw new InvalidArgumentException;
        }
    }