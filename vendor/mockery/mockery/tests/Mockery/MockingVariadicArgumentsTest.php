{
    "name": "monolog/monolog",
    "description": "Sends your logs to files, sockets, inboxes, databases and various web services",
    "keywords": ["log", "logging", "psr-3"],
    "homepage": "http://github.com/Seldaek/monolog",
    "type": "library",
    "license": "MIT",
    "authors": [
        {
            "name": "Jordi Boggiano",
            "email": "j.boggiano@seld.be",
            "homepage": "http://seld.be"
        }
    ],
    "require": {
        "php": ">=5.3.0",
        "psr/log": "~1.0"
    },
    "require-dev": {
        "phpunit/phpunit": "~4.5",
        "graylog2/gelf-php": "~1.0",
        "sentry/sentry": "^0.13",
        "ruflin/elastica": ">=0.90 <3.0",
        "doctrine/couchdb": "~1.0@dev",
        "aws/aws-sdk-php": "^2.4.9 || ^3.0",
        "php-amqplib/php-amqplib": "~2.4",
        "swiftmailer/swiftmailer": "^5.3|^6.0",
        "php-console/php-console": "^3.1.3",
        "phpunit/phpunit-mock-objects": "2.3.0",
        "jakub-onderka/php-parallel-lint": "0.9"
    },
    "_": "phpunit/phpunit-mock-objects required in 2.3.0 due to https://github.com/sebastianbergmann/phpunit-mock-objects/issues/223 - needs hhvm 3.8+ on travis",
    "suggest": {
        "graylog2/g