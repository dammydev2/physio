ns($permissions)
    {
        // remove the type identifier
        $permissions = substr($permissions, 1);

        // map the string rights to the numeric counterparts
        $map = ['-' => '0', 'r' => '4', 'w' => '2', 'x' => '1'];
        $permissions = strtr($permissions, $map);

        // split up the permission groups
        $parts = str_split($permissions, 3);

        // convert the groups
        $mapper = function ($part) {
            return array_sum(str_split($part));
        };

        // converts to decimal number
        return octdec(implode('', array_map($mapper, $parts)));
    }

    /**
     * Filter out dot-directories.
     *
     * @param array $list
     *
     * @return array
     */
    public function removeDotDirectories(array $list)
    {
        $filter = function ($line) {
            return $line !== '' && ! preg_match('#.* \.(\.)?$|^total#', $line);
        };

        return array_filter($list, $filter);
    }

    /**
     * @inheritdoc