           $name,
            $options
        );
        $this->_addForeignKeyConstraint($constraint);

        return $this;
    }

    /**
     * @param string $name
     * @param string $value
     *
     * @return self
     */
    public function addOption($name, $value)
    {
        $this->_options[$name] = $value;

        return $this;
    }

    /**
     * @return void
     *
     * @throws SchemaException
     */
    protected function _addColumn(Column $column)
    {
        $columnName = $column->getName();
        $columnName = $this->normalizeIdentifier($columnName);

        if (isset($this->_columns[$columnName])) {
            throw SchemaException::columnAlreadyExists($this->getName(), $columnName);
        }

        $this->_columns[$columnName] = $column;
    }

    /**
     * Adds an index to the table.
     *
     * @return self
     *
     * @throws SchemaException
     */
    protected function _addIndex(Index $indexCandidate)
    {
        $indexName               = $indexCandidate->getName();
        $indexName               = $this->normalizeIdentifier($indexName);
        $replacedImplicitIndexes = [];

        foreach ($this->implicitIndexes as $name => $implicitIndex) {
            if (! $implicitIndex->isFullfilledBy($indexCandidate) || ! isset($this->_indexes[$name])) {
                continue;
            }

            $replacedImplicitIndexes[] = $name;
        }

        if ((isset($this->_indexes[$indexName]) && ! in_array($indexName, $replacedImplicitIndexes, true)) ||
            ($this->_primaryKeyName !== false && $indexCandidate->isPrimary())
        ) {
            throw SchemaException::indexAlreadyExists($indexName, $this->_name);
        }

        foreach ($replacedImplicitIndexes as $name) {
            unset($this->_indexes[$name], $this->implicitIndexes[$name]);
        }

        if ($indexCandidate->isPrimary()) {
            $this->_primaryKeyName = $indexName;
        }

        $this->_indexes[$indexName] = $indexCandidate;

        return $this;
    }

    /**
     * @return void
     */
    protected function _addForeignKeyConstraint(ForeignKeyConstraint $constraint)
    {
        $constraint->setLocalTable($this);

        if (strlen($constraint->getName())) {
            $name = $constraint->getName();
        } else {
            $name = $this->_generateIdentifierName(
                array_merge((array) $this->getName(), $constraint->getLocalColumns()),
                'fk',
                $this->_getMaxIdentifierLength()
            );
        }
        $name = $this->normalizeIdentifier($name);

        $this->_fkConstraints[$name] = $constraint;

        // add an explicit index on the foreign key columns. If there is already an index that fulfils this requirements drop the request.
        // In the case of __construct calling this method during hydration from schema-details all the explicitly added indexes
        // lead to duplicates. This creates computation overhead in this case, however no duplicate indexes are ever added (based on columns).
        $indexName      = $this->_generateIdentifierName(
            array_merge([$this->getName()], $constraint->getColumns()),
            'idx',
            $this->_getMaxIdentifierLength()
        );
        $indexCandidate = $this->_createIndex($constraint->getColumns(), $indexName, false, false);

        foreach ($this->_indexes as $existingIndex) {
            if ($indexCandidate->isFullfilledBy($existingIndex)) {
                return;
            }
        }

        $this->_addIndex($indexCandidate);
        $this->implicitIndexes[$this->normalizeIdentifier($indexName)] = $indexCandidate;
    }

    /**
     * Returns whether this table has a foreign key constraint with the given name.
     *
     * @param string $constraintName
     *
     * @return bool
     */
    public function hasForeignKey($constraintName)
    {
        $constraintName = $this->normalizeIdentifier($constraintName);

        return isset($this->_fkConstraints[$constraintName]);
    }

    /**
     * Returns the foreign key constraint with the given name.
     *
     * @param string $constraintName The constraint name.
     *
     * @return ForeignKeyConstraint
     *
     * @throws SchemaException If the foreign key does not exist.
     */
    public function getForeignKey($constraintName)
    {
        $constraintName = $this->normalizeIdentifier($constraintName);
        if (! $this->hasForeignKey($constraintName)) {
            throw SchemaException::foreignKeyDoesNotExist($constraintName, $this->_name);
        }

        return $this->_fkConstraints[$constraintName];
    }

    /**
     * Removes the foreign key constraint with the given name.
     *
     * @param string $constraintName The constraint name.
     *
     * @return void
     *
     * @throws SchemaException
     */
    public function removeForeignKey($constraintName)
    {
        $constraintName = $this->normalizeIdentifier($constraintName);
        if (! $this->hasForeignKey($constraintName)) {
            throw SchemaException::foreignKeyDoesNotExist($constraintName, $this->_name);
        }

        unset($this->_fkConstraints[$constraintName]);
    }

    /**
     * Returns ordered list of columns (primary keys are first, then foreign keys, then the rest)
     *
     * @return Column[]
     */
    public function getColumns()
    {
        $primaryKeyColumns = [];
        if ($this->hasPrimaryKey()) {
            $primaryKeyColumns = $this->filterColumns($this->getPrimaryKey()->getColumns());
        }

        return array_merge($primaryKeyColumns, $this->getForeignKeyColumns(), $this->_columns);
    }

    /**
     * Returns foreign key columns
     *
     * @return Column[]
     */
    private function getForeignKeyColumns()
    {
        $foreignKeyColumns = [];
        foreach ($this->getForeignKeys() as $foreignKey) {
            $foreignKeyColumns = array_merge($foreignKeyColumns, $foreignKey->getColumns());
        }
        return $this->filterColumns($foreignKeyColumns);
    }

    /**
     * Returns only columns that have specified names
     *
     * @param string[] $columnNames
     *
     * @return Column[]
     */
    private function filterColumns(array $columnNames)
    {
        return array_filter($this->_columns, static function ($columnName) use ($columnNames) {
            return in_array($columnName, $columnNames, true);
        }, ARRAY_FILTER_USE_KEY);
    }

    /**
     * Returns whether this table has a Column with the given name.
     *
     * @param string $columnName The column name.
     *
     * @return bool
     */
    public function hasColumn($columnName)
    {
        $columnName = $this->normalizeIdentifier($columnName);

        return isset($this->_columns[$columnName]);
    }

    /**
     * Returns the Column with the given name.
     *
     * @param string $columnName The column name.
     *
     * @return Column
     *
     * @throws SchemaException If the column does not exist.
     */
    public function getColumn($columnName)
    {
        $columnName = $this->normalizeIdentifier($columnName);
        if (! $this->hasColumn($columnName)) {
            throw SchemaException::columnDoesNotExist($columnName, $this->_name);
        }

        return $this->_columns[$columnName];
    }

    /**
     * Returns the primary key.
     *
     * @return Index|null The primary key, or null if this Table has no primary key.
     */
    public function getPrimaryKey()
    {
        if (! $this->hasPrimaryKey()) {
            return null;
        }

        return $this->getIndex($this->_primaryKeyName);
    }

    /**
     * Returns the primary key columns.
     *
     * @return string[]
     *
     * @throws DBALException
     */
    public function getPrimaryKeyColumns()
    {
        if (! $this->hasPrimaryKey()) {
            throw new DBALException('Table ' . $this->getName() . ' has no primary key.');
        }
        return $this->getPrimaryKey()->getColumns();
    }

    /**
     * Returns whether this table has a primary key.
     *
     * @return bool
     */
    public function hasPrimaryKey()
    {
        return $this->_primaryKeyName && $this->hasIndex($this->_primaryKeyName);
    }

    /**
     * Returns whether this table has an Index with the given name.
     *
     * @param string $indexName The index name.
     *
     * @return bool
     */
    public function hasIndex($indexName)
    {
        $indexName = $this->normalizeIdentifier($indexName);

        return isset($this->_indexes[$indexName]);
    }

    /**
     * Returns the Index with the given name.
     *
     * @param string $indexName The index name.
     *
     * @return Index
     *
     * @throws SchemaException If the index does not exist.
     */
    public function getIndex($indexName)
    {
        $indexName = $this->normalizeIdentifier($indexName);
        if (! $this->hasIndex($indexName)) {
            throw SchemaException::indexDoesNotExist($indexName, $this->_name);
        }

        return $this->_indexes[$indexName];
    }

    /**
     * @return Index[]
     */
    public function getIndexes()
    {
        return $this->_indexes;
    }

    /**
     * Returns the foreign key constraints.
     *
     * @return ForeignKeyConstraint[]
     */
    public function getForeignKeys()
    {
        return $this->_fkConstraints;
    }

    /**
     * @param string $name
     *
     * @return bool
     */
    public function hasOption($name)
    {
        return isset($this->_options[$name]);
    }

    /**
     * @param string $name
     *
     * @return mixed
     */
    public function getOption($name)
    {
        return $this->_options[$name];
    }

    /**
     * @return mixed[]
     */
    public function getOptions()
    {
        return $this->_options;
    }

    /**
     * @return void
     */
    public function visit(Visitor $visitor)
    {
        $visitor->acceptTable($this);

        foreach ($this->getColumns() as $column) {
            $visitor->acceptColumn($this, $column);
        }

        foreach ($this->getIndexes() as $index) {
            $visitor->acceptIndex($this, $index);
        }

        foreach ($this->getForeignKeys() as $constraint) {
            $visitor->acceptForeignKey($this, $constraint);
        }
    }

    /**
     * Clone of a Table triggers a deep clone of all affected assets.
     *
     * @return void
     */
    public function __clone()
    {
        foreach ($this->_columns as $k => $column) {
            $this->_columns[$k] = clone $column;
        }
        foreach ($this->_indexes as $k => $index) {
            $this->_indexes[$k] = clone $index;
        }
        foreach ($this->_fkConstraints as $k => $fk) {
            $this->_fkConstraints[$k] = clone $fk;
            $this->_fkConstraints[$k]->setLocalTable($this);
        }
    }

    /**
     * Normalizes a given identifier.
     *
     * Trims quotes and lowercases the given identifier.
     *
     * @param string $identifier The identifier to normalize.
     *
     * @return string The normalized identifier.
     */
    private function normalizeIdentifier($identifier)
    {
        return $this->trimQuotes(strtolower($identifier));
    }
}
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                  <?php

