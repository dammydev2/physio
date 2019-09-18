{
    "name": "illuminate/database",
    "description": "The Illuminate Database package.",
    "license": "MIT",
    "homepage": "https://laravel.com",
    "support": {
        "issues": "https://github.com/laravel/framework/issues",
        "source": "https://github.com/laravel/framework"
    },
    "keywords": ["laravel", "database", "sql", "orm"],
    "authors": [
        {
            "name": "Taylor Otwell",
            "email": "taylor@laravel.com"
        }
    ],
    "require": {
        "php": "^7.1.3",
        "ext-json": "*",
        "illuminate/container": "5.8.*",
        "illuminate/contracts": "5.8.*",
        "illuminate/support": "5.8.*"
    },
    "autoload": {
        "psr-4": {
            "Illuminate\\Database\\": ""
        }
    },
    "extra": {
        "branch-alias": {
            "dev-master": "5.8-dev"
        }
    },
    "suggest": {
        "doctrine/dbal": "Required to rename columns and drop SQLite columns (^2.6).",
        "fzaninotto/faker": "Required to use the eloquent factory builder (^1.4).",
        "illuminate/console