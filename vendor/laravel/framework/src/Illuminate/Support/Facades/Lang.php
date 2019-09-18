    $results, $attribute, $rules
        );
    }

    /**
     * Merge additional rules into a given attribute.
     *
     * @param  array  $results
     * @param  string  $attribute
     * @param  string|array  $rules
     * @return array
     */
    protected function mergeRulesForAttribute($results, $attribute, $rules)
    {
        $merge = head($this->explodeRules([$rules]));

        $results[$attribute] = array_merge(
            isset($results[$attribute]) ? $this->explodeExplicitRule($results[$attribute]) : [], $merge
        );

        return $results;
    }

    /**
     * Extract the rule name and parameters from a rule.
     *
     * @param  array|string  $rules
     * @return array
     */
    public stati