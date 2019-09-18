the activation policy
  * Added StreamHandler::getUrl to retrieve the stream's URL
  * Added ability to override addRow/addTitle in HtmlFormatter
  * Added the $context to context information when the ErrorHandler handles a regular php error
  * Deprecated RotatingFileHandler::setFilenameFormat to only support 3 formats: Y, Y-m and Y-m-d
  * Fixed WhatFailureGroupHandler to work with PHP7 throwables
  * Fixed a few minor bugs

### 1.19.0 (2016-04-12)

  * Break: StreamHandler will not close streams automatically that it does not own. If you pass in a stream (not a path/url), then it will not close it for you. You can retrieve those using getStream() if needed
  * Added DeduplicationHandler to remove duplicate records from notifications across multiple requests, useful for email or other notifications on errors
  * Added ability to use `%message%` and other LineFormatter replacements in the subject line of emails sent with NativeMailHandler and SwiftMailerHandler
  * Fixed HipChatHandler handling of long messages

### 1.18.2 (2016-04-02)

  * Fixed ElasticaFormatter to use more precise dates
  * Fixed GelfMessageFormatter sending too long messages

### 1.18.1 (2016-03-13)

  * Fixed SlackHandler bug where slack dropped messages randomly
  * Fixed RedisHandler issue when using with the PHPRedis extension
  * Fixed AmqpHandler content-type being incorrectly set when using with the AMQP extension
  * Fixed BrowserConsoleHandler regression

### 1.18.0 (2016-03-01)

  * Added optional reduction of timestamp precision via `Logger->useMicrosecondTimestamps(false)`, disabling it gets you a bit of perfo