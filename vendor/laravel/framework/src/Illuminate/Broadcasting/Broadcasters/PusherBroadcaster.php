ll on the base command instance. This specifies what can get
        // passed into these commands as "parameters" to control the execution.
        foreach ($this->getArguments() as $arguments) {
            call_user_func_array([$this, 'addArgument'], $arguments);
        }

        foreach ($this->getOptions() as $options) {
            call_user_func_array([$this, 'addOption'], $options);
        }
    }

    /**
     * Run the console command.
     *
     * @param  \Symfony\Component\Console\Input\InputInterface  $input
     * @param  \Symfony\Component\Console\Output\OutputInterface  $output
     * @return int
     */
    public function run(InputInterface $input, OutputInterface $output)
    {
        $this->output = $this->laravel->make(
            OutputStyle::class, ['input' => $input, 'output' => $output]
        );

        return parent::run(
            $this->input = $input, $this->output
        );
    }

    /**
     * Execute the console command.
     *
     * @param  \Symfony\Component\Console\Input\InputInterface  $input
     * @param  \Symfony\Component\Console\Output\OutputInterface  $output
     * @return mixed
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        return $this->laravel->call([$this, 'handle']);
    }

    /**
     * Call another console command.
     *
     * @param  string  $command
     * @param  array   $arguments
     * @return int
     */
    public function call($command, array $arguments = [])
    {
        $arguments['command'] = $command;

        return $this->getApplication()->find($command)->run(
            $this->createInputFromArguments($arguments), $this->output
        );
    }

    /**
     * Call another console command silently.
     *
     * @param  string  $command
     * @param  array   $arguments
     * @return int
     */
    public function callSilent($command, array $arguments = [])
    {
        $arguments['command'] = $command;

        return $this->getApplication()->find($command)->run(
            $this->createInputFromArguments($arguments), new NullOutput
        );
    }

    /**
     * Create an input instance from the given arguments.
     *
     * @param  array  $arguments
     * @return \Symfony\Component\Console\Input\ArrayInput
     */
    protected function createInputFromArguments(array $arguments)
    {
        return tap(new ArrayInput(array_merge($this->context(), $arguments)), function ($input) {
            if ($input->hasParameterOption(['--no-interaction'], true)) {
                $input->setInteractive(false);
            }
        });
    }

    /**
     * Get all of the context passed to the command.
     *
     * @return array
     */
    protected function context()
    {
        return collect($this->option())->only([
            'ansi',
            'no-ansi',
            'no-interaction',
            'quiet',
            'verbose',
        ])->filter()->mapWithKeys(function ($value, $key) {
            return ["--{$key}" => $value];
        })->all();
    }

    /**
     * Determine if the given argument is present.
     *
     * @param  string|int  $name
     * @return bool
     */
    public function hasArgument($name)
    {
        return $this->input->hasArgument($name);
    }

    /**
     * Get the value of a command argument.
     *
     * @param  string|null  $key
     * @return string|array|null
     */
    public function argument($key = null)
    {
        if (is_null($key)) {
            return $this->input->getArgument