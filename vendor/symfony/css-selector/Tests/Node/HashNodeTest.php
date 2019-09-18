dErrorCache = [];
    private static $silencedErrorCount = 0;
    private static $exitCode = 0;

    /**
     * Registers the error handler.
     *
     * @param self|null $handler The handler to register
     * @param bool      $replace Whether to replace or not any existing handler
     *
     * @return self The registered error handler
     */
    public static function register(self $handler = null, $replace = true)
    {
        if (null === self::$reservedMemory) {
            self::$reservedMemory = str_repeat('x', 10240);
            register_shutdown_function(__CLASS__.'::handleFatalError');
        }

        if ($handlerIsNew = null === $handler) {
            $handler = new static();
        }

        if (null === $prev = set_error_handler([$handler, 'handleError'])) {
            restore_error_handler();