# Upgrade to 2.9

## Deprecated `Statement::fetchColumn()` with an invalid index

Calls to `Statement::fetchColumn()` with an invalid column index currently return `NULL`. In the future, such calls will result in a exception.

## Deprecated `Configuration::getFilterSchemaAssetsExpression()`, `::setFilterSchemaAssetsExpression()` and `AbstractSchemaManager::getFilterSchemaAssetsExpression()`.

Regular expression-based filters are hard to extend by combining together. Instead, you may use callback-based filers via `::getSchemaAssetsFilter()` and `::getSchemaAssetsFilter()`. Callbacks can use regular expressions internally.

## Deprecated `Doctrine\DBAL\Types\Type::getDefaultLength()`

This method was never used by DBAL internally. It is now deprecated and will be removed in DBAL 3.0.

## Deprecated `Doctrine\DBAL\Types\Type::__toString()`

Relying on string representation is discouraged and will be removed in DBAL 3.0.

## Deprecated `NULL` value of `$offset` in LIMIT queries

The `NULL` value of the `$offset` argument in `AbstractPlatform::(do)?ModifyLimitQuery()` methods is deprecated. If explicitly used in the method call, the absence of the offset should be indicated with a `0`.

## Deprecated dbal:import CLI command

The `dbal:import` CLI command has been deprecated since it only works with PDO-based drivers by relying on a non-documented behavior of the extension, and it's impossible to make it work with other drivers.
Please use other database client applications for import, e.g.:

 * For MySQL and MariaDB: `mysql [dbname] < data.sql`.
 * For PostgreSQL: `psql [dbname] < data.sql`.
 * For SQLite: `sqlite3 /path/to/file.db < data.sql`.

# Upgrade to 2.8

## Deprecated usage of DB-generated UUIDs

The format of DB-generated UUIDs is inconsistent across supported platforms and therefore is not portable. Some of the platforms produce UUIDv1, some produce UUIDv4, some produce the values which are not even UUID.

Unless UUIDs are used in stored procedures which DBAL doesn't support, there's no real benefit of DB-generated UUIDs comparing to the application-generated ones.

