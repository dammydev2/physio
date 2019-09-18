store/detail/php-console/nfhmhhlpfleoednkpnnnkolmclajemef), providing
  inline `console` and notification popup messages within Chrome.

### Log to databases

- _RedisHandler_: Logs records to a [redis](http://redis.io) server.
- _MongoDBHandler_: Handler to write records in MongoDB via a
  [Mongo](http://pecl.php.net/package/mongo) extension connection.
- _CouchDBHandler_: Logs records to a CouchDB server.
- _DoctrineCouchDBHandler_: Logs records to a CouchDB server via the Doctrine CouchDB ODM.
- _ElasticSearchHandler_: Logs records to an Elastic Search server.
- _DynamoDbHandler_: Logs records to a DynamoDB table with the [AWS SDK](https://github.com/aws/aws-sdk-php).

### Wrappers / Special Handlers

- _FingersCrossedHandler_: A very interesting wrapper. It takes a logger as
  parameter and will accumulate log records of all levels until a record
  exceeds the defined severity level. At which point it delivers all records,
  including those of lower severity, to the handler it wraps. This means that
  until an error actually happens you will not see anything in your logs, but
  when it happens you will have the full information, including debug and info
  records. This provides you with all the information you need, but only when
  you need it.
- _DeduplicationHandler_: Useful if you are sending notifications or emails
  when critical errors occur. It takes a logger as parameter and will
  accumulate log records of all levels until the end of the request (or
  `flush()` is called). At that point it delivers all records to the handler
  it wraps, but only if the records are unique over a given time period
  (60seconds by default). If the records are duplicates they are simply
  discarded. The main use of this is in case of critical failure like if your
  database is unreachable for example all your requests will fail and that
  can result in a lot of notifications being sent. Adding this handler reduces
  the amount of notifications to a manageable level.
- _WhatFailureGroupHandler_: This handler extends the _GroupHandler_ ignoring
   exceptions raised by each child handler. This allows you to ignore issues
   where a remote tcp connection may have died but you do not want your entire
   application to crash and may wish to continue to log to other handlers.
- _BufferHandler_: This handler will buffer all the log records it receives
  until `close()` is called at which point it will call `handleBatch()` on the
  handler it wraps with all the log messages at once. This is very useful to
  send an email with all records at once for example instead of having one mail
  for every log record.
- _GroupHandler_: This handler groups other handlers. Every record received is
  sent to all the handlers it is configured with.
- _FilterHandler_: This handler only lets records of the given levels through
   to the wrapped handler.
- _SamplingHandler_: Wraps around another handler and lets you sample records
   if you only want to store some of them.
- _NullHandler_: Any record it can handle will be thrown away. This can be used
  to put on top of an existing handler stack to 