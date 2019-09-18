n": "v1.0.1",
            "source": {
                "type": "git",
                "url": "https://github.com/amphp/sync.git",
                "reference": "a1d8f244eb19e3e2a96abc4686cebc80995bbc90"
            },
            "dist": {
                "type": "zip",
                "url": "https://api.github.com/repos/amphp/sync/zipball/a1d8f244eb19e3e2a96abc4686cebc80995bbc90",
                "reference": "a1d8f244eb19e3e2a96abc4686cebc80995bbc90",
                "shasum": ""
            },
            "require": {
                "amphp/amp": "^2"
            },
            "require-dev": {
                "amphp/phpunit-util": "^1",
                "friendsofphp/php-cs-fixer": "^2.3",
                "phpunit/phpunit": "^6"
            },
            "type": "library",
            "autoload": {
                "psr-4": {
                    "Amp\\Sync\\": "lib"
                },
                "files": [
                    "lib/functions.php"
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
            "description": "Mutex, Semaphore, and other synchronization tools for Amp.",
            "homepage": "https://github.com/amphp/sync",
            "keywords": [
                "async",
                "asynchronous",
                "mutex",
                "semaphore",
                "synchronization"
            ],
            "time": "2017-11-29T21:48:53+00:00"
        },
        {
            "name": "beberlei/assert",
            "version": "v2.9.5",
            "source": {
                "type": "git",
                "url": "https://github.com/beberlei/assert.git",
                "reference": "c07fe163d6a3b3e4b1275981ec004397954afa89"
            },
            "dist": {
                "type": "zip",
                "url": "https://api.github.com/repos/beberlei/assert/zipball/c07fe163d6a3b3e4b1275981ec004397954afa89",
                "reference": "c07fe163d6a3b3e4b1275981ec004397954afa89",
                "shasum": ""
            },
            "require": {
                "ext-mbstring": "*",
                "php": ">=5.3"
            },
            "require-dev": {
                "friendsofphp/php-cs-fixer": "^2.1.1",
                "phpunit/phpunit": "^4.8.35|^5.7"
            },
            "type": "library",
            "autoload": {
                "psr-4": {
                    "Assert\\": "lib/Assert"
                },
                "files": [
                    "lib/Assert/functions.php"
                ]
            },
            "notification-url": "https://packagist.org/downloads/",
            "license": [
                "BSD-2-Clause"
            ],
            "authors": [
                {
                    "name": "Benjamin Eberlei",
                    "email": "kontakt@beberlei.de",
                    "role": "Lead Developer"
                },
