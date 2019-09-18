",
                "shasum": ""
            },
            "require": {
                "ext-json": "*",
                "ext-simplexml": "*",
                "guzzle/guzzle": "^2.8|^3.0",
                "php": ">=5.3.3",
                "psr/log": "^1.0",
                "symfony/config": "^2.4|^3.0",
                "symfony/console": "^2.1|^3.0",
                "symfony/stopwatch": "^2.2|^3.0",
                "symfony/yaml": "^2.1|^3.0"
            },
            "suggest": {
                "symfony/http-kernel": "Allows Symfony integration"
            },
            "bin": [
                "bin/coveralls"
            ],
            "type": "library",
            "extra": {
                "branch-alias": {
                    "dev-master": "0.8-dev"
                }
            },
            "autoload": {
                "psr-4": {
                    "Satooshi\\": "src/Satooshi/"
                }
            },
            "notification-url": "https://packagist.org/downloads/",
            "license": [
                "MIT"
            ],
            "authors": [
                {
                    "name": "Kitamura Satoshi",
                    "email": "with.no.parachute@gmail.com",
                  