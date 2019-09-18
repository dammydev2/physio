e of $count elements from a provided array
     *
     * @param  array            $array           Array to take elements from. Defaults to a-f
     * @param  integer          $count           Number of elements to take.
     * @param  boolean          $allowDuplicates Allow elements to be picked several times. Defaults to false
     * @throws \LengthException When requesting more elements than provided
     *
     * @return array New array with $count elements from $array
     */
    public static function randomElements($array = array('a', 'b', 'c'), $count = 1, $allowDuplicates = false)
    {
        $traversables = array();

        if ($array instanceof \Traversable) {
            foreach ($array as $element) {
                $traversables[] = $element;
            }
        }

        $arr = count($traversables) ? $traversables : $array;

        $allKeys = array_keys($arr);
        $numKeys = count($allKeys);

        if (!$allowDuplicates && $numKeys < $count) {
            throw new \LengthException(sprintf('Cannot get %d elements, only %d in array', $count, $numKeys));
        }

        $highKey = $numKeys - 1;
        $keys = $elements = array();
        $numElements = 0;

        while ($numElements < $count) {
            $num = mt_rand(0, $highKey);

            if (!$allowDuplicates) {
                if (isset($keys[$num])) {
                    continue;
                }
                $keys[$num] = true;
            }

            $elements[] = $arr[$allKeys[$num]];
            $numElements++;
        }

        return $elements;
    }

    /**
     * Returns a random element from a passed array
     *
     * @param  array $array
     * @return mixed
     */
    public static function randomElement($array = array('a', 'b', 'c'))
    {
        if (!$array || ($array instanceof \Traversable && !count($array))) {
            return null;
        }
        $elements = static::randomElements($array, 1);

        return $elements[0];
    }

    /**
     * Returns a random key from a passed associative array
     *
     * @param  array $array
     * @return int|string|null
     */
    public static function randomKey($array = array())
    {
        if (!$array) {
            return null;
        }
        $keys = array_keys($array);
        $key = $keys[mt_rand(0, count($keys) - 1)];

        return $key;
    }

    /**
     * Returns a shuffled version of the argument.
     *
     * This function accepts either an array, or a string.
     *
     * @example $faker->shuffle([1, 2, 3]); // [2, 1, 3]
     * @example $faker->shuffle('hello, world'); // 'rlo,h eold!lw'
     *
     * @see shuffleArray()
     * @see shuffleString()
     *
     * @param array|string $arg Th