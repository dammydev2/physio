lete' => $value['on_delete'],
                    'onUpdate' => $value['on_update'],
                    'deferrable' => $value['deferrable'],
                    'deferred'=> $value['deferred'],
                ];
            }
            $list[$name]['local'][]   = $value['from'];
            $list[$name]['foreign'][] = $value['to'];
        }

        $result = [];
        foreach ($list as $constraint) {
            $result[] = new ForeignKeyConstraint(
                array_values($constraint['local']),
                $constraint['foreignTable'],
                array_values($constraint['foreign']),
                $constraint['name'],
                [
                    'onDelete' => $constraint['onDelete'],
                    'onUpdate' => $constraint['onUpdate'],
                    'deferrable' => $constraint['deferrable'],
                    'deferred'=> $constraint['deferred'],
                ]
            );
        }

        return $result;
    }

    /**
     * @param Table|string $table
     *
     * @