namespace Doctrine\DBAL\Schema;

use Doctrine\DBAL\Platforms\AbstractPlatform;

/**
 * Table Diff.
 */
class TableDiff
{
    /** @var string */
    public $name = null;

    /** @var string|bool */
    public $newName = false;

    /**
     * All added fields.
     *
     * @var Column[]
     */
    public $addedColumns;

    /**
     * All changed fields.
     *
     * @var ColumnDiff[]
     */
    public $changedColumns = [];

    /**
     * All removed fields.
     *
     * @var Column[]
     */
    public $removedColumns = [];

    /**
     * Columns that are only renamed from key to column instance name.
     *
     * @var Column[]
     */
    public $renamedColumns = [];

    /**
     * All added indexes.
     *
     * @var Index[]
     */
    public $addedIndexes = [];

    /**
     * All changed indexes.
     *
     * @var Index[]
     */
    public $changedIndexes = [];

    /**
     * All removed indexes
     *
     * @var Index[]
     */
    public $removedIndexes = [];

    /**
     * Indexes that are only renamed but are identical otherwise.
     *
     * @var Index[]
     */
    public $renamedIndexes = [];

    /**
     * All added foreign key definitions
     *
     * @var ForeignKeyConstraint[]
     */
    public $addedForeignKeys = [];

