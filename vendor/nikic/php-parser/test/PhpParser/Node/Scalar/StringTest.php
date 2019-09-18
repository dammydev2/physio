tValue($data, $item);
                    }
                }
            } while($reflection = $reflection->getParentClass());
        }
    }

    /**
     * Internal method used to map closures by reference
     *
     * @internal
     * @param   mixed &$data
     */
    protected function mapByReference(&$data)
    {
        if ($data instanceof Closure) {
            if($data === $this->closure){
                $data = new SelfReference($this->reference);
                return;
            }

            if (isset($this->scope[$data])) {
                $data = $this->scope[$data];
                return;
            }

            $instance = new static($data);

            if (static::$context !== null) {
                static::$context->scope->toserialize--;
            } else {
                $instance->scope = $this->scope;
            }

            $data = $this->scope[$data] = $instance;
        } elseif (is_array($data)) {
            if(isset($data[self::ARRAY_RECURSIVE_KEY])){
                return;
            }
            $data[self::ARRAY_RECURSIVE_KEY] = true;
            foreach ($data as $key => &$value){
                if($key === self::ARRAY_RECURSIVE_KEY){
                    continue;
                }
                $this->mapByReference($value);
            }
            unset($value);
            unset($data[self::ARRAY_RECURSIVE_KEY]);
        } elseif ($data instanceof \stdClass) {
            if(isset($this->scope[$data])){
                $data = $this->scope[$data];
                return;
            }
            $instance = $data;
            $this->scope[$instance] = $data = clone($data);

            foreach ($data as &$value){
                $this->mapByReference($value);
            }
            unset($value);
        } elseif (is_object($data)