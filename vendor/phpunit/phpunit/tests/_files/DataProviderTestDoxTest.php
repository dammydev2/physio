  "league/flysystem": "Required to use the Flysystem local and FTP drivers (~1.0).",
                "league/flysystem-aws-s3-v3": "Required to use the Flysystem S3 driver (~1.0).",
                "league/flysystem-rackspace": "Required to use the Flysystem Rackspace driver (~1.0)."
            },
            "type": "library",
            "extra": {
                "branch-alias": {
                    "dev-master": "5.2-dev"
                }
            },
            "autoload": {
                "psr-4": {
                    "Illuminate\\Filesystem\\": ""
                }
            },
            "notification-url": "https://packagist.org/downloads/",
            "license": [
                "MIT"
            ],
            "authors": [
                {
                    "name": "Taylor Otwell",
                    "email": "taylorotwell@gmail.com"
                }
            ],
            "description": "The Illuminate Filesystem package.",
            "homepage": "http://laravel.com",
            "time": "2016-03-21 14:55:26"
        },
        {
            "name": "illuminate/http",
            "version": "v5.2.27",
            "source": {
                "type": "git",
                "url": "https://github.com/illuminate/http.git",
                "reference": "906b677c83ae4917f3e14cd966d8b22518de0bbd"
            },
            "dist": {
                "type": "zip",
                "url": "https://api.github.com/repos/illuminate/http/zipball/906b677c83ae4917f3e14cd966d8b22518de0bbd",
                "reference": "906b677c83ae4917f3e14cd966d8b22518de0bbd",
                "shasum": ""
            },
            "require": {
                "illuminate/session": "5.2.*",
                "illuminate/support": "5.2.*",
                "php": ">=5.5.9",
                "symfony/http-foundation": "2.8.*|3.0.*",
            