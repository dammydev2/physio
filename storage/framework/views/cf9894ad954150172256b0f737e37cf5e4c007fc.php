ate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(Illuminate\\Http\\Request))
#66 /Library/WebServer/Documents/physio/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(104): Illuminate\\Routing\\Pipeline->Illuminate\\Routing\\{closure}(Object(Illuminate\\Http\\Request))
#67 /Library/WebServer/Documents/physio/vendor/laravel/framework/src/Illuminate/Foundation/Http/Kernel.php(151): Illuminate\\Pipeline\\Pipeline->then(Object(Closure))
#68 /Library/WebServer/Documents/physio/vendor/laravel/framework/src/Illuminate/Foundation/Http/Kernel.php(116): Illuminate\\Foundation\\Http\\Kernel->sendRequestThroughRouter(Object(Illuminate\\Http\\Request))
#69 /Library/WebServer/Documents/physio/public/index.php(55): Illuminate\\Foundation\\Http\\Kernel->handle(Object(Illuminate\\Http\\Request))
#70 /Library/WebServer/Documents/physio/server.php(21): require_once('/Library/WebSer...')
#71 {main}
"} 
[2019-05-25 07:59:38] local.ERROR: SQLSTATE[23000]: Integrity constraint violation: 1048 Column 'bp' cannot be null (SQL: insert into `ort_page2s` (`sys_num`, `rec`, `bp`, `heart`, `respiration`, `complaint`, `history`, `symptoms`, `onset`, `pain`, `des`, `updated_at`, `created_at`) values (PHYSIO/2019/11, 2418176280, ?, 120, 99, ?, Present bruise, The current symptoms, the onset, the pain, Sharp, 2019-05-25 07:59:38, 2019-05-25 07:59:38)) {"userId":4,"exception":"[object] (Il