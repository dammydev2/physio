ent\\Debug\\Exception\\FatalThrowableError(code: 0): syntax error, unexpected ';', expecting ',' or ')' at /Library/WebServer/Documents/physio/app/Http/Controllers/OrtController.php:63)
[stacktrace]
#0 /Library/WebServer/Documents/physio/vendor/composer/ClassLoader.php(322): Composer\\Autoload\\includeFile('/Library/WebSer...')
#1 [internal function]: Composer\\Autoload\\ClassLoader->loadClass('App\\\\Http\\\\Contro...')
#2 [internal function]: spl_autoload_call('App\\\\Http\\\\Contro...')
#3 /Library/WebServer/Documents/physio/vendor/laravel/framework/src/Illuminate/Container/Container.php(790): ReflectionClass->__construct('App\\\\Http\\\\Contro...')
#4 /Library/WebServer/Documents/physio/vendor/laravel/framework/src/Illuminate/Container/Container.php(667): Illuminate\\Container\\Container->build('App\\\\Http\\\\Contro...')
#5 /Library/WebServer/Documents/physio/vendor/laravel/framework/src/Illuminate/Container/Container.php(615): Illuminate\\Container\\Container->resolve('App\\\\Http\\\\Contro...', Array)
#6 /Library/WebServer/Documents/physio/vendor/laravel/framework/src/Illuminate/Foundation/Application.php(757): Illuminate\\Container\\Container->make('App\\\\Http\\\\Contro...', Array)
#7 /Library/WebServer/Documents/physio/vendor/laravel/framework/src/Illuminate/Routing/Route.php(233): Illuminate\\Foundation\\Application->make('App\\\\Http\\\\Contro...')
#8 /Library/WebServer/Documents/physio/vendor/laravel/framework/src/Illuminate/Routing/Route.php(833): Illuminate\\Routing\\Route->getController()
#9 /Library/WebServer/Documents/physio/vendor/laravel/framework/src/Illuminate/Routing/Route.php(794): Illuminate\\Routing\\Route->controllerMiddleware()
#10 /