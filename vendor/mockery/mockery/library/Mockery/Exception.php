never straight up implement Traversable
             */
            if (!preg_match("/^\\\\?Traversable$/i", $targetInterface)) {
                $this->targetInterfaces[] = $dtc;
            }
        }
        $this->targetInterfaces = array_unique($this->targetInterfaces); // just in case
        return $this->targetInterfaces;
    }

    public function getTargetObject()
    {
        return $this->targetObject;
    }

    public function getName()
    {
        return $this->name;
    }

    /**
     * Generate a suitable name based on the config
     */
    public function generateName()
    {
        $name = 'Mockery_' . static::$mockCounter++;

        if ($this->getTargetObject()) {
            $name .= "_" . str_replace("\\", "_", get_cl