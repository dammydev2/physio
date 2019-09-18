nstants
     */
    private $lockMode = self::LOCK_TRANSACTIONAL;

    /**
     * It's an array to support multiple reads before closing which is manual, non-standard usage.
     *
     * @var \PDOStatement[] An array of statements to release advisory locks
     */
    private $unlockStatements = [];

    /**
     * @var bool True when the current session exists but expired according to session.gc_maxlifetime
     */
    private $sessionExpired = false;

    /**
     * @var bool Whether a transaction is active
     */
    private $inTransaction = false;

    /**
     * @var bool Whether gc() has been called
     */
    private $gcCalled = false;

    /**
     * You can either pass an existing database connection as PDO instance or
     * pass a DSN string that will be used to lazy-connect to the database
     * when the session is actually used. Furthermore it's possible to pass null
     * which will then use the session.save_path ini setting as PDO DSN parameter.
     *
     * List of available options:
     *  * db_table: The name of the table [default: sessions]
     *  * db_id_col: The column where to store the session id [default: sess_id]
     *  * db_data_col: The column where to store the session data [default: sess_data]
     *  * db_lifetime_col: The column where to store the lifetime [default: sess_lifetime]
     *  * db_time_col: The column where to store the timestamp [default: sess_time]
     *  * db_username: The username when lazy-connect [default: '']
     *  * db_password: The password when lazy-connect [default: '']
     *  * db_connection_options: An array of driver-specific connection options [default: []]
     *  * lock_mode: The strategy for locking, see constants [default: LOCK_TRANSACTIONAL]
     *
     * @param \PDO|string|null $pdoOrDsn A \PDO instance or DSN string or URL string or null
     * @param array            $options  An associative array of options
     *
     * @throws \InvalidArgumentException When PDO error mode is not PDO::ERRMODE_EXCEPTION
     */
    public function __construct($pdoOrDsn = null, array $options = [])
    {
        if ($pdoOrDsn instanceof \PDO) {
            if (\PDO::ERRMODE_EXCEPTION !== $pdoOrDsn->getAttribute(\PDO::ATTR_ERRMODE)) {
                throw new \InvalidArgumentException(sprintf('"%s" requires PDO error mode attribute be set to throw Exceptions (i.e. $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION))', __CLASS__));
            }

            $this->pdo = $pdoOrDsn;
            $this->driver = $this->pdo->getAttribute(\PDO::ATTR_DRIVER_NAME);
        } elseif (\is_string($pdoOrDsn) && false !== strpos($pdoOrDsn, '://')) {
            $this->dsn = $this->buildDsnFromUrl($pdoOrDsn);
        } else {
            $this->dsn = $pdoOrDsn;
        }

        $this->table = isset($options['db_table']) ? $options['db_table'] : $this->table;
        $this->idCol = isset($options['db_id_col']) ? $options['db_id_col'] : $this->idCol;
        $this->dataCol = isset($options['db_data_col']) ? $options['db_data_col'] : $this->dataCol;
        $this->lifetimeCol = isset($options['db_lifetime_col']) ? $options['db_lifetime_col'] : $this->lifetimeCol;
        $this->timeCol = isset($options['db_time_col']) ? $options['db_time_col'] : $this->timeCol;
        $this->username = isset($options['db_username']) ? $option