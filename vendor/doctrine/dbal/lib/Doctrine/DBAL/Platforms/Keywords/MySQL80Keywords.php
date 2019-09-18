<?php

namespace Doctrine\DBAL\Schema;

use Doctrine\DBAL\Types\Type;
use function explode;
use function strtolower;
use function trim;

/**
 * Schema manager for the Drizzle RDBMS.
 */
class DrizzleSchemaManager extends AbstractSchemaManager
{
    /**
     * {@inheritdoc}
     */
    protected function _getPortableTableColumnDefinition($tableColumn)
    {
        $dbType = strtolower($tableColumn['DATA_TYPE']);

        $type                          = $this->_platform->getDoctrineTypeMapping($dbType);
        $type                          = $this->extractDoctrineTypeFromComment($tableColumn['COLUMN_COMMENT'], $type);
        $tableColumn['COLUMN_COMMENT'] = $this->removeDoctrineTypeFromComment($tableColumn['COLUMN_COMMENT'], $type);

        $options = [
            'notnull' => ! (bool) $tableColumn['IS_NULLABLE'],
            'length' => (int) $tableColumn['CHARACTER_MAXIMUM_LENGTH'],
            'default' => $tableColumn['COLUMN_DEFAULT'] ?? null,
            'autoincrement' => (bool) $tableColumn['IS_AUTO_INCREMENT'],
            'scale' => (int) $tableColumn['NUMERIC_SCALE'],
            'precision' => (int) $tableColumn['NUMERIC_PRECISION'],
            'comment' => isset($tableCo