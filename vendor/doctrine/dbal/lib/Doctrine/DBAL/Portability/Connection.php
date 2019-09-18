<?php

namespace Doctrine\DBAL\Schema;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Schema\Visitor\CreateSchemaSqlCollector;
use Doctrine\DBAL\Schema\Visitor\DropSchemaSqlCollector;
use Doctrine\DBAL\Schema\Visitor\NamespaceVisitor;
use Doctrine\DBAL\Schema\Visitor\Visitor;
use function array_keys;
use function strpos;
use function strtolower;

/**
 * Object representation of a database schema.
 *
 * Different vendors have very inconsistent naming with regard to the concept
 * of a "schema". Doctrine understands a schema as the entity that conceptually
 * wraps a set of database objects such as tables, sequences, indexes and
 * foreign keys that belong to each other into a namespace. A Doctrine Schema
 * has nothing to do with the "SCHEMA" defined as in PostgreSQL, it is more
 * related to the concept of "DATABASE" that exists in MySQL and PostgreSQL.
 *
 * Every asset in the doctrine schema has a name. A name consists of either a
 * namespace.local name pair or just a local unqualified name.
 *
 * The abstraction layer that covers a PostgreSQL schema is the namespace of an
 * database object (asset). A schema can have a name, which will be used as
 * default namespace for the unqualified database objects that are created in
 * the schema.
 *
 * In the case of MySQL where cross-database queries are allowed this leads to
 * databases being "misinterpreted" as namespaces. This is intentional, however
 * the CREATE/DROP SQL visitors will just filter this queries and do not
 * execute them. Only the queries for the currently connected database are
 * executed.
 */
class Schema extends AbstractAsset
{
    /**
     * The namespaces in this schema.
     *
     * @var string[]
     */
    private $namespaces = [];

    /** @var Table[] */
    protected $_tables = [];

    /** @var Sequence[] */
    protected $_sequences = [];

    /** @var SchemaConfig */
    protected $_schemaConfig = false;

    /**
     * @param Table[]    $tables
     * @param Sequence[] $sequences
     * @param string[]   $namespaces
     */
    public function __construct(
        array $tables = [],
        array $sequences = [],
        ?SchemaConfig $schemaConfig = null,
        array $namespaces = []
    ) {
        if ($schemaConfig === null) {
            $schemaConfig = new SchemaConfig();
        }
        $this->_schemaConfig = $schemaConfig;
        $this->_setName($schemaConfig->getName() ?: 'public');

        foreach ($namespaces as $namespace) {
            $this->createNamespace($namespace);
        }

        foreach ($tables as $table) {
            $this->_addTable($table);
        }

        foreach ($sequences as $sequence) {
            $this->_addSequence($sequence);
        }
    }

    /**
     * @return bool
     */
    public function hasExplicitForeignKeyIndexes()
    {
        return $this->_schemaConfig->hasExplicitForeignKeyIndexes();
    }

    /**
     * @return void
     *
     * @throws SchemaException
     */
    protected function _addTable(Table $table)
    {
        $namespaceName = $table->getNamespaceName();
        $tableName     = $table->getFullQualifiedName($this->getName());

        if (isset($this->_tables[$tableName])) {
            throw SchemaException::tableAlreadyExists($tableName);
        }

        if (! $table->isInDefaultNamespace($this->getName()) && ! $this->hasNamespace($namespaceName)) {
            $this->createNamespace($namespaceName);
        }

        $this->_tables[$tableName] = $table;
        $table->setSchemaConfig($this->_schemaConfig);
    }

    /**
     * @return void
     *
     * @throws SchemaException
     */
    protected function _addSequence(Sequence $sequence)
    {
        $namespaceName = $sequence->getNamespaceName();
        $seqName       = $sequence->getFullQualifiedName($this->getName());

        if (isset($this->_sequences[$seqName])) {
            throw SchemaException::sequenceAlreadyExists($seqName);
        }

        if (! $sequence->isInDefaultNamespace($this->getName()) && ! $this->hasNamespace($namespaceName)) {
            $this->createNamespace($namespaceName);
        }

        $this->_sequences[$seqName] = $