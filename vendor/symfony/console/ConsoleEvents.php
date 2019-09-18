
     *
     * @throws ProcessFailedException
     *
     * @see run()
     */
    public function mustRun(OutputInterface $output, $cmd, $error = null, callable $callback = null)
    {
        $process = $this->run($output, $cmd, $error, $callback);

        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }

        return $process;
    }

    /**
     * Wraps a Process callback to add debugging output.
     *
     * @param OutputInterface $output   An OutputInterface interface
     * @param Process         $process  The Process
     * @param callable|null   $callback A PHP callable
     *
     * @return callable
     */
    public function wrapCallback(OutputInterface $output, Process $process, callable $callback = null)
    {
        if ($output instanceof ConsoleOutputInterface) {
            $output = $output->getErrorOutput();
        }

        $formatter = $this->getHelperSet()->get('debug_formatter');

        return function ($type, $buffer) use ($output, $process, $callback, $formatter) {
            $output->write($formatter->progress(spl_object_hash($process), $this->escapeString($buffer), Process::ERR === $type));

            if (null !== $callback) {
                $callback($type, $buffer);
            }
        };
    }

    private fun