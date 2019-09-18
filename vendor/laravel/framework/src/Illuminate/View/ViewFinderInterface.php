key = $this->transformKey($name);

        if (method_exists($this->model, 'getFormValue')) {
            return $this->model->getFormValue($key);
        }

        return data_get($this->model, $this->transformKey($name));
    }

    /**
     * Get a value from the session's old input.
     *
     * @param  string $name
     *
     * @return mixed
     */
    public function old($name)
    {
        if (isset($this->session)) {
            $key = $this->transformKey($name);
            $payload = $this->session->getOldInput($key);

            if (!is_array($payload)) {
                return $payload;
            }

            if (!in_array($this->type, ['select', 'checkbox'])) {
                if (!isset($this->payload[$key])) {
                    $this->payload[$key] = collect($payload);
                }

                if (!empty($this->payload[$key])) {
                    $value = $this->payload[$key]->shift();
                    return $value;
                }
            }

            return $payload;
        }
    }

    /**
     * Determine if the old input is empty.
     *
     * @return bool
     */
    public function oldInputIsEmpty()
    {
        return (isset($this->session) && count((array) $this->session->getOldInput()) === 0);
    }

    /**
     * Transform key from array to dot syntax.
     *
     * @param  string $key
     *
     * @return mixed
     */
    protected function transformKey($key)
