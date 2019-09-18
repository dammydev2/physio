          $onDelete = $match[1];
        }

        if (preg_match('/FOREIGN KEY \((.+)\) REFERENCES (.+)\((.+)\)/', $tableForeignKey['condef'], $values)) {
            // PostgreSQL returns identifiers that are keywords with quotes, we need them later, don't get
            // the idea to trim them here.
            $localColumns   = array_map('trim', explode(',', $values[1]));
            $foreignColumns = array_map('trim', explode(',', $values[3]));
            $foreignTable   = $values[2];
        }

        return new ForeignKeyConstraint(
            $localColumns,
            $foreignTable,
            $foreignColumns,
            $tableForeignKey['conname'],
            ['onUpdate' => $onUpdate, 'onDelete' => $onDelete]
        );
    }

    /