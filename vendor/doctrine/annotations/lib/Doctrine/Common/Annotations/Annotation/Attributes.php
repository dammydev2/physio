ager
     */
    protected $_schemaManager;

    /**
     * The used DBAL driver.
     *
     * @var Driver
     */
    protected $_driver;

    /**
     * Flag that indicates whether the current transaction is marked for rollback only.
     *
     * @var bool
     */
    private $isRollbackOnly = false;

    /** @var int */
    protected $defaultFetchMode = FetchMode::ASSOCIATIVE;

    /**
     * Initializes a new instance of the Connection class.
     *
     * @param mixed[]            $params       The connection parameters.
     * @param Driver             $driver       The driver to use.
     * @param Configuration|null $config       The configuration, optional.
     * @param EventManager|null  $eventManager The event manager, optional.
     *
     * @throws DBALException
     */
    public function __construct(
        array $params,
        Driver $driver,
        ?Configuration $config = null,
        ?EventManager $eventManager = null
    ) {
        $this->_driver = $driver;
        $this->params  = $params;

        if (isset($params['pdo'])) {
            $this->_conn       = $params['pdo'];
            $this->isConnected = true;
            unset($this->params['pdo']);
        }

        if (isset($params['platform'])) {
            if (! $params['platform'] instanceof Platforms\AbstractPlatform) {
                throw DBALException::invalidPlatformT