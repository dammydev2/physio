 'tinyblob':
                $length = MySqlPlatform::LENGTH_LIMIT_TINYBLOB;
                break;
            case 'blob':
                $length = MySqlPlatform::LENGTH_LIMIT_BLOB;
                break;
            case 'mediumblob':
                $length = MySqlPlatform::LENGTH_LIMIT_MEDIUMBLOB;
                break;
            case 'tinyint':
            case 'smallint':
            case 'mediumint':
            case 'int':
            case 'integer':
            case 'bigint':
            case 'year':
                $length = null;
                break;
        }

        if ($this->_platform instanceof MariaDb1027Platform) {
            $columnDefault = $this->getMariaDb1027ColumnDefault($this->_platform, $tableColumn['default']);
        } else {
            $columnDefault = $tableColumn['default'];
        }

        $options = [
            'length'        => $length !== null ? (int) $length : null,
            'unsigned'      => strpos($tableColumn['type'], 'unsigned