       "url": "https://api.github.com/repos/composer/ca-bundle/zipball/d2c0a83b7533d6912e8d516756ebd34f893e9169",
                "reference": "d2c0a83b7533d6912e8d516756ebd34f893e9169",
                "shasum": ""
            },
            "require": {
                "ext-openssl": "*",
                "ext-pcre": "*",
                "php": "^5.3.2 || ^7.0"
            },
            "require-dev": {
                "phpunit/phpunit": "^4.8.35 || ^5.7 || ^6.5",
                "psr/log": "^1.0",
                "symfony/process": "^2.5 || ^3.0 || ^4.0"
            },
            "type": "library",
            "extra": {
                "branch-alias": {
                    "dev-master": "1.x-dev"
                }
            },
            "autoload": {
                "psr-4": {
                    "Composer\\CaBundle\\": "src"
                }
            },
            "notification-url": "https://packagist.org/downloads/",
            "license": [
                "MIT"
            ],
            "authors": [
                {
                    "name": "Jordi Boggiano",
                    "email": "j.boggiano@seld.be",
                    "homepage": "http://seld.be"
                }
            ],
            "description": "Lets you find a path to the system CA bundle, and includes a fallback to the Mozilla CA bundle.",
            "keywords": [
                "cabundle",
                "cacert",
                "certificate",
                "ssl",
                "tls"
            ],
            "time": "2018-03-29T19:57:20+00:00"
        },
        {
            "name": "composer/composer",
            "version": "1.6.5",
            "source": {
                "type": "git",
                "url": "https://github.com/composer/composer.git",
                "reference": "b184a92419cc9a9c4c6a09db555a94d441cb11c9"
            },
            "dist": {
                "type": "zip",
                "url": "https://api.github.com/repos/composer/composer/zipball/b184a92419cc9a9c4c6a09db555a94d441cb11c9",
                "reference": "b184a92419cc9a9c4c6a09db555a94d441cb11c9",
                "shasum": ""
            },
            "require": {
                "composer/ca-bundle": "^1.0",
                "composer/semver": "^1.0",
                "composer/spdx-licenses": "^1.2",
                "justinrainbow/json-schema": "^3.0 || ^4.0 || ^5.0",
                "php": "^5.3.2 || ^7.0",
                "psr/log": "^1.0",
                "seld/cli-prompt": "^1.0",
                "seld/jsonlint": "^1.4",
                "seld/phar-utils": "^1.0",
                "symfony/console": "^2.7 || ^3.0 || ^4.0",
                "symfony/filesystem": "^2.7 || ^3.0 || ^4.0",
                "symfony/finder": "^2.7 || ^3.0 || ^4.0",
                "symfony/process": "^2.7 || ^3.0 || ^4.0"
            },
            "conflict": {
                "symfony/console": "2.8.38"
            },
            "require-dev": {
                "phpunit/phpunit": "^4.8.35 || ^5.7",
                "phpunit/phpunit-mock-objects": "^2.3 || ^3.0"
            },
            "suggest": {
                "ext-openssl": "Enabling the openssl extension allows you to access https URLs for repositories and packages",
                "ext-zip": "Enabling the zip extension allows you to unzip archives",
                "ext-zlib": "Allow gzip compression of HTTP requests"
            },
            "bin": [
                "bin/composer"
            ],
            "type": "library",
            "extra": {
                "branch-alias": {
                    "dev-master": "1.6-dev"
                }
            },
            "autoload": {
                "psr-4": {
                    "Composer\\": "src/Composer"
                }
            },
            "notification-url": "https://packagist.org/downloads/",
            "license": [
                "MIT"
            ],
            "authors": [
                {
                    "name": "Nils Adermann",
                    "email": "naderman@naderman.de",
                    "homepage": "http://www.naderman.de"
                },
                {
                    "name": "Jordi Boggiano",
                    "email": "j.boggiano@seld.be",
                    "homepage": "http://seld.be"
                }
            ],
            "description": "Composer helps you declare, manage and install dependencies of PHP projects, ensuring you have the right stack everywhere.",
            "homepage": "https://getcomposer.org/",
            "keywords": [
                "autoload",
                "dependency",
                "package"
            ],
            "time": "2018-05-04T09:44:59+00:00"
        },
        {
            "name": "composer/semver",
            "version": "1.4.2",
            "source": {
                "type": "git",
                "url": "https://github.com/composer/semver.git",
                "reference": "c7cb9a2095a074d131b65a8a0cd294479d785573"
            },
            "dist": {
                "type": "zip",
                "url": "https://api.github.com/repos/composer/semver/zipball/c7cb9a2095a074d131b65a8a0cd294479d785573",
                "reference": "c7cb9a2095a074d131b65a8a0cd294479d785573",
                "shasum": ""
            },
            "require": {
                "php": "^5.3.2 || ^7.0"
            },
            "require-dev": {
                "phpunit/phpunit": "^4.5 || ^5.0.5",
                "phpunit/phpunit-mock-objects": "2.3.0 || ^3.0"
            },
            "type": "library",
            "extra": {
                "branch-alias": {
                    "dev-master": "1.x-dev"
                }
            },
            "autoload": {
                "psr-4": {
                    "Composer\\Semver\\": "src"
                }
            },
            "notification-url": "https://packagist.org/downloads/",
            "license": [
                "MIT"
            ],
            "authors": [
                {
                    "name": "Nils Adermann",
                    "email": "naderman@naderman.de",
                    "homepage": "http://www.naderman.de"
                },
                {
                    "name": "Jordi Boggiano",
                    "email": "j.boggiano@seld.be",
                    "homepage": "http://seld.be"
                },
                {
                    "name": "Rob Bast",
                    "email": "rob.bast@gmail.com",
                    "homepage": "http://robbast.nl"
                }
            ],
            "description": "Semver library that offers utilities, version constraint parsing and validation.",
            "keywords": [
                "semantic",
                "semver",
                "validation",
                "versioning"
            ],
            "time": "2016-08-30T16:08:34+00:00"
        },
        {
            "name": "composer/spdx-licenses",
            "version": "1.4.0",
            "source": {
                "type": "git",
                "url": "https://github.com/composer/spdx-licenses.git",
                "reference": "cb17687e9f936acd7e7245ad3890f953770dec1b"
            },
            "dist": {
                "type": "zip",
                "url": "https://api.github.com/repos/composer/spdx-licenses/zipball/cb17687e9f936acd7e7245ad3890f953770dec1b",
                "reference": "cb17687e9f936acd7e7245ad3890f953770dec1b",
                "shasum": ""
            },
            "require": {
                "php": "^5.3.2 || ^7.0"
            },
            "require-dev": {
                "phpunit/phpunit": "^4.8.35 || ^5.7 || ^6.5",
                "phpunit/phpunit-mock-objects": "2.3.0 || ^3.0"
            },
            "type": "library",
            "extra": {
                "branch-alias": {
                    "dev-master": "1.x-dev"
                }
            },
            "autoload": {
                "psr-4": {
                    "Composer\\Spdx\\": "src"
                }
            },
            "notification-url": "https://packagist.org/downloads/",
            "license": [
                "MIT"
            ],
            "authors": [
                {
                    "name": "Nils Adermann",
                    "email": "naderman@naderman.de",
                    "homepage": "http://www.naderman.de"
                },
                {
                    "name": "Jordi Boggiano",
                    "email": "j.boggiano@seld.be",
                    "homepage": "http://seld.be"
                },
                {
                    "name": "Rob Bast",
                    "email": "rob.bast@gmail.com",
                    "homepage": "http://robbast.nl"
                }
            ],
            "description": "SPDX licenses list and validation library.",
            "keywords": [
                "license",
                "spdx",
                "validator"
            ],
            "time": "2018-04-30T10:33:04+00:00"
        },
        {
            "name": "composer/xdebug-handler",
            "version": "1.1.0",
            "source": {
                "type": "git",
                "url": "https://github.com/composer/xdebug-handler.git",
                "reference": "c919dc6c62e221fc6406f861ea13433c0aa24f08"
            },
            "dist": {
                "type": "zip",
                "url": "https://api.github.com/repos/composer/xdebug-handler/zipball/c919dc6c62e221fc6406f861ea13433c0aa24f08",
                "reference": "c919dc6c62e221fc6406f861ea13433c0aa24f08",
                "shasum": ""
            },
            "require": {
                "php": "^5.3.2 || ^7.0",
                "psr/log": "^1.0"
            },
            "require-dev": {
                "phpunit/phpunit": "^4.8.35 || ^5.7 || ^6.5"
            },
            "type": "library",
            "autoload": {
                "psr-4": {
                    "Composer\\XdebugHandler\\": "src"
                }
            },
            "notification-url": "https://packagist.org/downloads/",
            "license": [
                "MIT"
            ],
            "authors": [
                {
                    "name": "John Stevenson",
                    "email": "john-stevenson@blueyonder.co.uk"
                }
            ],
            "description": "Restarts a process without xdebug.",
            "keywords": [
                "Xdebug",
                "performance"
            ],
            "time": "2018-04-11T15:42:36+00:00"
        },
        {
            "name": "doctrine/annotations",
            "version": "v1.6.0",
            "source": {
                "type": "git",
                "url": "https://github.com/doctrine/annotations.git",
                "reference": "c7f2050c68a9ab0b