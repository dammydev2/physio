p(657): Illuminate\\Routing\\Router->runRouteWithinStack(Object(Illuminate\\Routing\\Route), Object(Illuminate\\Http\\Request))
#47 /Library/WebServer/Documents/physio/vendor/laravel/framework/src/Illuminate/Routing/Router.php(623): Illuminate\\Routing\\Router->runRoute(Object(Illuminate\\Http\\Request), Object(Illuminate\\Routing\\Route))
#48 /Library/WebServer/Documents/physio/vendor/laravel/framework/src/Illuminate/Routing/Router.php(612): Illuminate\\Routing\\Router->dispatchToRoute(Object(Illuminate\\Http\\Request))
#49 /Library/WebServer/Documents/physio/vendor/laravel/framework/src/Illuminate/Foundation/Http/Kernel.php(176): Illuminate\\Routing\\Router->dispatch(Object(Illuminate\\Http\\Request))
#50 /Library/WebServer/Documents/physio/vendor/laravel/framework/src/Illuminate/Routing/Pipeline.php(30): Illuminate\\Foundation\\Http\\Kernel->Illuminate\\Foundation\\Http\\{closure}(Object(Illuminate\\Http\\Request))
#51 /Library/WebServer/Documents/physio/vendor/fideloper/proxy/src/TrustProxies.php(57): Illuminate\\Routing\\Pipeline->Illuminate\\Routing\\{closure}(Object(Illuminate\\Http\\Request))
#52 /Library/WebServer/Documents/physio/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(163): Fideloper\\Proxy\\TrustProxies->handle(Object(Illuminate\\Http\\Request), Object(Closure))
#53 /Library/WebServer/Documents/physio/vendor/laravel/framework/src/Illuminate/Routing/Pipeline.php(53): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(Illuminate\\Http\\Request))
#54 /Library/WebServer/Documents/physio/vendor/laravel/framework/src/Illuminate/Foundation/Http/Middleware/TransformsRequest.php(21): Illuminate\\Routing\\Pipeline->Illuminate\\Routing\\{closure}(Object(Illuminate\\Http\\Request))
#55 /Library/WebServer/Documents/physio/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(163): Illuminate\\Foundation\\Http\\Middleware\\TransformsRequest->handle(Object(Illuminate\\Http\\Request), Object(Closure))
#56 /Library/WebServer/Documents/physio/vendor/laravel/framework/src/Illuminate/Routing/Pipeline.php(53): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(Illuminate\\Http\\Request))
#57 /Library/WebServer/Documents/physio/vendor/laravel/framework/src/Illuminate/Foundation/Http/Middleware/TransformsRequest.php(21): Illuminate\\Routing\\Pipeline->Illuminate\\Routing\\{closure}(Object(Illuminate\\Http\\Request))
#58 /Library/WebServer/Documents/physio/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(163): Illuminate\\Foundation\\Http\\Middleware\\TransformsRequest->handle(Object(Illuminate\\Http\\Request), Object(Closure))
#59 /Library/WebServer/Documents/physio/vendor/laravel/framework/src/Illuminate/Routing/Pipeline.php(53): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(Illuminate\\Http\\Request))
#60 /Library/WebServer/Documents/physio/vendor/laravel/framework/src/Illuminate/Foundation/Http/Middleware/ValidatePostSize.php(27): Illuminate\\Routing\\Pipeline->Illuminate\\Routing\\{closure}(Object(Illuminate\\Http\\Request))
#61 /Library/WebServer/Documents/physio/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(163): Illuminate\\Foundation\\Http\\Middleware\\ValidatePostSize->