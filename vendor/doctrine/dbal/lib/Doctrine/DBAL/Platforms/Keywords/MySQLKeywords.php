<?php

namespace Doctrine\DBAL\Schema;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use function array_combine;
use function array_keys;
use function array_map;
use function end;
use function explode;
use function in_array;
use function strtolower;
use function strtoupper;

/**
 * An abstraction class for a foreign key constraint.
 */
class ForeignKeyConstraint extends AbstractAsset implements Constraint
{
    /**
     * Instance of the referencing table the foreign key constraint is associated with.
     *
     * @var Table
     */
    protected $_localTable;

    /**
     * Asset identifier instances of the referencing table column names the foreign key constraint is associated with.
     * array($columnName => Identifier)
     *
     * @var Identifier[]
     */
    protected $_localColumnNames;

    /**
     * Table or asset identifier instance of the referenced table name the foreign key constraint is associated with.
     *
     * @var Table|Identifier
     */
    protected $_foreignTableName;

    /**
     * Asset identifier instances of the referenced table column names the foreign key constraint is associated with.
     * array($columnName => Identifier)
     *
     * @var Identifier[]
     */
    protected $_foreignColumnNames;

    /**
     * Options associated with the foreign key constraint.
     *
     * @var mixed[]
     */
    protected $_options;

    /**
     * Initializes the foreign key constraint.
     *
     * @param string[]     $localColumnNames   Names of the referencing table columns.
     * @param Table|string $foreignTableName   Referenced table.
     * @param string[]     $foreignColumnNames Names of the referenced table columns.
     * @param string|null  $name               Name of the foreign key constraint.
     * @param mixed[]      $options            Options associated with the foreign key constraint.
     */
    public function __construct(array $localColumnNames, $foreignTableName, array $foreignColumnNames, $name = null, array $options = [])
    {
        $this->_setName($name);
        $identifierConstructorCallback = static function ($column) {
            return new Identifier($column);
        };
        $this->_localColumnNames       = $localColumnNames
            ? array_combine($localColumnNames, array_map($identifierConstructorCallback, $localColumnNames))
            : [];

        if ($foreignTableName instanceof Table) {
            $this->_foreignTableName = $foreignTableName;
        } else {
            $this->_foreignTableName = new Identifier($foreignTableName);
        }

        $this->_foreignColumnNames = $foreignColumnNames
            ? array_combine($foreignColumnNames, array_map($identifierConstructorCallback, $foreignColumnNames))
            : [];
        $this->_options            = $options;
    }

    /**
     * Returns the name of the referencing table
     * the foreign key constraint is associated with.
     *
     * @return string
     */
    public function getLocalTableName()
    {
        return $this->_localTable->getName();
    }

    /**
     * Sets the Table instance of the referencing table
     * the foreign key constraint is associated with.
     *
     * @param Table $table Instance of the referencing table.
     *
     * @return void
     */
    public function setLocalTable(Table $table)
    {
        $this->_localTable = $table;
    }

    /**
     * @return Table
     */
    public function getLocalTable()
    {
        return $this->_localTable;
    }

    /**
     * Returns the names of the referencing table columns
     * the foreign key constraint is associated with.
     *
     * @return string[]
     */
    public function getLocalColumns()
    {
        return array_keys($this->_localColumnNames);
    }

    /**
     * Returns the quoted representation of the referencing table column names
     * the foreign key constraint is associated with.
     *
     * But only if they were defined with one or the referencing table column name
     * is a keyword reserved by the platform.
     * Otherwise the plain unquoted value as inserted is returned.
     *
     * @param AbstractPlatform $platform The platform to use for quotation.
     *
     * @return string[]
     */
    public function getQuotedLocalColumns(AbstractPlatform $platform)
    {
        $columns = [];

        foreach ($this->_localColumnNames as $column) {
            $columns[] = $column->getQuotedName($platform);
        }

        return $columns;
    }

    /**
     * Returns unquoted representation of local table column names for comparison with other FK
     *
     * @return string[]
     */
    public function getUnquotedLocalColumns()
    {
        return array_map([$this, 'trimQuotes'], $this->getLocalColumns());
    }

    /**
     * Returns unquoted representation of foreign table column names for comparison with other FK
     *
     * @return string[]
     */
    public function getUnquotedForeignColumns()
    {
        return array_map([$this, 'trimQuotes'], $this->getForeignColumns());
    }

    /**
     * {@inheritdoc}
     *
     * @see getLocalColumns
     */
    public function getColumns()
    {
        return $this->getLocalColumns();
    }

    /**
     * Returns the quoted representation of the referencing table column names
     * the foreign key constraint is associated with.
     *
     * But only if they were defined with one or the referencing table column name
     * is a keyword reserved by the platform.
     * Otherwise the plain unquoted value as inserted is returned.
     *
     * @see getQuotedLocalColumns
     *
     * @param AbstractPlatform $platform The platform to use for quotation.
     *
     * @return string[]
     */
    public function getQuotedColumns(AbstractPlatform $platform)
    {
        return $this->getQuotedLocalColu