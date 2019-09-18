ver/Documents/physio/vendor/laravel/framework/src/Illuminate/Foundation/Http/Kernel.php(116): Illuminate\\Foundation\\Http\\Kernel->sendRequestThroughRouter(Object(Illuminate\\Http\\Request))
#73 /Library/WebServer/Documents/physio/public/index.php(55): Illuminate\\Foundation\\Http\\Kernel->handle(Object(Illuminate\\Http\\Request))
#74 /Library/WebServer/Documents/physio/server.php(21): require_once('/Library/WebSer...')
#75 {main}
"} 
[2019-05-25 09:12:50] local.ERROR: Array to string conversion {"userId":4,"exception":"[object] (ErrorException(code: 0): Array to string conversion at /Library/WebServer/Documents/physio/vendor/laravel/framework/src/Illuminate/Support/Str.php:335)
[stacktrace]
#0 /Library/WebServer/Documents/physio/vendor/laravel/framework/src/Illuminate/Support/Str.php(335): Illuminate\\Foundation\\Bootstrap\\HandleExceptions->handleError(8, 'Array to string...', '/Library/WebSer...', 335, Array)
#1 /Library/WebServer/Documents/physio/vendor/laravel/framework/src/Illuminate/Database/QueryException.php(56): Illuminate\\Support\\Str::replaceArray('?', Array, 'insert into `or...')
#2 /Library/WebServer/Documents/physio/vendor/laravel/framework/src/Illuminate/Database/QueryException.php(39): Illuminate\\Database\\QueryException->formatMessage('insert into `or...', Array, Object(ErrorException))
#3 /Library/WebServer/Documents/physio/vendor/laravel/framework/src/Illuminate/Database/Conn