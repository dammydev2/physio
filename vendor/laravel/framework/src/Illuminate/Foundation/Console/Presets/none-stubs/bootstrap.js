e response count matched the expected {$count}"
        );

        return $this;
    }

    /**
     * Assert that the response has the given JSON validation errors for the given keys.
     *
     * @param  string|array  $keys
     * @return $this
     */
    public function assertJsonValidationErrors($keys)
    {
        $keys = Arr::wrap($keys);

        PHPUnit::assertNotEmpty($keys, 'No keys were provided.');

        $errors = $this->json()['errors'] ?? [];

        $errorMessage = $errors
                ? 'Response has the following JSON validation errors:'.
                        PHP_EOL.PHP_EOL.json_encode($errors, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE).PHP_EOL
                : 'Response does not have JSON validation errors.';

        foreach ($keys as $key) {
            PHPUnit::assertArrayHasKey(
                $key,
                $errors,
                "Failed to find a validation error in the response for key: '{$key}'".PHP_EOL.PHP_EOL.$errorMessage
            );
        }

        return $this;
    }

    /**
     * Assert that the response has no JSON validation errors for the given keys.
     *
     * @param  string|array  $keys
     * @return $this
     */
    public function assertJsonMissingValidationErrors($keys = null)
    {
        $json = $this->json();

        if (! array_key_e