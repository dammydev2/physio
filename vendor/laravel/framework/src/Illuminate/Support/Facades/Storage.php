cify language lines for any attribute in this
            // application, which allows flexibility for displaying a unique displayable
            // version of the attribute name instead of the name used in an HTTP POST.
            if ($line = $this->getAttributeFromTranslations($name)) {
                return $line;
            }
        }

        // When no language line has been specified for the attribute and it is also
        // an implicit attribute we will display the raw attribute's name and not
        // modify it with any of these replacements before we display the name.
        if (isset($this->implicitAttributes[$primaryAttribute])) {
            return $attribute;
        }

        return str_replace('_', ' ', Str::snake($attribute));
    }

    /**
     * Get the given attribute from the attribute translations.
     *
     * @param  string  $name
     * @return string
     */
    protected function getAttributeFromTranslations($name)
    {
        return Arr::get($this->translator->trans('validation.attributes'), $name);
    }

    /**
     * Replace the :attribute placeholder in the given message.
     *
     * @param  string  $message
     * @param  string  $value
     * @return string
     */
    protected function replaceAttributePlaceholder($message, $value)
    {
        return str_replace(
            [':attribute', ':ATTRIBUTE', ':Attribute'],
            [$value, Str::upper($value), Str::ucfirst($value)],
       