    /**
     * All changed foreign keys
     *
     * @var ForeignKeyConstraint[]
     */
    public $changedForeignKeys = [];

    /**
     * All removed foreign keys
     *
     * @var ForeignKeyConstraint[]|string[]
     */
    public $removedForeignKeys = [];

    /** @var Table */
    public $fromTable;

    /**
     * Constructs an TableDiff object.
     *
     * @param string       $tableName
     * @param Column[]     $addedColumns
     * @param ColumnDiff[] $changedColumns
     * @param Column[]     $removedColumns
     * @param Index[]      $addedIndexes
     * @param Index[]      $changedIndexes
     * @param Index[]      $removedIndexes
     */
    public function __construct(
        $tableName,
        $addedColumns = [],
        $changedColumns = [],
        $removedColumns = [],
        $addedIndexes = [],
        $changedIndexes = [],
        $removedIndexes = [],
        ?Table $fromTable = null
    ) {
        $this->name           = $tableName;
        $this->addedColumns   = $addedColumns;
        $this->changedColumns = $changedColumns;
        $this->removedColumns = $removedColumns;
        $this->addedIndexes   = $addedIndexes;
        $this->changedIndexes = $changedIndexes;
        $this->removedIndexes = $removedIndexes;
        $this->fromTable      = $fromTable;
    }

