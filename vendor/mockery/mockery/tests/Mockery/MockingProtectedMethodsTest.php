ustomize levels by channel
  * Added stack traces output when normalizing exceptions (json output & co)
  * Added Monolog\Logger::API constant (currently 1)
  * Added support for ChromePHP's v4.0 extension
  * Added support for message priorities in PushoverHandler, see $highPriorityLevel and $emergencyLevel
  * Added support for sending messages to multiple users at once with the PushoverHandler
  * Fixed RavenHandler's support for batch sending of messages (when behind a Buffer or FingersCrossedHandler)
  * Fixed normalization of Traversables with very large data sets, only the first 1000 items are shown now
  * Fixed issue in RotatingFileHandler when an open_basedir restriction is active
  * Fixed minor issues in RavenHandler and bumped the API to Raven 0.5.0
  * Fixed SyslogHandler issue when many were used concurrently with different facilities

### 1.5.0 (2013-04-23)

  * Added ProcessIdProcessor to inject the PID in log records
  * Added UidProcessor to inject a unique identifier to all log records of one request/run
  * Added support for previous exceptions in the LineFormatter exception serialization
  * Added Monolog\Logger::getLevels() to get all available levels
  * Fixed ChromePHPHandler so it avoids sending headers larger than Chrome can handle

### 1.4.1 (2013-04-01)

  * Fixed exception formatting in the LineFormatter to be more minimalistic
  * Fixed RavenHandler's handling of context/extra data, requires Raven client >0.1.0
  * Fixed log rotation in RotatingFileHandler to work with long running scripts spanning multiple days
  * Fixed WebProcessor array access so it checks for data presence
  * Fixed Buffer, Group and FingersCrossed handlers to make use of their processors

### 1.4.0 (2013-02-13)

  * Added RedisHandler to log to Redis via the Predis library or the phpredis extension
  * Added ZendMonitorHandler to log to the Zend Server monitor
  * Added the possibility to pass arrays of handlers and processors directly in the Logger constructor
  * Added `$useSSL` option to the PushoverHandler which is enabled by default
  * Fixed ChromePHPHandler and FirePHPHandler issue when multiple instances are used simultaneously
  * Fixed header injection capability in the NativeMailHandler

### 1.3.1 (2013-01-11)

  * Fixed LogstashFormatter to be usable with stream handlers
  * Fixed GelfMessageFormatter levels on Windows

### 1.3.0 (2013-01-08)

  * Added PSR-3 compliance, the `Monolog\Logger` class is now an instance of `Psr\Log\LoggerInterface`
  * Added PsrLogMessageProcessor that you can selectively enable for full PSR-3 compliance
  * Added LogstashFormatter (combine with SocketHandler or StreamHandler to send logs to Logstash)
  * Added PushoverHandler to send mobile notifications
  * Added CouchDBHandler and DoctrineCouchDBHandler
  * Added RavenHandler to send data to Sentry servers
  * Added support for the new MongoClient class in MongoDBHandler
  * Added microsecond precision to log records' timestamps
  * Added `$flushOnOverflow` param to BufferHandler to flush by batches instead of losing
    the oldest entries
  * Fixed normalization of objects with cyclic references

### 1.2.1 (2012-08-29)

  * Added new $logopts arg to SyslogHandler to provide custom openlog options
  * Fixed fatal error in SyslogHandler

### 1.2.0 (2012-08-18)

  * Added AmqpHandler (for use with AMQP servers)
  * Added CubeHandler
  * Added NativeMailerHandler::addHeader() to send custom headers in mails
  * Added the possibility to specify more than one recipient in NativeMailerHandler
  * Added the possibility to specify float timeouts in SocketHandler
  * Added NOTICE and EMERGENCY levels to conform with RFC 5424
  * Fixed the log records to use the php default timezone instead o