.= ' ON DELETE ' . $this->getForeignKeyReferentialActionSQL($foreignKey->getOption('onDelete'));
        }

        return $query;
    }

    /**
     * Returns the given referential action in uppercase if valid, otherwise throws an exception.
     *
     * @param string $action The foreign key referential action.
     *
     * @return string
     *
     * @throws InvalidArgumentException If unknown referential action given.
     */
    public function getForeignKeyReferentialActionSQL($action)
    {
        $upper = strtoupper($action);
        switch ($upper) {
            case 'CASCADE':
            case 'SET NULL':
            case 'NO ACTION':
            case 'RESTRICT':
            case 'SET DEFAULT':
                return $upper;
            default:
                throw new InvalidArgumentException('Invalid foreign key action: ' . $upper);
        }
    }

    /**
     * Obtains DBMS specific SQL code portion needed to set the FOREIGN KEY constraint
     * of a field declaration to be used in statements like CREATE TABLE.
     *
     * @return string
     *
     * @throws InvalidArgumentException
     */
    public function getForeignKeyBaseDeclarationSQL(ForeignKeyConstraint $foreignKey)
    {
        $sql = '';
        if (strlen($foreignKey->getName())) {
            $sql .= 'CONSTRAINT ' . $foreignKey->getQuotedName($this) . ' ';
        }
        $sql .= 'FOREIGN KEY (';

        if (count($foreignKey->getLocalColumns()) === 0) {
            throw n