    /**
     * @param AbstractPlatform $platform The platform to use for retrieving this table diff's name.
     *
     * @return Identifier
     */
    public function getName(AbstractPlatform $platform)
    {
        return new Identifier(
            $this->fromTable instanceof Table ? $this->fromTable->getQuotedName($platform) : $this->name
        );
    }

    /**
     * @return Identifier|string|bool
     */
    public function getNewName()
    {
        return $this->newName ? new Identifier($this->newName) : $this->newName;
    }
}
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                   <?php

namespace Doctrine\DBAL\Schema\Synchronizer;

use Doctrine\DBAL\Connection;
use Throwable;

/**
 * Abstract schema synchronizer with methods for executing batches of SQL.
 */
abstract class AbstractSchemaSynchronizer implements SchemaSynchronizer
{
    /** @var Connection */
    protected $conn;

    public function __construct(Connection $conn)
    {
        $this->conn = $conn;
    }

    /**
     * @param string[] $sql
     */
    protected function processSqlSafely(array $sql)
    {
        foreach ($sql as $s) {
            try {
                $this->conn->exec($s);
            } catch (Throwable $e) {
            }
        }
    }

    /**
     * @param string[] $sql
     */
    protected function processSql(array $sql)
    {
        foreach ($sql as $s) {
            $this->conn->exec($s);
        }
    }
}
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                             <?php

namespace Doctrine\DBAL\Schema\Synchronizer;

use Doctrine\DBAL\Schema\Schema;

/**
 * The synchronizer knows how to synchronize a schema with the configured
 * database.
 */
interface SchemaSynchronizer
{
    /**
     * Gets the SQL statements that can be executed to create the schema.
     *
     * @return string[]
     */
    public function getCreateSchema(Schema $createSchema);

    /**
     * Gets the SQL Statements to update given schema with the underlying db.
     *
     * @param bool $noDrops
     *
     * @return string[]
     */
    public function getUpdateSchema(Schema $toSchema, $noDrops = false);

    /**
     * Gets the SQL Statements to drop the given schema from underlying db.
     *
     * @return string[]
     */
    public function getDropSchema(Schema $dropSchema);

    /**
     * Gets the SQL statements to drop all schema assets from underlying db.
     *
     * @return string[]
     */
    public function getDropAllSchema();

    /**
     * Creates the Schema.
     *
     * @return void
     */
    public function createSchema(Schema $createSchema);

    /**
     * Updates the Schema to new schema version.
     *
     * @param bool $noDrops
     *
     * @return void
     */
    public function updateSchema(Schema $toSchema, $noDrops = false);

    /**
     * Drops the given database schema from the underlying db.
     *
     * @return void
     */
    public function dropSchema(Schema $dropSchema);

    /**
     * Drops all assets from the underlying db.
     *
     * @return void
     */
    public function dropAllSchema();
}
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                              <?php

namespace Doctrine\DBAL\Schema\Synchronizer;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Schema\Comparator;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Schema\Visitor\DropSchemaSqlCollector;
use function count;

/**
 * Schema Synchronizer for Default DBAL Connection.
 */
class SingleDatabaseSynchronizer extends AbstractSchemaSynchronizer
{
    /** @var AbstractPlatform */
    private $platform;

    public function __construct(Connection $conn)
    {
        parent::__construct($conn);
        $this->platform = $conn->getDatabasePlatform();
    }

