1.15.0 (2015-07-12)

  * Added addTags and setTags methods to change a TagProcessor
  * Added automatic creation of directories if they are missing for a StreamHandler to open a log file
  * Added retry functionality to Loggly, Cube and Mandrill handlers so they retry up to 5 times in case of network failure
  * Fixed process exit code being incorrectly reset to 0 if ErrorHandler::registerExceptionHandler was used
  * Fixed HTML/JS escaping in BrowserConsoleHandler
  * Fixed JSON encoding errors being silently suppressed (PHP 5.5+ only)

### 1.14.0 (2015-06-19)

  * Added PHPConsoleHandler to send record to Chrome's PHP Console extension and library
  * Added support for objects implementing __toString in the NormalizerFormatter
  * Added support for HipChat's v2 API in HipChatHandler
  * Added Logger::setTimezone() to initialize the timezone monolog should use in case date.timezone isn't correct for your app
  * Added an option to send formatted message instead of the raw record on PushoverHandler via ->useFormattedMessage(true)
  * Fixed curl errors being silently suppressed

### 1.13.1 (2015-03-09)

  * Fixed regression in HipChat requiring a new token to be created

### 1.13.0 (2015-03-05)

  * Added Registry::hasLogger to check for the presence of a logger instance
  * Added context.user support to RavenHandler
  * Added HipChat API v2 support in the HipChatHandler
  * Added NativeMailerHandler::addParameter to pass params to the mail() process
  * Added context data to SlackHandler when $includeContextAndExtra is true
  * Added ability to customize the Swift_Message per-email in SwiftMailerHandler
  * Fixed SwiftMailerHandler to lazily create message instances if a callback is provided
  * Fixed serialization of INF and NaN values in Normalizer and LineFormatter

### 1.12.0 (2014-12-29)

  * Break: HandlerInterface::isHandling now receives a partial record containing only a level key. This was always the intent and does not break any Monolog handler but is strictly speaking a BC break and you should check if you relied on any other field in your own handlers.
  * Added PsrHandler to forward records to another PSR-3 logger
  * Added SamplingHandler to wrap around a handler and include only every Nth record
  * Added MongoDBFormatter to support better storage with MongoDBHandler (it must be enabled manually for now)
  * Added exception codes in the output of most formatters
  * Added LineFormatter::includeStacktraces to enable exception stack traces in logs (uses more than one line)
  * Added $useShortAttachment to SlackHandler to minify attachment size and $includeExtra to append extra data
  * Added $host to HipChatHandler for users of private instances
  * Added $transactionName to NewRelicHandler and support for a transaction_name context value
  * Fixed MandrillHandler to avoid outputing API call responses
  * Fixed some non-standard behaviors in SyslogUdpHandler

### 1.11.0 (2014-09-30)

  * Break: The NewRelicHandler extra and context data are now prefixed with extra_ and context_ to avoid clashes. Watch out if you have scripts reading those from the API and rely on names
  * Added WhatFailureGroupHandler to suppress any exception coming from the wrapped handlers and avoid chain failures if a logging service fails
  * Added MandrillHandler to send emails via the Mandrillapp.com API
  * Added SlackHandler to log records to a Slack.com account
  * Added FleepHookHandler to log records to a Fleep.io account
  * Added LogglyHandler::addTag to allow adding tags to an existing handler
  * Added $ignoreEmptyContextAndExtra to LineFormatter to avoid empty [] at the end
  * Added $useLocking to StreamHandler and RotatingFileHandler to enable flock() while writing
  * Added support for PhpAmqpLib in the AmqpHandler
  * Added FingersCrossedHandler::clear and BufferHandler::clear to reset them between batches in long running jobs
  * Added support for adding extra fields from $_SERVER in the WebProcessor
  * Fixed support for non-string values in PrsLogMessageProcessor
  * Fixed SwiftMailer messages being sent with the wrong date in long running scripts
  * Fixed minor PHP 5.6 compatibility issues
  * Fixed BufferHandler::close being called twice

### 1.10.0 (2014-06-04)

  * Added Logger::getHandlers() and Logger::getProcessors() methods
  * Added $passthruLevel argument to FingersCrossedHandler to let it always pass some records through even if the trigger level is not reached
  * Added support for extra data in NewRelicHandler
  * Added $expandNewlines flag to the ErrorLogHandler to create multiple log entries when a message has multiple lines

### 1.9.1 (2014-04-24)

  * Fixed regression in RotatingFileHandler file permissions
  * Fixed initialization of the BufferHandler to make sure it gets flushed after receiving records
  * Fixed ChromePHPHandler and FirePHPHandler's activation strategies to be more conservative

### 1.9.0 (2014-04-20)

  * Added LogEntriesHandler to send logs to a LogEntries account
  * Added $filePermissions to tweak file mode on StreamHandler and RotatingFileHandler
  * Added $useFormatting flag to MemoryProcessor to make it send raw data in bytes
  * Added support for table formatting in FirePHPHandler via the table context key
  * Added a TagProcessor to add tags to records, and support for tags in RavenHandler
  * Added $appendNewline flag to the JsonFormatter to enable using it when logging to files
  * Added sound support to the PushoverHandler
  * Fixed multi-threading support in StreamHandler
  * Fixed empty headers issue when ChromePHPHandler received no records
  * Fixed default format of the ErrorLogHandler

### 1.8.0 (2014-03-23)

  * Break: the LineFormatter now strips newlines by default because this was a bug, set $allowInlineLineBreaks to true if you need them
  * Added BrowserConsoleHandler to send logs to any browser's console via console.log() injection in the output
  * 