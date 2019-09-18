<?php

namespace Doctrine\DBAL\Schema;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use InvalidArgumentException;
use function array_filter;
use function array_keys;
use function array_map;
use function array_search;
use function array_shift;
use function count;
use function is_string;
use function strtolower;

class Index extends AbstractAsset implements Constraint
{
    /**
     * Asset identifier instances of the column names the index is associated with.
     * array($columnName => Identifier)
     *
     * @var Identifier[]
     */
    protected $_columns = [];

    /** @var bool */
    protected $_isUnique = false;

    /** @var bool */
    protected $_isPrimary = false;

    /**
     * Platform specific flags for indexes.
     * array($flagName => true)
     *
     * @var true[]
     */
    protected $_flags = [];

    /**
     * Platform specific options
     *
     * @todo $_flags should eventually be refactored into options
     * @var mixed[]
     */
    private $options = [];

    /**
     * @param string   $indexName
     * @param string[] $columns
     * @param bool     $isUnique
     * @param bool     $isPrimary
     * @param string[] $flags
     * @param mixed[]  $options
     */
    public function __construct($indexName, array $columns, $isUnique = false, $isPrimary = false, array $flags = [], array $options = [])
    {
        $isUnique = $isUnique || $isPrimary;

        $this->_setName($indexName);
        $this->_isUnique  = $isUnique;
        $this->_isPrimary = $isPrimary;
        $this->options    = $options;

        foreach ($columns as $column) {
            $this->_addColumn($column);
        }
        foreach ($flags as $flag) {
            $this->addFlag($flag);
        }
    }

    /**
     * @param string $column
     *
     * @return void
     *
     * @throws InvalidArgumentException
     */
    protected function _addColumn($column)
    {
        if (! is_string($column)) {
            throw new InvalidArgumentException('Expecting a string as Index Column');
        }

        $this->_columns[$column] = new Identifier($column);
    }

    /**
     * {@inheritdoc}
     */
    public function getColumns()
    {
        return array_keys($this->_columns);
    }

    /**
     * {@inheritdoc}
     */
    public function getQuotedColumns(AbstractPlatform $platform)
    {
        $subParts = $platform->supportsColumnLengthIndexes() && $this->hasOption('lengths')
            ? $this->getOption('lengths') : [];

        $columns = [];

        foreach ($this->_columns as $column) {
            $length = array_shift($subParts);

            $quotedColumn = $column->getQuotedName(