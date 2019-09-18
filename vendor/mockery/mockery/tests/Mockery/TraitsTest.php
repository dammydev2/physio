# Handlers, Formatters and Processors

- [Handlers](#handlers)
  - [Log to files and syslog](#log-to-files-and-syslog)
  - [Send alerts and emails](#send-alerts-and-emails)
  - [Log specific servers and networked logging](#log-specific-servers-and-networked-logging)
  - [Logging in development](#logging-in-development)
  - [Log to databases](#log-to-databases)
  - [Wrappers / Special Handlers](#wrappers--special-handlers)
- [Formatters](#formatters)
- [Processors](#processors)
- [Third Party Packages](#third-party-packages)

## Handlers

### Log to files and syslog

- _StreamHandler_: Logs records into any PHP stream, use this for log files.
- _RotatingFileHandler_: Logs records to a file and creates one logfile per day.
  It will also delete files older than `$maxFiles`. You should use
  [logrotate](http://linuxcommand.org/man_pages/logrotate8.html) for high profile
  setups though, this is just meant as a quick and dirty solution.
- _SyslogHandler_: Logs records to the syslog.
- _ErrorLogHandler_: Logs records to PHP's
  [`error_log()`](http://docs.php.net/manual/en/function.error-log.php) function.

### Send alerts and emails

- _NativeMailerHandler_: Sends emails using PHP's
  [`mail()`](http://php.net/manual/en/function.mail.php) function.
- _SwiftMailerHandler_: Sends emails using a [`Swift_Mailer`](http://swiftmailer.org/) instance.
- _PushoverHandler_: Sends mobile notifications via the [Pushover](https://www.pushover.net/) API.
- _HipChatHandler_: Logs records to a [HipChat](http://hipchat.com) chat room using its API.
- _FlowdockHandler_: Logs records to a [Flowdock](https://www.flowdock.com/) account.
- _SlackHandler_: Logs records to a [Slack](https://www.slack.com/) account using the Slack API.
- _SlackbotHandler_: Logs re