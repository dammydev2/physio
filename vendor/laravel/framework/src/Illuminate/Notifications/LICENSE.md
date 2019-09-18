 function runProcess(Process $process, $memory)
    {
        $process->run(function ($type, $line) {
            $this->handleWorkerOutput($type, $line);
        });

        // Once we have run the job we'll go check if the memory limit has been exceeded
        // for the script. If it has, we will kill this script so the process manager
        // will restart this with a clean slate of memory automatically on exiting.
        if ($this->memoryExceeded($memory)) {
            $this->stop();
        }
    }

    /**
     * Handle output from the worker process.
     *
     * @param  int  $type
     * @param  string  $line
     * @return void
     */
    protected function handleWorkerOutput($type, $line)
    {
        if (isset($this->outputHandler)) {
            call_user_func($this->outputHandler, $type, $line);
        }
    }

    /**
     * Determine if the memory limit has been exceeded.
     *
     * @param  int  $memoryLimit
     * @return bool
     */
    public function memoryExceeded($memoryLimit)
    {
        return (memory_get_usage(true) / 