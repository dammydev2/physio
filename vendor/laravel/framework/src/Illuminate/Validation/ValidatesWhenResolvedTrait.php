lue)
    {
        foreach ($this->extensions as $compiler) {
            $value = call_user_func($compiler, $value, $this);
        }

        return $value;
    }

    /**
     * Compile Blade statements that start with "@".
     *
     * @param  string  $value
     * @return string
     */
    protected function compileStatements($value)
    {
        return preg_replace_callback(
            '/\B@(@?\w+(?:::\w+)?)([ \t]*)(\( ( (?>[^()]+) | (?3) )* \))?/x', function ($match) {
                return $this->compileStatement($match);
            }, $value
        );
    }

    /**
     * Compile a single Blade @ statement.
     *
     * @param  array  $match
     * @return string
     */
    protected function compileStatement($match)
    {
        if (Str::contains($match[1], '@')) {
            $match[0] = isset($match[3]) ? $match[1].$match[3] : $match[1];
        } elseif (isset($this->customDirectives[$match[1]])) {
            $match[0] = $this->callCustomDirective($match[1], Arr::get($match, 3));
        } elseif (method_exists($this, $method = 'compile'.ucfirst($match[1]))) {
            $match[0] = $this->$method(Arr::get($match, 3));
        }

        return isset($match[3]) ? $match[0] : $match[0].$match[2];
    }

    /**
     * Call the given directive with the given value.
     *
     * @param  string  $name
     * @param  string|null  $value
     * @return string
     */
    protected function callCustomDirective($name, $value)
    {
        if (Str::startsWith($value, '(') && Str::endsWith($value, ')')) {
            $value = Str::substr($value, 1, -1);
        }

        return call_user_func($this->customDirectives[$name], trim($value));
    }

    /**
     * Strip the parentheses from the given expression.
     *
     * @param  string  $expression
     * @return string
     */
    