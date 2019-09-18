 $type |= self::EXCLUDE_EMPTY & $filter;
            }
            if ((self::EXCLUDE_NOT_IMPORTANT & $filter) && !\in_array($k, $listedProperties, true)) {
                $type |= self::EXCLUDE_NOT_IMPORTANT;
            }
            if ((self::EXCLUDE_VERBOSE & $filter) && \in_array($k, $listedProperties, true)) {
                $type |= self::EXCLUDE_VERBOSE;
            }

            if (!isset($k[1]) || "\0" !== $k[0]) {
                $type |= self::EXCLUDE_PUBLIC & $filter;
            } elseif ('~' === $k[1]) {
                $type |= self::EXCLUDE_VIRTUAL & $filter;
            } elseif ('+' === $k[1]) {
                $type |= self::EXCLUDE_DYNAMIC & $filter;
            } elseif ('*' === $k[1]) {
                $type |= self::EXCLUDE_PROTECTED & $filter;
            } else {
