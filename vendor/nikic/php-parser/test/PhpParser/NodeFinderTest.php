ter(
                function ($frame) {
                    foreach ($this->ignore as $ignore) {
                        if (preg_match($ignore, $frame->getFile())) {
                            return false;
                        }
                    }

                    return true;
                }
            )
            ->getArray();
    }

    /**
     * Renders the title of the exception.
     *
     * @param \Whoops\Exception\Inspector $inspector
     *
     * @return \NunoMaduro\Collision\Contracts\Writer
     */
    protected function renderTitle(Inspector $inspector): WriterContract
    {
        $exception = $inspector->getException();
        $message = $exception->getMessage();
        $class = $inspector->getExceptionName();

        $this->render("<bg=red;options=bold> $class </> : <comment>$message</>");

        return $this;
    }

    /**
     * Renders the editor containing the code that was the
     * origin of the exception.
     *
     * @param \Whoops\Exception\Frame $frame
     *
     * @return \NunoMaduro\Collision\Contracts\Writer
     */
    protected function renderEditor(Frame $frame): WriterContract
    {
        $this->render('at <fg=green>'.$frame->getFile().'</>'.':<fg=green>'.$frame->getLine().'</>');

        $content = $this->highlighter->highlight((string) $frame->getFileContents(), (int) $frame->getLine());

        $this->output->writeln($content);

        return $this;
    }

    /**
     * Renders the trace of the exception.
     *
     * @param  array $frames
     *
     * @return \NunoMaduro\Collision\Contracts\Writer
     */
    protected function renderTrace(array $frames): WriterContract
    {
        $this->render('<comment>Exception trace:</comment>');
        foreach ($frames as $i => $frame) {
            if ($i > static::VERBOSITY_NORMAL_FRAMES && $this->output->getVerbosity(
                ) < OutputInterface::VERBOSITY_VERBOSE) {
                $this->render('<info>Please use the argument <fg=red>-v</> to see more details.</info>');
                break;
            }

            $file = $frame->getFile();
            $line = $frame->getLine();
            $class = empty($frame->getClass()) ? '' : $frame->getClass().'::';
            $function = $frame->getFunction();
            $args = $this->argumentFormatter->format($frame->getArgs());
            $pos 