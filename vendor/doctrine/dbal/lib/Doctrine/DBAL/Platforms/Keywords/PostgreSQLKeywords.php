 = $this->getColumns();
        $numberOfColumns = count($columns);
        $sameColumns     = true;

        for ($i = 0; $i < $numberOfColumns; $i++) {
            if (isset($columnNames[$i]) && $this->trimQuotes(strtolower($columns[$i])) === $this->trimQuotes(strtolower($columnNames[$i]))) {
                continue;
            }

            $sameColumns = false;
        }

        return $sameColumns;
    }

    /**
     * Checks if the other index already fulfills all the indexing and constraint needs of the current one.
     *
     * @return bool
     */
    public function isFullfilledBy(Index $other)
    {
        // allow the other index to be equally large only. It being larger is an option
        // but it creates a problem with scenarios of the kind PRIMARY KEY(foo,bar) UNIQUE(foo)
        if (count($other->getColumns()) !== count($this->getColumns())) {
            return false;
        }

        // Check if columns are the same, and even in the same order
        $sameColumns = $this->spansColumns($other->getColumns());

        if ($sameColumns) {
            if (! $this->samePartialIndex($other)) {
                return false;
            }

            if (! $this->hasSameColumnLengths($other)) {
                return false;
            }

            if (! $this->isUnique() && ! $this->isPrimary()) {
                // this is a special case: If the current key is neither primary or unique, any unique or
                // primary key will always have the same effect for the index and there cannot be any constraint
                // overlaps. This means a primary or unique index can always fulfill the requirements of just an
                // index that has no constraints.
                return true;
            }

            if ($other->isPrimary() !== $this->isPrimary()) {
                return false;
            }

            return $other->isUnique() === $this->isUnique();
        }

        return false;
    }

    /**
     * Detects if the other index is a non-unique, non primary index that can be overwritten by this one.
     *
     * @return bool
     */
    public function overrules(Index $other)
    {
        if ($other->isPrimary()) {
            return false;
        } elseif