    /**
     * {@inheritdoc}
     */
    public function getCreateSchema(Schema $createSchema)
    {
        return $createSchema->toSql($this->platform);
    }


    /**
     * {@inheritdoc}
     */
    public function getUpdateSchema(Schema $toSchema, $noDrops = false)
    {
        $comparator = new Comparator();
        $sm         = $this->conn->getSchemaManager();

        $fromSchema = $sm->createSchema();
        $schemaDiff = $comparator->compare($fromSchema, $toSchema);

        if ($noDrops) {
            return $schemaDiff->toSaveSql($this->platform);
        }

        return $schemaDiff->toSql($this->platform);
    }

    /**
     * {@inheritdoc}
     */
    public function getDropSchema(Schema $dropSchema)
    {
        $visitor = new DropSchemaSqlCollector($this->platform);
        $sm      = $this->conn->getSchemaManager();

        $fullSchema = $sm->createSchema();

        foreach ($fullSchema->getTables() as $table) {
            if ($dropSchema->hasTable($table->getName())) {
                $visitor->acceptTable($table);
            }

            foreach ($table->getForeignKeys() as $foreignKey) {
                if (! $dropSchema->hasTable($table->getName())) {
                    continue;
                }

                if (! $dropSchema->hasTable($foreignKey->getForeignTableName())) {
                    continue;
                }

                $visitor->acceptForeignKey($table, $foreignKey);
            }
        }

        if (! $this->platform->supportsSequences()) {
            return $visitor->getQueries();
        }

        foreach ($dropSchema->getSequences() as $sequence) {
            $visitor->acceptSequence($sequence);
        }

        foreach ($dropSchema->getTables() as $table) {
            if (! $table->hasPrimaryKey()) {
                continue;
            }

            $columns = $table->getPrimaryKey()->getColumns();
            if (count($columns) > 1) {
                continue;
            }

            $checkSequence = $table->getName() . '_' . $columns[0] . '_seq';
            if (! $fullSchema->hasSequence($checkSequence)) {
                continue;
            }

            $visitor->acceptSequence($fullSchema->getSequence($checkSequence));
        }

        return $visitor->getQueries();
    }

    /**
     * {@inheritdoc}
     */
    public function getDropAllSchema()
    {
        $sm      = $this->conn->getSchemaManager();
        $visitor = new DropSchemaSqlCollector($this->platform);

        $schema = $sm->createSchema();
        $schema->visit($visitor);

        return $visitor->getQueries();
    }

    /**
     * {@inheritdoc}
     */
    public function createSchema(Schema $createSchema)
    {
        $this->processSql($this->getCreateSchema($createSchema));
    }

    /**
     * {@inheritdoc}
     */
    public function updateSchema(Schema $toSchema, $noDrops = false)
    {
        $this->processSql($this->getUpdateSchema($toSchema, $noDrops));
    }

    /**
     * {@inheritdoc}
     */
    public function dropSchema(Schema $dropSchema)
    {
        $this->processSqlSafely($this->getDropSchema($dropSchema));
    }

    /**
     * {@inheritdoc}
     */
    public function dropAllSchema()
    {
        $this->processSql($this->getDropAllSchema());
    }
}
                                                                                                                                                                                     <?php

namespace Doctrine\DBAL\Schema\Visitor;

use Doctrine\DBAL\Schema\Column;
use Doctrine\DBAL\Schema\ForeignKeyConstraint;
use Doctrine\DBAL\Schema\Index;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Schema\Sequence;
use Doctrine\DBAL\Schema\Table;

/**
 * Abstract Visitor with empty methods for easy extension.
 */
class AbstractVisitor implements Visitor, NamespaceVisitor
{
    public function acceptSchema(Schema $schema)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function acceptNamespace($namespaceName)
    {
    }

    public function acceptTable(Table $table)
    {
    }

    public function acceptColumn(Table $table, Column $column)
    {
    }

    public function acceptForeignKey(Table $localTable, ForeignKeyConstraint $fkConstraint)
    {
    }

    public function acceptIndex(Table $table, Index $index)
    {
    }

    