ey, $format));
            }
        }

        return $this;
    }

    /**
     * Assert that the session has no errors.
     *
     * @return $this
     */
    public function assertSessionHasNoErrors()
    {
        $hasErrors = $this->session()->has('errors');

        $errors = $hasErrors ? $this->session()->get('errors')->all() : [];

        PHPUnit::assertFalse(
            $hasErrors,
            'Session has unexpected errors: '.PHP_EOL.PHP_EOL.
            json_encode($errors, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)
        );

        return $this;
    }

    /**
     * Assert that the session has the given errors.
     *
     * @param  string  $errorBag
     * @param  string|array  $keys
     * @param  mixed  $format
     * @return $this
     */
    public function assertSessionHasErrorsIn($errorBag, $keys = [], $format = null)
    {
        return $this->assertSessionHasErrors($keys, $format, $errorBag);
    }

    /**
     * Assert that the session does not have a given key.
     *
     * @param  string|array  $key
     * @return $this
  