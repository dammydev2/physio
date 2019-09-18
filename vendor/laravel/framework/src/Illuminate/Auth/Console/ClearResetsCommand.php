<?php

namespace Illuminate\Cache;

use RuntimeException;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use Aws\DynamoDb\DynamoDbClient;
use Illuminate\Contracts\Cache\Store;
use Illuminate\Support\InteractsWithTime;
use Illuminate\Contracts\Cache\LockProvider;
use Aws\DynamoDb\Exception\DynamoDbException;

class DynamoDbStore implements Store, LockProvider
{
    use InteractsWithTime;

    /**
     * The DynamoDB client instance.
     *
     * @var \Aws\DynamoDb\DynamoDbClient
     */
    protected $dynamo;

    /**
     * The table name.
     *
     * @var string
     */
    protected $table;

    /**
     * The name of the attribute that should hold the key.
     *
     * @var string
     */
    protected $keyAttribu