Use a PHP library (e.g. [ramsey/uuid](https://packagist.org/packages/ramsey/uuid)) to generate UUIDs on the application side.

## Deprecated usage of binary fields whose length exceeds the platform maximum

- The usage of binary fields whose length exceeds the maximum field size on a given platform is deprecated.
  Use binary fields of a size which fits all target platforms, or use blob explicitly instead.

## Removed dependency on doctrine/common

The dependency on doctrine/common package has been removed.
DBAL now depends on doctrine/cache and doctrine/event-manager instead.
If you are using any other component from doctrine/common package,
you will have to add an explicit dependency to your composer.json.

## Corrected exception thrown by ``Doctrine\DBAL\Platforms\SQLAnywhere16Platform::getAdvancedIndexOptionsSQL()``

This method now throws SPL ``UnexpectedValueException`` instead of accidentally throwing ``Doctrine\Common\Proxy\Exception\UnexpectedValueException``.

# Upgrade to 2.7

## Doctrine\DBAL\Platforms\AbstractPlatform::DATE_INTERVAL_UNIT_* constants deprecated

``Doctrine\DBAL\Platforms\AbstractPlatform::DATE_INTERVAL_UNIT_*`` constants were moved into ``Doctrine\DBAL\Platforms\DateIntervalUnit`` class without the ``DATE_INTERVAL_UNIT_`` prefix.

## Doctrine\DBAL\Platforms\AbstractPlatform::TRIM_* constants deprecated

``Doctrine\DBAL\Platforms\AbstractPlatform::TRIM_*`` constants were moved into ``Doctrine\DBAL\Platforms\TrimMode`` class without the ``TRIM_`` prefix.

## Doctrine\DBAL\Connection::TRANSACTION_* constants deprecated

``Doctrine\DBAL\Connection::TRANSACTION_*`` were moved into ``Doctrine\DBAL\TransactionIsolationLevel`` class without the ``TRANSACTION_`` prefix. 

## DEPRECATION: direct usage of the PDO APIs in the DBAL API

1. When calling `Doctrine\DBAL\Driver\Statement` methods, instead of `PDO::PARAM_*` constants, `Doctrine\DBAL\ParameterType` constants should be used.
2. When calling `Doctrine\DBAL\Driver\ResultStatement` methods, instead of `PDO::FETCH_*` constants, `Doctrine\DBAL\FetchMode` constants should be used.
3. When configuring `Doctrine\DBAL\Portability\Connection`, instead of `PDO::CASE_*` constants, `Doctrine\DBAL\ColumnCase` constants should be used.
4. Usage of `PDO::PARAM_INPUT_OUTPUT` in `Doctrine\DBAL\Driver\Statement::bindValue()` is deprecated.
5. Usage of `PDO::FETCH_FUNC` in `Doctrine\DBAL\Driver\ResultStatement::fetch()` is deprecated.
6. Calls to `\PDOStatement` methods on a `\Doctrine\DBAL\Driver\PDOStatement` instance (e.g. `fetchObject()`) are deprecated.

# Upgrade to 2.6

## MINOR BC BREAK: `fetch()` and `fetchAll()` method signatures in `Doctrine\DBAL\Driver\ResultStatement`

1. ``Doctrine\DBAL\Driver\ResultStatement::fetch()`` now has 3 arguments instead of 1, respecting
``PDO::fetch()`` signature.

Before:

    Doctrine\DBAL\Driver\ResultStatement::fetch($fetchMode);

After:

    Doctrine\DBAL\Driver\ResultStatement::fetch($fetchMode, $cursorOrientation, $cursorOffset);

2. ``Doctrine\DBAL\Driver\ResultStatement::fetchAll()`` now has 3 arguments instead of 1, respecting
``PDO::fetchAll()`` signature.

Before:

    Doctrine\DBAL\Driver\ResultStatement::fetchAll($fetchMode);

After:

    Doctrine\DBAL\Driver\ResultStatement::fetch($fetchMode, $fetchArgument, $ctorArgs);


## MINOR BC BREAK: URL-style DSN with percentage sign in password

URL-style DSNs (e.g. ``mysql://foo@bar:localhost/db``) are now assumed to be percent-encoded
in order to allow certain special characters in usernames, paswords and database names. If
you are using a URL-style DSN and have a username, password or database name containing a
percentage sign, you need to update your DSN. If your password is, say, ``foo%foo``, it
should be encoded as ``foo%25foo``.

# Upgrade to 2.5.1

## MINOR BC BREAK: Doctrine\DBAL\Schema\Table

When adding indexes to ``Doctrine\DBAL\Schema\Table`` via ``addIndex()`` or ``addUniqueIndex()``,
duplicate indexes are not silently ignored/dropped anymore (based on semantics, not naming!).
Duplicate indexes are considered indexes that pass ``isFullfilledBy()`` or ``overrules()``
in ``Doctrine\DBAL\Schema\Index``.
This is required to make the index renaming feature introduced in 2.5.0 work properly and avoid
issues in the ORM schema tool / DBAL schema manager which pretends users from updating
their schemas and migrate to DBAL 2.5.*.
Additionally it offers more flexibility in declaring indexes for the user and potentially fixes
related issues in the ORM.
With this change, the responsibility to decide which index is a "duplicate" is completely deferred
to the user.
Please also note that adding foreign key constraints to a table via ``addForeignKeyConstraint()``,
``addUnnamedForeignKeyConstraint()`` or ``addNamedForeignKeyConstraint()`` now first checks if an
appropriate index is already present and avoids adding an additional auto-generated one eventually.

# Upgrade to 2.5

## BC BREAK: time type resets date fields to UNIX epoch

When mapping `time` type field to PHP's `DateTime` instance all unused date fields are
reset to UNIX epoch (i.e. 1970-01-01). This might break any logic which relies on comparing
`DateTime` instances with date fields set to the current date.

Use `!` format prefix (see http://php.net/manual/en/datetime.createfromformat.php) for parsing
time strings to prevent having different date fields when comparing user input and `DateTime`
instances as mapped by Doctrine.

## BC BREAK: Doctrine\DBAL\Schema\Table

The methods ``addIndex()`` and ``addUniqueIndex()`` in ``Doctrine\DBAL\Schema\Table``
have an additional, optional parameter. If you override these methods, you should
add this new parameter to the declaration of your overridden methods.

## BC BREAK: Doctrine\DBAL\Connection

The visibility of the property ``$_platform`` in ``Doctrine\DBAL\Connection``
was changed from protected to private. If you have subclassed ``Doctrine\DBAL\Connection``
in your application and accessed ``$_platform`` directly, you have to change the code
portions to use ``getDatabasePlatform()`` instead to retrieve the underlying database
platform.
The reason for this change is the new automatic platform version detection feature,
which lazily evaluates the appropriate platform class to use for the underlying database
server version at runtime.
Please also note, that calling ``getDatabasePlatform()`` now needs to establish a connection
in order to evaluate the appropriate platform class if ``Doctrine\DBAL\Connection`` is not
already connected. Under the following circumstances, it is not possible anymore to retrieve
the platform instance from the connection object without having to do a real connect:

1. ``Doctrine\DBAL\Connection`` was instantiated without the ``platform`` connection parameter.
2. ``Doctrine\DBA