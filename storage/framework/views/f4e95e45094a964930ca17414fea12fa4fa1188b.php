uest))
#33 /Library/WebServer/Documents/physio/vendor/laravel/framework/src/Illuminate/Foundation/Http/Kernel.php(151): Illuminate\\Pipeline\\Pipeline->then(Object(Closure))
#34 /Library/WebServer/Documents/physio/vendor/laravel/framework/src/Illuminate/Foundation/Http/Kernel.php(116): Illuminate\\Foundation\\Http\\Kernel->sendRequestThroughRouter(Object(Illuminate\\Http\\Request))
#35 /Library/WebServer/Documents/physio/public/index.php(55): Illuminate\\Foundation\\Http\\Kernel->handle(Object(Illuminate\\Http\\Request))
#36 /Library/WebServer/Documents/physio/server.php(21): require_once('/Library/WebSer...')
#37 {main}
"} 
[2019-05-25 09:18:45] local.ERROR: Array to string conversion {"userId":4,"exception":"[object] (ErrorException(code: 0): Array to string conversion at /Library/WebServer/Documents/physio/vendor/laravel/framework/src/Illuminate/Support/Str.php:335)
[stacktrace]
#0 /Library/WebServer/Documents/physio/vendor/laravel/framework/src/Illuminate/Support/Str.php(335): Illuminate\\Foundation\\Bootstrap\\HandleExceptions->handleError(8, 'Array to string...', '/Library/WebSer...', 335, Array)
#1 /Library/WebServer/Documents/physio/vendor/laravel/framework/src/Illuminate/Database/QueryException.php(56): Illuminate\\Support\\Str::replaceArray('?', Array, 'insert into `or...')
#2 /Library/WebServer/Documents/physio/vendor/laravel/framework/src/Illuminate/Database/QueryException.php(39): Illuminate\\Database\\QueryException->formatMessage('insert into `or...', Array, Object(ErrorException))
#3 /Library/WebServer/Documents/physio/vendor/laravel/framework/src/Illuminate/Database/Connection.php(665): Illuminate\\Database\\QueryException->__construct('insert into `or...', Array, Object(ErrorException))
#4 /Library/WebServer/Documents/physio/vendor/laravel/framework/src/Illuminate/Database/Connection.php(624): Illuminate\\Database\\Connection->runQueryCallback('insert into `or...', Array, Object(Closure))
#5 /Library/WebServer/Documents/physio/vendor/laravel/framework/src/Illuminate/Database/Connection.php(459): Illuminate\\Database\\Connection->run('insert into `or...', Array, Object(Closure))
#6 /Library/WebServer/Documents/physio/vendor/laravel/framework/src/Illuminate/Database/Connection.php(411): Illuminate\\Database\\Connection->statement('insert into `or...', Array)
#7 /Library/WebServer/Documents/physio/vendor/laravel/framework/src/Illuminate/Database/Query/Processors/Processor.php(32): Illuminate\\Database\\Connection->insert('insert into `or...', Array)
#8 /Library/WebServer/Documents/physio/vendor/laravel/framework/src/Illuminate/Database/Query/Builder.php(2657): Illuminate\\Database\\Query\\Processors\\Processor->processInsertGetId(Object(Illuminate\\Database\\Query\\Builder), 'insert into `or...', Array, 'id')
#9 /Library/WebServer/Documents/physio/vendor/laravel/framework/src/Illuminate/Database/Eloquent/Builder.php(1347): Illuminate\\Database\\Query\\Builder->insertGetId(Array, 'id')
#10 /Library/WebServer/Documents/physio/vendor/laravel/framework/src/Illuminate/Database/Eloquent/Model.php(835): Illuminate\\Database\\Eloquent\\Builder->__call('insertGetId', Array)
#11 /Library/WebServer/Documents/physio/vendor/laravel/framework/src/Illuminate/Database/Eloquent/Model.php(800): Illuminate\\Database\\Eloquent\\Model->insertAndSetId(Object(Illuminate\\Database\\Eloquent\\Builder), Array)
#12 /Library/WebServer/Documents/physio/vendor/laravel/framework/src/Illuminate/Database/Eloquent/Model.php(663): Illuminate\\Database\\Eloquent\\Model->performInsert(Object(Illuminate\\Database\\Eloquent\\Builder))
#13 /Library/WebServer/Documents/physio/vendor/laravel/framework/src/Illuminate/Database/Eloquent/Builder.php(793): Illuminate\\Database\\Eloquent\\Model->save()
#14 /Library/WebServer/Documents/physio/vendor/laravel/framework/src/Illuminate/S