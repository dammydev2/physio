guzzle/plugin-mock": "self.version",
                "guzzle/plugin-oauth": "self.version",
                "guzzle/service": "self.version",
                "guzzle/stream": "self.version"
            },
            "require-dev": {
                "doctrine/cache": "~1.3",
                "monolog/monolog": "~1.0",
                "phpunit/phpunit": "3.7.*",
                "psr/log": "~1.0",
                "symfony/class-loader": "~2.1",
                "zendframework/zend-cache": "2.*,<2.3",
                "zendframework/zend-log": "2.*,<2.3"
            },
            "suggest": {
                "guzzlehttp/guzzle": "Guzzle 5 has moved to a new package name. The package you have installed, Guzzle 3, is deprecated."
            },
            "type": "library",
            "extra": {
                "branch-alias": {
                    "dev-master": "3.9-dev"
                }
            },
            "autoload": {
                "psr-0": {
                    "Guzzle": "src/",
                    "Guzzle\\Tests": "tests/"
                }
            },
        