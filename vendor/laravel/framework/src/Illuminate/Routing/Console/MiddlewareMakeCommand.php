 used for all created dates.
     *
     * @var string
     */
    const DEFAULT_CLASS_NAME = Carbon::class;

    /**
     * The type (class) of dates that should be created.
     *
     * @var string
     */
    protected static $dateClass;

    /**
     * This callable may be used to intercept date creation.
     *
     * @var callable
     */
    protected static $callable;

    /**
     * The Carbon factory that should be used when creating dates.
     *
     * @var object
     */
    protected static $factory;

    /**
     * Use the given handler when generating dates (class name, callable, or factory).
     *
     * @param  mixed  $handler
     *
     * @throws \InvalidArgumentException
     */
    public static function use($handler)
    {
        if (is_callable($handler) && is_object($handler)) {
            return static::useCallable($handler);
        } elseif (is_string($handler)) {
            return static::useClass($handler);
        