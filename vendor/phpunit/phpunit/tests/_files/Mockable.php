  =>null,

        //Allow caching for all available methods, except
        'except'=>null
    ],
],
```

It is possible to override these settings directly in the repository.

```php
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Contracts\CacheableInterface;
use Prettus\Repository\Traits\CacheableRepository;

class PostRepository extends BaseRepository implements CacheableInterface {

    // Setting the lifetime of the cache to a repository specifically
    protected $cacheMinutes = 90;

    protected $cacheOnly = ['all', ...];
    //or
    protected $cacheExcept = ['find', ...];

    use CacheableRepository;

    ...
}
```

The cacheable methods are : all, paginate, find, findByField, f