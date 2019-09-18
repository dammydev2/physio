onsole.git",
                "reference": "2e06a5ccb19dcf9b89f1c6a677a39a8df773635a"
            },
            "dist": {
                "type": "zip",
                "url": "https://api.github.com/repos/symfony/console/zipball/2e06a5ccb19dcf9b89f1c6a677a39a8df773635a",
                "reference": "2e06a5ccb19dcf9b89f1c6a677a39a8df773635a",
                "shasum": ""
            },
            "require": {
                "php": ">=5.3.9",
                "symfony/polyfill-mbstring": "~1.0"
            },
            "require-dev": {
                "psr/log": "~1.0",
                "symfony/event-dispatcher": "~2.1|~3.0.0",
                "symfony/process": "~2.1|~3.0.0"
            },
            "suggest": {
                "psr/log": "For using the console logger",
                "symfony/event-dispatcher": "",
                "symfony/process": ""
            },
            "type": "library",
            "extra": {
                "branch-alias": {
                    "dev-master": "2.8-dev"
                }
            },
            "autoload": {
                "psr-4": {
                    "Symfony\\Component\\Console\\": ""
                },
                "exclude-from-classmap": [
                    "/Tests/"
                ]
            },
            "notification-url": "https://packagist.org/downloads/",
            "license": [
                "MIT"
            ],
            "authors": [
                {
                    "name": "Fabien Potencier",
                    "email": "fabien@symfony.com"
                },
                {
                    "name": "Symfony Community",
                    "homepage": "https://symfony.com/contributors"
                }
            ],
            "description": "Symfony Console Component",
            "homepage": "https://symfony.com",
            "time": "2015-12-22 10:25:57"
        },
        {
            "name": "symfony/event-dispatche