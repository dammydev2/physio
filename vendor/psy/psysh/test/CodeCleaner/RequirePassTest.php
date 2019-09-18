 {
                    "name": "Niklas Keller",
                    "email": "me@kelunik.com"
                },
                {
                    "name": "Aaron Piotrowski",
                    "email": "aaron@trowski.com"
                }
            ],
            "description": "A stream abstraction to make working with non-blocking I/O simple.",
            "homepage": "http://amphp.org/byte-stream",
            "keywords": [
                "amp",
                "amphp",
                "async",
                "io",
                "non-blocking",
                "stream"
            ],
            "time": "2018-04-04T05:33:09+00:00"
        },
        {
            "name": "amphp/parallel",
            "version": "v0.2.5",
            "source": {
                "type": "git",
                "url": "https://github.com/amphp/parallel.git",
                "reference": "732694688461936bec02c0ccf020dfee10c4f7ee"
            },
            "dist": {
                "type": "zip",
                "url": "https://api.github.com/repos/amphp/parallel/zipball/732694688461936bec02c0ccf020dfee10c4f7ee",
                "reference": "732694688461936bec02c0ccf020dfee10c4f7ee",
                "shasum": ""
            },
            "require": {
                "amphp/amp": "^2",
                "amphp/byte-stream": "^1.2",
                "amphp/parser": "^1",
                "amphp/process": "^0.2 || ^0.3",
                "amphp/sync": "^1.0.1"
            },
            "require-dev": {
                "amphp/phpunit-util": "^1",
                "friendsofphp/php-cs-fixer": "^2.3",
                "phpunit/phpunit": "^6"
            },
            "suggest": {
                "ext-pthreads": "Required for thread contexts"
            },
            "type": "library",
            "autoload": {
                "psr-4": {
                    "Amp\\Parallel\\": "lib"
                },
                "files": [
                    "lib/Worker/functions.php"
                ]
            },
            "notification-url": "https://packagist.org/downloads/",
            "license": [
                "MIT"
            ],
            "authors": [
                {
                    "name": "Stephen Coakley",
                    "email": "me@stephencoakley.com"
                },
                {
                    "name": "Aaron Piotrowski",
                    "email": "aaron@trowski.com"
                }
            ],
            "description": "Parallel processing component for Amp.",
            "homepage": "https://github.com/amphp/parallel",
            "keywords": [
                "async",
                "asynchronous",
                "concurrent",
                "multi-processing",
 