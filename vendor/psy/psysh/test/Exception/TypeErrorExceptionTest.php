ttps://github.com/symfony/var-dumper.git",
                "reference": "3c34cf3f4bbac9e003d9325225e9ef1a49180a18"
            },
            "dist": {
                "type": "zip",
                "url": "https://api.github.com/repos/symfony/var-dumper/zipball/3c34cf3f4bbac9e003d9325225e9ef1a49180a18",
                "reference": "3c34cf3f4bbac9e003d9325225e9ef1a49180a18",
                "shasum": ""
            },
            "require": {
                "php": "^7.1.3",
                "symfony/polyfill-mbstring": "~1.0",
                "symfony/polyfill-php72": "~1.5"
            },
            "conflict": {
                "phpunit/phpunit": "<4.8.35|<5.4.3,>=5.0"
            },
            "require-dev": {
                "ext-iconv": "*",
                "twig/twig": "~1.34|~2.4"
            },
            "suggest": {
                "ext-iconv": "To convert non-UTF-8 strings to UTF-8 (or symfony/polyfill-iconv in case ext-iconv cannot be used).",
                "ext-intl": "To show region name in time zone dump"
            },
            "type": "library",
            "extra": {
                "branch-alias": {
                    "dev-master": "4.0-dev"
                }
            },
            "autoload": {
                "files": [
                    "Resources/functions/dump.php"
                ],
                "psr-4": {
                    "Symfony\\Component\\VarDumper\\": ""
                },
                "exclude-from-classmap": [
                    "/Tests/"
                ]
            },
            "notification-url": "https://packagist.org/downloads/",
            "licens