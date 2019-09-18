'Symfony\Component\VarDumper\Caster\DOMCaster', 'castText'],
        'DOMTypeinfo' => ['Symfony\Component\VarDumper\Caster\DOMCaster', 'castTypeinfo'],
        'DOMDomError' => ['Symfony\Component\VarDumper\Caster\DOMCaster', 'castDomError'],
        'DOMLocator' => ['Symfony\Component\VarDumper\Caster\DOMCaster', 'castLocator'],
        'DOMDocumentType' => ['Symfony\Component\VarDumper\Caster\DOMCaster', 'castDocumentType'],
        'DOMNotation' => ['Symfony\Component\VarDumper\Caster\DOMCaster', 'castNotation'],
        'DOMEntity' => ['Symfony\Component\VarDumper\Caster\DOMCaster', 'castEntity'],
        'DOMProcessingInstruction' => ['Symfony\Component\VarDumper\Caster\DOMCaster', 'castProcessingInstruction'],
        'DOMXPath' => ['Symfony\Component\VarDumper\Caster\DOMCaster', 'castXPath'],

        'XmlReader' => ['Symfony\Component\VarDumper\Caster\XmlReaderCaster', 'castXmlReader'],

        'ErrorException' => ['Symfony\Component\VarDumper\Caster\ExceptionCaster', 'castErrorException'],
        'Exception' => ['Symfony\Component\VarDumper\Caster\ExceptionCaster', 'castException'],
        'Error' => ['Symfony\Component\VarDumper\Caster\ExceptionCaster', 'castError'],
        'Symfony\Component\DependencyInjection\ContainerInterface' => ['Symfony\Component\VarDumper\Caster\StubCaster', 'cutInternals'],
        'Symfony\Component\HttpFoundation\Request' => ['Symfony\Component\VarDumper\Caster\SymfonyCaster', 'castRequest'],
        'Symfony\Component\VarDumper\Exception\ThrowingCasterException' => ['Symfony\Component\VarDumper\Caster\ExceptionCaster', 'castThrowingCasterException'],
        'Symfony\Component\VarDumper\Caster\TraceStub' => ['Symfony\Component\VarDumper\Caster\ExceptionCaster', 'castTraceStub'],
        'Symfony\Component\VarDumper\Caster\FrameStub' => ['Symfony\Component\VarDumper\Caster\ExceptionCaster', 'castFrameStub'],
        'Symfony\Component\Debug\Exception\SilencedErrorContext' => ['Symfony\Component\VarDumper\Caster\ExceptionCaster', 'castSilencedErrorContext'],

        'ProxyManager\Proxy\ProxyInterface' => ['Symfony\Component\VarDumper\Caster\ProxyManagerCaster', 'castProxy'],
        'PHPUnit_Framework_MockObject_MockObject' => ['Symfony\Component\VarDumper\Caster\StubCaster', 'cutInternals'],
        'Prophecy\Prophecy\ProphecySubjectInterface' => ['Symfony\Component\VarDumper\Caster\StubCaster', 'cutInternals'],
        'Mockery\MockInterface' => ['Symfony\Component\VarDumper\Caster\StubCaster', 'cutInternals'],

        'PDO' => ['Symfony\Component\VarDumper\Caster\PdoCaster', 'castPdo'],
        'PDOStatement' => ['Symfony\Component\VarDumper\Caster\PdoCaster', 'castPdoStatement'],

        'AMQPConnection' => ['Symfony\Component\VarDumper\Caster\AmqpCaster', 'castConnection'],
        'AMQPChannel' => ['Symfony\Component\VarDumper\Caster\AmqpCaster', 'castChannel'],
        'AMQPQueue' => ['Symfony\Component\VarDumper\Caster\AmqpCaster', 'castQueue'],
        'AMQPExchange' => ['Symfony\Component\VarDumper\Caster\AmqpCaster', 'castExchange'],
        'AMQPEnvelope' => ['Symfony\Component\VarDumper\Caster\AmqpCaster', 'castEnvelope'],

        'ArrayObject' => ['Symfony\Component\VarDumper\Caster\SplCaster', 'castArrayObject'],
        'ArrayIterator' => ['Symfony\Component\VarDumper\Caster\SplCaster', 'castArrayIterator'],
        'SplDoublyLinkedList' => ['Symfony\Component\VarDumper\Caster\SplCaster', 'castDoublyLinkedList'],
        'SplFileInfo' => ['Symfony\Component\VarDumper\Caster\SplCaster', 'castFileInfo'],
        'SplFileObject' => ['Symfony\Component\VarDumper\Caster\SplCaster', 'castFileObject'],
        'SplFixedArray' => ['Symfony\Component\VarDumper\Caster\SplCaster', 'castFixedArray'],
        'SplHeap' => ['Symfony\Component\VarDumper\Caster\SplCaster', 'castHeap'],
        'SplObjectStorage' => ['Symfony\Component\VarDumper\Caster\SplCaster', 'castObjectStorage'],
        'SplPriorityQueue' => ['Symfony\Component\VarDumper\Caster\SplCaster', 'castHeap'],
        'OuterIterator' => ['Symfony\Component\VarDumper\Caster\SplCaster', 'castOuterIterator'],

        'Redis' => ['Symfony\Component\VarDumper\Caster\RedisCaster', 'castRedis'],
        'RedisArray' => ['Symfony\Component\VarDumper\Caster\RedisCaster', 'castRedisArray'],
        'RedisCluster' => ['Symfony\Component\VarDumper\Caster\RedisCaster', 'castRedisCluster'],

        'DateTimeInterface' => ['Symfony\Component\VarDumper\Caster\DateCaster', 'castDateTime'],
        'DateInterval' => ['Symfony\Component\VarDumper\Caster\DateCaster', 'castInterval'],
        'DateTimeZone' => ['Symfony\Component\VarDumper\Caster\DateCaster', 'castTimeZone'],
        'DatePeriod' => ['Symfony\Component\VarDumper\Caster\DateCaster', 'castPeriod'],

        'GMP' => ['Symfony\Component\VarDumper\Caster\GmpCaster', 'castGmp'],

        'MessageFormatter' => ['Symfony\Component\VarDumper\Caster\IntlCaster', 'castMessageFormatter'],
        'NumberFormatter' => ['Symfony\Component\VarDumper\Caster\IntlCaster', 'castNumberFormatter'],
        'IntlTimeZone' => ['Symfony\Component\VarDumper\Caster\IntlCaster', 'castIntlTimeZone'],
        'IntlCalendar' => ['Symfony\Component\VarDumper\Caster\IntlCaster', 'castIntlCalendar'],
        'IntlDateFormatter' => ['Symfony\Component\VarDumper\Caster\IntlCaster', 'castIntlDateFormatter'],

        'Memcached' => ['Symfony\Component\VarDumper\Caster\MemcachedCaster', 'castMemcached'],

        ':curl' => ['Symfony\Component\VarDumper\Caster\ResourceCaster', 'castCurl'],
        ':dba' => ['Symfony\Component\VarDumper\Caster\ResourceCaster', 'castDba'],
        ':dba persistent' => ['Symfony\Component\VarDumper\Caster\ResourceCaster', 'castDba'],
        ':gd' => ['Symfony\Component\VarDumper\Caster\ResourceCaster', 'castGd'],
        ':mysql link' => ['Symfony\Component\VarDumper\Caster\ResourceCaster', 'castMysqlLink'],
        ':pgsql large object' => ['Symfony\Component\VarDumper\Caster\PgSqlCaster', 'castLargeObject'],
        ':pgsql link' => ['Symfony\Component\VarDumper\Caster\PgSqlCaster', 'castLink'],
        ':pgsql link persistent' => ['Symfony\Component\VarDumper\Caster\PgSqlCaster', 'castLink'],
        ':pgsql result' => ['Symfony\Component\VarDumper\Caster\PgSqlCaster', 'castResult'],
        ':process' => ['Symfony\Component\VarDumper\Caster\ResourceCaster', 'castProcess'],
        ':stream' => ['Symfony\Component\VarDumper\Caster\ResourceCaster', 'castStream'],
        ':persistent stream' => ['Symfony\Component\VarDumper\Caster\ResourceCaster', 'castStream'],
        ':stream-context' => ['Symfony\Component\VarDumper\Caster\ResourceCaster', 'castStreamContext'],
        ':xml' => ['Symfony\Component\VarDumper\Caster\XmlResourceCaster', 'castXml'],
    ];

    protected $maxItems = 2500;
    protected $maxString = -1;
    protected $minDepth = 1;

    private $casters = [];
    private $prevErrorHandler;
    private $classInfo = [];
    private $filter = 0;

    /**
     * @param callable[]|null $casters A map of casters
     *
     * @see addCasters
     */
    public function __construct(array $casters = null)
    {
        if (null === $casters) {
            $casters = static::$defaultCasters;
        }
        $this->addCasters($casters);
    }

    /**
     * Adds casters for resources and objects.
     *
     * Maps resources or objects types to a callback.
     * Types are in the key, with a callable caster for value.
     * Resource types are to be prefixed with a `:`,
     * see e.g. static::$defaultCasters.
     *
     * @param callable[] $casters A map of casters
     */
    public function addCasters(array $casters)
    {
        foreach ($casters as $type => $callback) {
            $this->casters[strtolower($type)][] = \is_string($callback) && false !== strpos($callback, '::') ? explode('::', $callback, 2) : $callback;
        }
    }

    /**
     * Sets the maximum number of items to clone past the minimum depth in nested structures.
     *
     * @param int $maxItems
     */
    public function setMaxItems($maxItems)
    {
        $this->maxItems = (int) $maxItems;
    }

    /**
     * Sets the maximum cloned length for strings.
     *
     * @param int $maxString
     */
    public function setMaxString($max