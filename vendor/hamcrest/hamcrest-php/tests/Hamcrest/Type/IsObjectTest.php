InputOption::VALUE_REQUIRED, 'Custom primary key'],
            ['prefix', null, InputOption::VALUE_REQUIRED, 'Prefix for all files'],
            ['paginate', null, InputOption::VALUE_REQUIRED, 'Pagination for index.blade.php'],
            ['skip', null, InputOption::VALUE_REQUIRED, 'Skip Specific Items to Generate (migration,model,controllers,api_controller,scaffold_controller,repository,requests,api_requests,scaffold_requests,routes,api_routes,scaffold_routes,views,tests,menu,dump-autoload)'],
            ['datatables', null, InputOption::VALUE_REQUIRED, 'Override datatables settings'],
            ['views', null, InputOption::VALUE_REQUIRED, 'Specify only the views you want generated: index,create,edit,show'],
            ['relations', null, InputOption::VALUE_NONE, 'Specify if you want to pass relationships for fields'],
            ['softDelete', null, InputOption::VALUE_NONE, 'Soft De