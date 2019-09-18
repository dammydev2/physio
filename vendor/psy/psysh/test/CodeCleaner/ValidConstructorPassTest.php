github.com/herrera-io/php-annotations",
            "keywords": [
                "annotations",
                "doctrine",
                "tokenizer"
            ],
            "abandoned": true,
            "time": "2014-02-03T17:34:08+00:00"
        },
        {
            "name": "humbug/box",
            "version": "3.0.0-alpha.5",
            "source": {
                "type": "git",
                "url": "https://github.com/humbug/box.git",
                "reference": "26b3f481e3b375f55c0644f501b831f7c05d8058"
            },
            "dist": {
                "type": "zip",
                "url": "https://api.github.com/repos/humbug/box/zipball/26b3f481e3b375f55c0644f501b831f7c05d8058",
                "reference": "26b3f481e3b375f55c0644f501b831f7c05d8058",
                "shasum": ""
            },
            "require": {
                "amphp/parallel-functions": "^0.1.2",
                "beberlei/assert": "^2.8",
                "composer/composer": "^1.6",
                "composer/xdebug-handler": "^1.1.0",
                "ext-phar": "*",
                "herrera-io/annotations": "~1.0",
                "humbug/php-scoper": "^1.0@dev",
                "justinrainbow/json-schema": "^5.2",
                "nikic/iter": "^1.6",
                "php": "^7.1",
                "phpseclib/phpseclib": "~2.0",
                "seld/jsonlint": "^1.6",
                "symfony/console": "^3.4 || ^4.0",
                "symfony/filesystem": "^3.4 || ^4.0",
                "symfony/finder": "^3.4 || ^4.0",
                "symfony/var-dumper": "^3.4 || ^4.0",
                "webmozart/path-util": "^2.3"
            },
            "require-dev": {
                "bamarni/composer-bin-plugin": "^1.2",
                "infection/infection": "^0.8",
                "mikey179/vfsstream": "^1.1",
                "phpunit/phpunit": "^7.0"
            },
            "suggest": {
                "ext-openssl": "To accelerate private key generation."
            },
            "bin": [
                "bin/box"
            ],
            "type": "library",
            "extra": {
                "branch-alias": {
                    "dev-master": "3.x-dev"
                },
                "bamarni-bin": {
                    "bin-links": false
                }
            },
            "autoload": {
                "psr-4": {
                    "KevinGH\\Box\\": "src"
                },
                "files": [
                    "src/FileSystem/file_system.php",
                    "src/functions.php"
                ],
                "exclude-from-classmap": [
                    "/Test/"
                ]
            },
            "notification-url": "https://packagist.org/downloads/",
            "license": [
    