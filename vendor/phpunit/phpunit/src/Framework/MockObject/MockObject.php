=> null,
        'stderr'                    => null,
        'stop-on-defect'            => null,
        'stop-on-error'             => null,
        'stop-on-failure'           => null,
        'stop-on-warning'           => null,
        'stop-on-incomplete'        => null,
        'stop-on-risky'             => null,
        'stop-on-skipped'           => null,
        'fail-on-warning'           => null,
        'fail-on-risky'             => null,
        'strict-coverage'           => null,
        'disable-coverage-ignore'   => null,
        'strict-global-state'       => null,
        'teamcity'                  => null,
        'testdox'                   => null,
        'testdox-group='            => null,
        'testdox-exclude-group='    => null,
        'testdox-html='             => null,
        'testdox-text='             => null,
        'testdox-xml='              => null,
        'test-suffix='              => null,
        'testsuite='                => null,
        'verbose'                   => null,
        'version'                   => null,
        'whitelist='                => null,
        'dump-xdebug-filter='       => null,
    ];

    /**
     * @var bool
     */
    private $versionStringPrinted = false;

    /**
     * @throws \RuntimeException
     * @throws \PHPUnit\Framework\Exception
     * @throws \InvalidArgumentException
     */
    public static function main(bool $exit = true): int
    {
        $command = new static;

        return $command->run($_SERVER['argv'], $exit);
    }

    /**
     * @throws \Ru