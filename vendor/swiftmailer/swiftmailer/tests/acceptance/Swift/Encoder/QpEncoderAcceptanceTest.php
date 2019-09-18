ngCheck($obj1_value, $obj2_value, $obj1_clone_value);
            } elseif (is_array($value)) {
                $obj1_value = $obj1_properties[$property];
                $obj2_value = $obj2_properties[$property];
                $obj1_clone_value = $obj1_clone_properties[$property];

                return $this->recursiveArrayCloningCheck($obj1_value, $obj2_value, $obj1_clone_value);
            }
        }
    }

    protected function recursiveArrayCloningCheck($array1, $array2, $array1_clone)
    {
        foreach ($array1 as $key => $value) {
            if (is_object($value)) {
                $arr1_value = $array1[$key];
                $arr2_value = $array2[$key];
                $arr1_clone_value = $array1_clone[$key];
                if ($arr1_value !== $arr2_value) {
                    // two separetely instanciated objects property not referencing same object
                    $this->assertFalse(
                        // but object's clone does - not everything copied
                        $arr1_value === $arr1_clone_value,
                        "Key `$key` cloning error: source and cloned objects property is referencing same object"
                    );
                } else {
                    // two separetely instanciated objects have same reference
                    $this->assertFalse(
                        // but object's clone doesn't - overdone making copies
                        $arr1_value !== $arr1_clone_value,
                        "Key `$key` not properly cloned: it should reference same object as cloning source (overdone copping)"
                    );
                }
                // recurse
                $this->recursiveObjectCloningCheck($arr1_value, $arr2_value, $arr1_clone_value);
            } elseif (is_array($value)) {
                $arr1_value = $array1[$key];
              