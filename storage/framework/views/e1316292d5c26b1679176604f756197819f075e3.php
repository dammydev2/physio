ure}(Object(Illuminate\\Http\\Request))
#60 /Library/WebServer/Documents/physio/vendor/laravel/framework/src/Illuminate/Foundation/Http/Middleware/ValidatePostSize.php(27): Illuminate\\Routing\\Pipeline->Illuminate\\Routing\\{closure}(Object(Illuminate\\Http\\Request))
#61 /Library/WebServer/Documents/physio/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(163): Illuminate\\Foundation\\Http\\Middleware\\ValidatePostSize->handle(Object(Illuminate\\Http\\Request), Object(Closure))
#62 /Library/WebServer/Documents/physio/vendor/laravel/framework/src/Illuminate/Routing/Pipeline.php(53): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(Illuminate\\Http\\Request))
#63 /Library/WebServer/Documents/physio/vendor/laravel/framework/src/Illuminate/Foundation/Http/Middleware/CheckForMaintenanceMode.php(62): Illuminate\\Routing\\Pipeline->Illuminate\\Routing\\{closure}(Object(Illuminate\\Http\\Request))
#64 /Library/WebServer/Documents/physio/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(163): Illuminate\\Foundation\\Http\\Middleware\\CheckForMaintenanceMode->handle(Object(Illuminate\\Http\\Request), Object(Closure))
#65 /Library/WebServer/Documents/physio/vendor/laravel/framework/src/Illuminate/Routing/Pipeline.php(53): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(Illuminate\\Http\\Request))
#66 /Library/WebServer/Documents/physio/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(104): Illuminate\\Routing\\Pipeline->Illuminate\\Routing\\{closure}(Object(Illuminate\\Http\\Request))
#67 /Library/WebServer/Documents/physio/vendor/laravel/framework/src/Illuminate/Foundation/Http/Kernel.php(151): Illuminate\\Pipeline\\Pipeline->then(Object(Closure))
#68 /Library/WebServer/Documents/physio/vendor/laravel/framework/src/Illuminate/Foundation/Http/Kernel.php(116): Illuminate\\Foundation\\Http\\Kernel->sendRequestThroughRouter(Object(Illuminate\\Http\\Request))
#69 /Library/WebServer/Documents/physio/public/index.php(55): Illuminate\\Foundation\\Http\\Kernel->handle(Object(Illuminate\\Http\\Request))
#70 /Library/WebServer/Documents/physio/server.php(21): require_once('/Library/WebSer...')
#71 {main}
"} 
[2019-05-25 09:11:17] local.ERROR: Class 'App\Http\Controllers\OrtPage3' not found {"userId":4,"exception":"[object] (Symfony\\Component\\Debug\\Exception\\FatalThrowableError(code: 0): Class 'App\\Http\\Controllers\\OrtPage3' not found at /Library/WebServer/Documents/physio/app/Http/Controllers/OrtController.php:114)
[stacktrace]
#0 [internal function]: App\\Http\\Controllers\\OrtController->ortPage3(Object(Illuminate\\Http\\Request))
#1 /Library/WebServer/Documents/physio/vendor/laravel/framework/src/Illuminate/Routing/Controller.php(54): call_user_func_array(Array, Array)
#2 /Library/WebServer/Documents/physio/vendor/laravel/framework/src/Illuminate/Routing/ControllerDispatcher.php(45): Illuminate\\Routing\\Controller->callAction('ortPage3', Array)
#3 /Library/WebServer/Documents/physio/vendor/laravel/framework/src/Illuminate/Routing/Route.php(219): Illuminate\\Routing\\ControllerDispatcher->dispatch(Object(Illuminate\\Routing\\Route), Object(App\\Http\\Controllers\\OrtController), 'ortPage3')
#4 /Library/WebServer/Documents/physio/vendor/laravel/framework/src/Illuminate/Routing/Route.php(176): Illuminate\\Routing\\Route->runController()
#5 /Library/WebServer/Documents/physio/vendor/laravel/framework/src/Illuminate/Routing/Router.php(680): Illuminate\\Routing\\Route->run()
#6 /Library/WebServer/Documents/physio/vendor/laravel/framework/src/Illuminate/Routing/Pipeline.php(30): Illuminate\\Routing\\Router->Illuminate\\Routing\\{closure}(Object(Illuminate\\Http\\Request))
#7 /Library/WebServer/Documents/physio/vendo