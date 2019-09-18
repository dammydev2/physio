         "padraic/phar-updater": "^1.0",
                "php": "^7.1",
                "roave/better-reflection": "^2.0",
                "symfony/console": "^3.2 || ^4.0",
                "symfony/filesystem": "^3.2 || ^4.0",
                "symfony/finder": "^3.2 || ^4.0"
            },
            "require-dev": {
                "bamarni/composer-bin-plugin": "^1.1",
                "phpunit/phpunit": "^6.1"
            },
            "bin": [
                "bin/php-scoper"
            ],
            "type": "library",
            "extra": {
                "bamarni-bin": {
                    "bin-links": false
                },
                "branch-alias": {
                    "dev-master": "1.0-dev"
                }
            },
            "autoload": {
                "files": [
                    "src/functions.php"
                ],
                "psr-4": {
                    "Humbug\\PhpScoper\\": "src/"
                }
            },
            "notification-url": "https://packagist.org/downloads/",
            "license": [
                "MIT"
            ],
            "authors": [
                {
                    "name": "Bernhard Schussek",
                    "email": "bschussek@gmail.com"
                },
                {
                    "name": "Théo Fidry",
                    "email": "theo.fidry@gmail.com"
                },
                {
                    "name": "Pádraic Brady",
                    "email": "padraic.brady@gmail.com"
                }
            ],
            "description": "Prefixes all PHP namespaces in a file or directory.",
            "time": "2018-04-25T21:59:07+00:00"
        },
        {
            "name": "justinrainbow/json-schema",
            "version": "5.2.7",
            "source": {
                "type": "git",
                "url": "https://github.com/justinrainbow/json-schema.git",
                "reference": "8560d4314577199ba51bf2032f02cd1315587c23"
            },
            "dist": {
                "type": "zip",
                "url": "https://api.github.com/repos/justinrainbow/json-schema/zipball/8560d4314577199ba51bf2032f02cd1315587c23",
                "reference": "8560d4314577199ba51bf2032f02cd1315587c23",
                "shasum": ""
            },
            "require": {
                "php": ">=5.3.3"
            },
            "require-dev": {
                "friendsofphp/php-cs-fixer": "^2.1",
                "json-schema/json-schema-test-suite": "1.2.0",
                "phpunit/phpunit": "^4.8.35"
            },
            "bin": [
                "bin/validate-json"
            ],
            "type": "library",
            "extra": {
                "branch-alias": {
                    "dev-master": "5.0.x-dev"
                }
            },
            "autoload": {
                "psr-4": {
                    "JsonSchema\\": "src/JsonSchema/"
                }
            },
            "notification-url": "https://packagist.org/downloads/",
            "license": [
                "MIT"
            ],
            "authors": [
                {
                    "name": "Bruno Prieto Reis",
                    "email": "bruno.p.reis@gmail.com"
                },
                {
                    "name": "Justin Rainbow",
                    "email": "justin.rainbow@gmail.com"
                },
                {
                    "name": "Igor Wiedler",
                    "email": "igor@wiedler.ch"
                },
                {
                    "name": "Robert Schönthal",
                    "email": "seroscho@googlemail.com"
                }
            ],
            "description": "A library to validate a json schema.",
            "homepage": "https://github.com/justinrainbow/json-schema",
            "keywords": [
                "json",
                "schema"
            ],
            "time": "2018-02-14T22:26:30+00:00"
        },
        {
            "name": "nikic/iter",
            "version": "v1.6.0",
            "source": {
                "type": "git",
                "url": "https://github.com/nikic/iter.git",
                "reference": "fed36b417ea93fe9b4b7cb2e2abf98d91092564c"
            },
            "dist": {
                "type": "zip",
                "url": "https://api.github.com/repos/nikic/iter/zipball/fed36b417ea93fe9b4b7cb2e2abf98d91092564c",
                "reference": "fed36b417ea93fe9b4b7cb2e2abf98d91092564c",
                "shasum": ""
            },
            "require": {
                "php": ">=5.5.0"
            },
            "require-dev": {
                "phpunit/phpunit": "~4.0|~5.0"
            },
            "type": "library",
            "autoload": {
                "files": [
                    "src/bootstrap.php"
                ]
            },
            "notification-url": "https://packagist.org/downloads/",
            "license": [
                "BSD-3-Clause"
            ],
            "authors": [
                {
                    "name": "Nikita Popov",
                    "email": "nikic@php.net"
                }
            ],
            "description": "Iteration primitives using generators",
            "keywords": [
    