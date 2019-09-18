### 1.24.0 (2018-11-05)

  * Added a `ResettableInterface` in order to reset/reset/clear/flush handlers and processors
  * Added a `ProcessorInterface` as an optional way to label a class as being a processor (mostly useful for autowiring dependency containers)
  * Added a way to log signals being received using Monolog\SignalHandler
  * Added ability to customize error handling at the Logger level using Logger::setExceptionHandler
  * Added InsightOpsHandler to migrate users of the LogEntriesHandler
  * Added protection to NormalizerHandler against circular and very deep structures, it now stops normalizing at a depth of 9
  * Added capture of stack traces to ErrorHandler when logging PHP errors
  * Added RavenHandler support for a `contexts` context or extra key to forward that to Sentry's contexts
  * Added forwarding of context info to FluentdFormatter
  * Added SocketHandler::setChunkSize to override the default chunk size in case you must send large log lines to rsyslog for example
  * Added ability to extend/override BrowserConsoleHandler
  * Added SlackWebhookHandler::getWebhookUrl and SlackHandler::getToken to enable class extensibility
  * Added SwiftMailerHandler::getSubjectFormat