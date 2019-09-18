[2019-05-25 07:27:34] local.ERROR: syntax error, unexpected '$table' (T_VARIABLE) {"exception":"[object] (Symfony\\Component\\Debug\\Exception\\FatalThrowableError(code: 0): syntax error, unexpected '$table' (T_VARIABLE) at /Library/WebServer/Documents/physio/database/migrations/2019_05_24_221842_create_ort_page1s_table.php:19)
[stacktrace]
#0 /Library/WebServer/Documents/physio/vendor/laravel/framework/src/Illuminate/Database/Migrations/Migrator.php(448): Illuminate\\Filesystem\\Filesystem->requireOnce('/Library/WebSer...')
#1 /Library/WebServer/Documents/physio/vendor/laravel/framework/src/Illuminate/Database/Migrations/Migrator.php(90): Illuminate\\Database\\Migrations\\Migrator->requireFiles(Array)
#2 /Library/WebServer/Documents/physio/vendor/laravel/framework/src/Illuminate/Database/Console/Migrations/MigrateCommand.php(71): Illuminate\\Database\\Migrations\\Migrator->run(Array, Array)
#3 [internal function]: Illuminate\\Database\\Console\\Migrations\\MigrateCommand->handle()
#4 /Library/WebServer/Documents/physio/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(32): call_user_func_array(Array, Array)
#5 /Library/WebServer/Documents/physio/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(90): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()
#6 /Library/WebServer/Documents/physio/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.ph