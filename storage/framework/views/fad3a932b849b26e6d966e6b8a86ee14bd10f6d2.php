Library/WebServer/Documents/physio/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(163): Illuminate\\View\\Middleware\\ShareErrorsFromSession->handle(Object(Illuminate\\Http\\Request), Object(Closure))
#38 /Library/WebServer/Documents/physio/vendor/laravel/framework/src/Illuminate/Routing/Pipeline.php(53): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(Illuminate\\Http\\Request))
#39 /Library/WebServer/Documents/physio/vendor/laravel/framework/src/Illuminate/Session/Middleware/StartSession.php(56): Illuminate\\Routing\\Pipeline->Illuminate\\Routing\\{closure}(Object(Illuminate\\Http\\Request))
#40 /Library/WebServer/Documents/physio/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(163): Illuminate\\Session\\Middleware\\StartSession->handle(Object(Illuminate\\Http\\Request), Object(Closure))
#41 /Library/WebServer/Documents/physio/vendor/laravel/framework/src/Illuminate/Routing/Pipeline.php(53): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(Illuminate\\Http\\Request))
#42 /Library/WebServer/Documents/physio/vendor/laravel/framework/src/Illuminate/Cookie/Middleware/AddQueuedCookiesToResponse.php(37): Illuminate\\Routing\\Pipeline->Illuminate\\Routing\\{closure}(Object(Illuminate\\Http\\Request))
#43 /Library/WebServer/Documents/physio/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(163): Illuminate\\Cookie\\Middleware\\AddQueuedCookiesToResponse->handle(Object(Illuminate\\Http\\Request), Object(Closure))
#44 /Library/WebServer/Documents/physio/vendor/laravel/framework/src/Illuminate/Routing/Pipeline.php(53): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(Illuminate\\Http\\Request))
#45 /Library/WebServer/Documents/physio/vendor/laravel/framework/src/Illuminate/Cookie/Middleware/EncryptCookies.php(66): Illuminate\\Routing\\Pipeline->Illuminate\\Routing\\{closure}(Object(Illuminate\\Http\\Request))
#46 /Library/WebServer/Documents/physio/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(163): Illuminate\\Cookie\\Middleware\\EncryptCookies->handle(Object(Illuminate\\Http\\Request), Object(Closure))
#47 /Library/WebServer/Documents/physio/vendor/laravel/framework/src/Illuminate/Routing/Pipeline.php(53): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(Illuminate\\Http\\Request))
#48 /Library/WebServer/Documents/physio/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(104): Illuminate\\Routing\\Pipeline->Illuminate\\Routing\\{closure}(Object(Illuminate\\Http\\Request))
#49 /Library/WebServer/Documents/physio/vendor/laravel/framework/src/Illuminate/Routing/Router.php(682): Illuminate\\Pipeline\\Pipeline->then(Object(Closure))
#50 /Library/WebServer/Documents/physio/vendor/laravel/framework/src/Illuminate/Routing/Router.php(657): Illuminate\\Routing\\Router->runRouteWithinStack(Object(Illuminate\\Routing\\Route), Object(Illuminate\\Http\\Request))
#51 /Library/WebServer/Documents/physio/vendor/laravel/framework/src/Illuminate/Routing/Router.php(623): Illuminate\\Routing\\Router->runRoute(Object(Illuminate\\Http\\Request), Object(Illuminate\\Routing\\Route))
#52 /Library/WebServer/Documents/physio/vendor/laravel/framework/src/Illuminate/Routing/Router.php(612): Illuminate\\Routing\\Router->dispatchToRoute(Object(Illuminate\\Http\\Request))
#53 /Library/WebServer/Documents/physio/vendor/laravel/framework/src/Illuminate/Foundation/Http/Kernel.php(176): Illuminate\\Routing\\Router->dispatch(Object(Illuminate\\Http\\Request))
#54 /Library/WebServer/Documents/physio/vendor/laravel/framework/src/Illuminate/Routing/Pipeline.php(30): Illuminate\\Foundation\\Http\\Kernel->Illuminate\\Foundation\\Http\\{closure}(Object(Illuminate\\Http\\Request))
#55 /Library/WebServer/Documents/physio/vendor/fideloper/proxy/src/TrustProxies.php(57): Illuminate\\Routing\\Pipeline->Illuminate\\Routing\\{closure}(Object(Illuminate\\Http\\Request))
#56 /Library/WebServer/Documents/physio/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(163): Fideloper\\Proxy\\TrustProxies->handle(Object(Illuminate\\Http\\Request), Object(Closure))
#57 /Library/WebServer/Documents/physio/vendor/laravel/framework/src/Illuminate/Routing/Pipeline.php(53): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(Illuminate\\Http\\Request))
#58 /Library/WebServer/Documents/physio/vendor/laravel/framework/src/Illuminate/Foundation/Http/Middleware/TransformsRequest.php(21): Illuminate\\Routing\\Pipeline->Illuminate\\Routing\\{closure}(Object(Illuminate\\Http\\Request))
#59 /Library/WebServer/Documents/physio/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(163): Illuminate\\Foundation\\Http\\Middleware\\TransformsRequest->handle(Object(Illuminate\\Http\\Request), Object(Closure))
#60 /Library/WebServer/Documents/physio/vendor/laravel/framework/src/Illuminate/Routing/Pipeline.php(53): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(Illuminate\\Http\\Request))
#61 /Library/WebServer/Documents/physio/vendor/laravel/framework/src/Illuminate/Foundation/Http/Middleware/TransformsRequest.php(21): Illuminate\\Routing\\Pipeline->Illuminate\\Routing\\{closure}(Object(Illuminate\\Http\\Request))
#62 /Library/WebServer/Documents/physio/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(163): Illuminate\\Foundation\\Http\\Middleware\\TransformsRequest->handle(Object(Illuminate\\Http\\Request), Object(Closure))
#63 /Library/WebServer/Documents/physio/vendor/laravel/framework/src/Illuminate/Routing/Pipeline.php(53): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(Illuminate\\Http\\Request))
#64 /Library/WebServer/Documents/physio/vendor/laravel/framework/src/Illuminate/Foundation/Http/Middleware/ValidatePostSize.php(27): Illuminate\\Routing\\Pipeline->Illuminate\\Routing\\{closure}(Object(Illuminate\\Http\\Request))
#65 /Li