tex name for the scheduled command.
     *
     * @return string
     */
    public function mutexName()
    {
        return 'framework'.DIRECTORY_SEPARATOR.'schedule-'.sha1($this->expression.$this->command);
    }

    /**
     * Run the command in the foreground.
     *
     * @param  \Illuminate\Contracts\Container\Container  $container
     * @return void
     */
    protected function runCommandInForeground(Container $container)
    {
        $this->callBeforeCallbacks($container);

        $this->exitCode = Process::fromShellCommandline($this->buildCommand(), base_path(), null, null, null)->run();

        $this->callAfterCallbacks($container);
    }

    /**
     * Run the command in the background.
     *
     * @param  \Illuminate\Contracts\Container\Container  $container
     * @return void
     */
    protected function runCommandInBackground(Container $container)
    {
        $this->callBeforeCallbacks($container);

        Process::fromShellCommandline($this->buildCommand(), base_path(), null, null, null)->run();
    }

    /**
     * Call all of the "before" callbacks for the event.
     *
     * @param  \Illuminate\Contracts\Container\Container  $container
     * @return void
     */
    public function callBeforeCallbacks(Container $container)
    {
        foreach ($this->beforeCallbacks as $callback) {
            $container->call($callback);
        }
    }

    /**
     * Call all of the "after" callbacks for the event.
     *
     * @param  \Illuminate\Contracts\Container\Container  $container
     * @return void
     */
    public function callAfterCallbacks(Container $container)
    {
        foreach ($this->afterCallbacks as $callback) {
            $container->call($callback);
        }
    }

    /**
     * Build the command string.
     *
     * @return string
     */
    public function buildCommand()
    {
        return (new CommandBuilder)->buildCommand($this);
    }

    /**
     * Determine if the given event should run based on the Cron expression.
     *
     * @param  \Illuminate\Contracts\Foundation\Application  $app
     * @return bool
     */
    public function isDue($app)
    {
        if (! $this->runsInMaintenanceMode() && $app->isDownForMaintenance()) {
            return false;
        }

        return $this->expressionPasses() &&
               $this->runsInEnvironment($app->environment());
    }

    /**
     * Determine if the event runs in maintenance mode.
     *
     * @return bool
     */
    public function runsInMaintenanceMode()
    {
        return $this->evenInMaintenanceMode;
    }

    /**
     * Determine if the Cron expression passes.
     *
     * @return bool
     */
    protected function expressionPasses()
    {
        $date = Carbon::now();

        if ($this->timezone) {
            $date->setTimezone($this->timezone);
        }

        return CronExpression::factory($this->expression)->isDue($date->toDateTimeString());
    }

    /**
     * Determine if the event runs in the given environment.
     *
     * @param  string  $environment
     * @return bool
     */
    public function runsInEnvironment($environment)
    {
        return empty($this->environments) || in_array($environment, $this->environments);
    }

    /**
     * Determine if the filters pass for the event.
     *
     * @param  \Illuminate\Contracts\Foundation\Application  $app
     * @return bool
     */
    public function filtersPass($app)
    {
        foreach ($this->filters as $callback) {
            if (! $app->call($callback)) {
                return false;
            }
        }

        foreach ($this->rejects as $callback) {
            if ($app->call($callback)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Ensure that the output is stored on disk in a log file.
     *
     * @return $this
     */
    public function storeOutput()
    {
        $this->ensureOutputIsBeingCaptured();

        return $this;
    }

    /**
     * Send the output of the command to a given location.
     *
     * @param  string  $location
     * @param  bool  $append
     * @return $this
     */
    public function sendOutputTo($location, $append = false)
    {
        $this->output = $location;

        $this->shouldAppendOutput = $append;

        return $this;
    }

    /**
     * Append the output of the command to a given location.
     *
     * @param  string  $location
     * @return $this
     */
    public function appendOutputTo($location)
    {
        return $this->sendOutputTo($location, true);
    }

    /**
     * E-mail the results of the scheduled operation.
     *
     * @param  array|mixed  $addresses
     * @param  bool  $onlyIfOutputExists
     * @return $this
     *
     * @throws \LogicException
     */
    public function emailOutputTo($addresses, $onlyIfOutputExists = false)
    {
        $this->ensureOutputIsBeingCaptured();

        $addresses = Arr::wrap($addresses);

        return $this->then(function (Mailer $mailer) use ($addresses, $onlyIfOutputExists) {
            $this->emailOutput($mailer, $addresses, $onlyIfOutputExists);
        });
    }

    /**
     * E-mail the results of the scheduled operation if it produces output.
     *
     * @param  array|mixed  $addresses
     * @return $this
     *
     * @throws \LogicException
     */
    public function emailWrittenOutputTo($addresses)
    {
        return $this->emailOutputTo($addresses, true);
    }

    /**
     * E-mail the results of the scheduled operation if it fails.
     *
     * @param  array|mixed  $addresses
     * @return $this
     */
    public function emailOutputOnFailure($addresses)
    {
        $this->ensureOutputIsBeingCaptured();

        $addresses = Arr::wrap($addresses);

        return $this->onFailure(function (Mailer $mailer) use ($addresses) {
            $this->emailOutput($mailer, $addresses, false);
        });
    }

    /**
     * Ensure that the command output is being captured.
     *
     * @return void
     */
    protected function ensureOutputIsBeingCaptured()
    {
        if (is_null($this->output) || $this->output == $this->getDefaultOutput()) {
            $this->sendOutputTo(storage_path('logs/schedule-'.sha1($this->mutexName()).'.log'));
        }
    }

    /**
     * E-mail the output of the event to the recipients.
     *
     * @param  \Illuminate\Contracts\Mail\Mailer  $mailer
     * @param  array  $addresses
     * @param  bool  $onlyIfOutputExists
     * @return void
     */
    protected function emailOutput(Mailer $mailer, $addresses, $onlyIfOutputExists = false)
    {
        $text = file_exists($this->output) ? file_get_contents($this->output) : '';

        if ($onlyIfOutputExists && empty($text)) {
            return;
        }

        $mailer->raw($text, function ($m) use ($addresses) {
            $m->to($addresses)->subject($this->getEmailSubject());
        });
    }

    /**
     * Get the e-mail subject line for output results.
     *
     * @return string
     */
    protected function getEmailSubject()
    {
        if ($this->description) {
            return $this->description;
        }

        return "Scheduled Job Output For [{$this->command}]";
    }

    /**
     * Register a callback to ping a given URL before the job runs.
     *
     * @param  string  $url
     * @return $this
     */
    public function pingBefore($url)
    {
        return $this->before(function () use ($url) {
            (new HttpClient)->get($url);
        });
    }

    /**
     * Register a callback to ping a given URL 