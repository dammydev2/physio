tinflectme'),
     *     'irregular' => array('red' => 'redlings')
     * ));
     * }}}
     *
     * @param string  $type         The type of inflection, either 'plural' or 'singular'
     * @param array|iterable $rules An array of rules to be added.
     * @param boolean $reset        If true, will unset default inflections for all
     *                              new rules that are being defined in $rules.
     *
     * @return void
     */
    public static function rules(string $type, iterable $rules, bool $reset = false) : void
    {
        foreach ($rules as $rule => $pattern) {
            if ( ! is_array($pattern)) {
                continue;
            }

            if ($reset) {
                self::${$type}[$rule] = $pattern;
            } else {
                self::${$type}[$rule] = ($rule === 'uninflected')
                    ? array_merge($pattern, self::${$type}[$rule])
                    : $pattern + self::${$type}[$rule];
            }

            unset($rules[$rule], self::${$type}['cache' . ucfirst($rule)]);

            if (isset(self::${$type}['merged'][$rule])) {
                unset(self::${$type}['merged'][$rule]);
            }

            if ($type === 'plural') {
                self::$cache['pluralize'] = self::$cache['tableize'] = array();
            } elseif ($type === 'singular') {
                self::$cache['singularize'] = array();
            }
        }

        self::${$type}['rules'] = $rules + self::${$type}['rules'];
    }

    /**
     * Returns a word in plural form.
     *
     * @param string $word The word in singular form.
     *
     * @return string The word