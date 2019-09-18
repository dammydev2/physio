);
        }
    }

    protected function doRenderException(\Exception $e, OutputInterface $output)
    {
        do {
            $message = trim($e->getMessage());
            if ('' === $message || OutputInterface::VERBOSITY_VERBOSE <= $output->getVerbosity()) {
                $class = \get_class($e);
                $class = 'c' === $class[0] && 0 === strpos($class, "class@anonymous\0") ? get_parent_class($class).'@anonymous' : $class;
                $title = sprintf('  [%s%s]  ', $class, 0 !== ($code = $e->getCode()) ? ' ('.$code.')' : '');
                $len = Helper::strlen($title);
            } else {
                $len = 0;
            }

            if (false !== strpos($message, "class@anonymous\0")) {
                $message = preg_replace_callback('/class@anonymous\x00.*?\.php0x?[0-9a-fA-F]++/', function ($m) {
                    return \class_exists($m[0], false) ? get_parent_class($m[0]).'@anonymous' : $m[0];
                }, $message);
            }

            $width = $this->terminal->getWidth() ? $this->terminal->getWidth() - 1 : PHP_INT_MAX;
            $lines = [];
            foreach ('' !== $message ? preg_split('/\r?\n/', $message) : [] as $line) {
                foreach ($this->splitStringByWidth($line, $width - 4) as $line) {
                    // pre-format lines to get the right string length
                    $lineLength = Helper::strlen($line) + 4;
                    $lines[] = [$line, $lineLength];

                    $len = max($lineLength, $len);
                }
            }

            $messages = [];
            if (!$e instanceof ExceptionInterface || OutputInterface::VERBOSITY_VERBOSE <= $output->getVerbosity()) {
                $messages[] = sprintf('<comment>%s</comment>', OutputFormatter::escape(sprintf('In %s line %s:', basename($e->getFile()) ?: 'n/a', $e->getLine() ?: 'n/a')));
            }
            $messages[] = $emptyLine = sprintf('<error>%s</error>', str_repeat(' ', $len));
            if ('' === $message || OutputInterface::VERBOSITY_VERBOSE <= $output->getVerbosity()) {
                $messages[] = sprintf('<error>%s%s</error>', $title, str_repeat(' ', max(0, $len - Helper::strlen($title))));
            }
            foreach ($lines as $line) {
                $messages[] = sprintf('<error>  %s  %s</error>', OutputFormatter::escape($line[0]), str_repeat(' ', $len - $line[1]));
            }
            $messages[] = $emptyLine;
            $messages[] = '';

            $output->writeln($messages, OutputInterface::VERBOSITY_QUIET);

            if (OutputInterface::VERBOSITY_VERBOSE <= $output->getVerbosity()) {
                $output->writeln('<comment>Exception trace:</comment>', OutputInterface::VERBOSITY_QUIET);

                // exception related properties
                $trace = $e->getTrace();

                array_unshift($trace, [
                    'function' => '',
                    'file' => $e->getFile() ?: 'n/a',
                    'line' => $e->getLine() ?: 'n/a',
                    'args' => [],
                ]);

                for ($i = 0, $count = \count($trace); $i < $count; ++$i) {
                    $class = isset($trace[$i]['class']) ? $trace[$i]['class'] : '';
                    $type = isset($trace[$i]['type']) ? $trace[$i]['type'] : '';
                    $function = $trace[$i]['function'];
                    $file = isset($trace[$i]['file']) ? $trace[$i]['file'] : 'n/a';
                    $line = isset($trace[$i]['line']) ? $trace[$i]['line'] : 'n/a';

                    $output->writeln(sprintf(' %s%s%s() at <info>%s:%s</info>', $class, $type, $function, $file, $line), OutputInterface::VERBOSITY_QUIET);
                }

                $output->writeln('', OutputInterface::VERBOSITY_QUIET);
            }
        } while ($e = $e->getPrevious());
    }

    /**
     * Configures the input and output instances based on the user arguments and options.
     */
    protected function configureIO(InputInterface $input, OutputInterface $output)
    {
        if (true === $input->hasParameterOption(['--ansi'], true)) {
            $output->setDecorated(true);
        } elseif (true === $input->hasParameterOption(['--no-ansi'], true)) {
            $output->setDecorated(false);
        }

        if (true === $input->hasParameterOption(['--no-interaction', '-n'], true)) {
            $input->setInteractive(false);
        } elseif (\function_exists('posix_isatty')) {
            $inputStream = null;

            if ($input instanceof StreamableInputInterface) {
                $inputStream = $input->getStream();
            }

            if (!@posix_isatty($inputStream) && false === getenv('SHELL_INTERACTIVE')) {
                $input->setInteractive(false);
            }
        }

        switch ($shellVerbosity = (int) getenv('SHELL_VERBOSITY')) {
            case -1: $output->setVerbosity(OutputInterface::VERBOSITY_QUIET); break;
            case 1: $output->setVerbosity(OutputInterface::VERBOSITY_VERBOSE); break;
            case 2: $output->setVerbosity(OutputInterface::VERBOSITY_VERY_VERBOSE); break;
            case 3: $output->setVerbosity(OutputInterface::VERBOSITY_DEBUG); break;
            default: $shellVerbosity = 0; break;
        }

        if (true === $input->hasParameterOption(['--quiet', '-q'], true)) {
            $output->setVerbosity(OutputInterface::VERBOSITY_QUIET);
            $shellVerbosity = -1;
        } else {
            if ($input->hasParameterOption('-vvv', true) || $input->hasParameterOption('--verbose=3', true) || 3 === $input->getParameterOption('--verbose', false, true)) {
                $output->setVerbosity(OutputInterface::VERBOSITY_DEBUG);
                $shellVerbosity = 3;
            } elseif ($input->hasParameterOption('-vv', true) || $input->hasParameterOption('--verbose=2', true) || 2 === $input->getParameterOption('--verbose', false, true)) {
                $output->setVerbosity(OutputInterface::VERBOSITY_VERY_VERBOSE);
                $shellVerbosity = 2;
            } elseif ($input->hasParameterOption('-v', true) || $input->hasParameterOption('--verbose=1', true) || $input->hasParameterOption('--verbose', true) || $input->getParameterOption('--verbose', false, true)) {
                $output->setVerbosity(OutputInterface::VERBOSITY_VERBOSE);
                $shellVerbosity = 1;
            }
        }

        if (-1 === $shellVerbosity) {
            $input->setInteractive(false);
        }

        putenv('SHELL_VERBOSITY='.$shellVerbosity);
        $_ENV['SHELL_VERBOSITY'] = $shellVerbosity;
        $_SERVER['SHELL_VERBOSITY'] = $shellVerbosity;
    }

    /**
     * Runs the current command.
     *
     * If an event dispatcher has been attached to the application,
     * events are also dispatched during the life-cycle of the command.
     *
     * @return int 0 if everything went fine, or an error code
     */
    protected function doRunCommand(Command $command, InputInterface $input, OutputInterface $output)
    {
        foreach ($command->getHelperSet() as $helper) {
            if ($helper instanceof InputAwareInterface) {
                $helper->setInput($input);
            }
        }

        if (null === $this->dispatcher) {
            return $command->run($input, $output);
        }

        // bind before the console.command event, so the listeners have access to input options/arguments
        try {
            $command->mergeApplicationDefinition();
            $input->bind($command->getDefinition());
        } catch (ExceptionInterface $e) {
            // ignore invalid options/arguments for now, to allow the event listeners to customize the InputDefinition
        }

        $event = new ConsoleCommandEvent($command, $input, $output);
        $e = null;

        try {
            $this->dispatcher->dispatch(ConsoleEvents::COMMAND, $event);

            if ($event->commandShouldRun()) {
                $exitCode = $command->run($input, $output);
            } else {
                $exitCode = ConsoleCommandEvent::RETURN_CODE_DISABLED;
            }
        } catch (\Throwable $e) {
            $event = new ConsoleErrorEvent($input, $output, $e, $command);
            $this->dispatcher->dispatch(ConsoleEvents::ERROR, $event);
            $e = $event->getError();

            if (0 === $exitCode = $event->getExitCode()) {
                $e = null;
            }
        }

        $event = new ConsoleTerminateEvent($command, $input, $output, $exitCode);
        $this->dispatcher->dispatch(ConsoleEvents::TERMINATE, $event);

        if (null !== $e) {
            throw $e;
        }

        return $event->getExitCode();
    }

    /**
     * Gets the name of the command based on input.
     *
     * @return string The command name
     */
    protected function getCommandName(InputInterface $input)
    {
        return $this->singleCommand ? $this->defaultCommand : $input->getFirstArgument();
    }

    /**
     * Gets the default input definition.
     *
     * @return InputDefinition An InputDefinition instance
     */
    protected function getDefaultInputDefinition()
    {
        return new InputDefinition([
            new InputArgument('command', InputArgument::REQUIRED, 'The command to execute'),

            new InputOption('--help', '-h', InputOption::VALUE_NONE, 'Display this help message'),
            new InputOption('--quiet', '-q', InputOption::VALUE_NONE, 'Do not output any message'),
            new InputOption('--verbose', '-v|vv|vvv', InputOption::VALUE_NONE, 'Increase the verbosity of messages: 1 for normal output, 2 for more verbose output and 3 for debug'),
            new InputOption('--version', '-V', InputOption::VALUE_NONE, 'Display this application version'),
            new InputOption('--ansi', '', InputOption::VALUE_NONE, 'Force ANSI output'),
            new InputOption('--no-ansi', '', InputOption::VALUE_NONE, 'Disable ANSI output'),
            new InputOption('--no-interaction', '-n', InputOption::VALUE_NONE, 'Do not ask any interactive question'),
        ]);
    }

    /**
     * Gets the default commands that should always be available.
     *
     * @return Command[] An array of default Command instances
     */
    protected function getDefaultCommands()
    {
        return [new HelpCommand(), new ListCommand()];
    }

    /**
     * Gets the default helper set with the helpers that should always be available.
     *
     * @return HelperSet A HelperSet instance
     */
    protected function getDefaultHelperSet()
    {
        return new HelperSet([
            new FormatterHelper(),
            new DebugFormatterHelper(),
            new ProcessHelper(),
            new QuestionHelper(),
        ]);
    }

    /**
     * Returns abbreviated suggestions in string format.
     *
     * @param array $abbrevs Abbreviated suggestions to convert
     *
     * @return string A formatted string of abbreviated suggestions
     */
    private function getAbbreviationSuggestions($abbrevs)
    {
        return '    '.implode("\n    ", $abbrevs);
    }

    /**
     * Returns the namespace part of the command name.
     *
     * This method is not part of public API and should not be used directly.
     *
     * @param string $name  The full name of the command
     * @param string $limit The maximum number of parts of the namespace
     *
     * @return string The namespace of the command
     */
    public function extractNamespace($name, $limit = null)
    {
        $parts = explode(':', $name);
        array_pop($parts);

        return implode(':', null === $limit ? $parts : \array_slice($parts, 0, $limit));
    }

    /**
     * Finds alternative of $name among $collection,
     * if nothing is found in $collection, try in $abbrevs.
     *
     * @param string   $name       The string
     * @param iterable $collection The collection
     *
     * @return string[] A sorted array of similar string
     */
    private function findAlternatives($name, $collection)
    {
        $threshold = 1e3;
        $alternatives = [];

        $collectionParts = [];
        foreach ($collection as $item) {
            $collectionParts[$item] = explode(':', $item);
        }

        foreach (explode(':', $name) as $i => $subname) {
            foreach ($collectionParts as $collectionName => $parts) {
                $exists = isset($alternatives[$collectionName]);
                if (!isset($parts[$i]) && $exists) {
                    $alternatives[$collectionName] += $threshold;
                    continue;
                } elseif (!isset($parts[$i])) {
                    continue;
                }

                $lev = levenshtein($subname, $parts[$i]);
                if ($lev <= \strlen($subname) / 3 || '' !== $subname && false !== strpos($parts[$i], $subname)) {
                    $alternatives[$collectionName] = $exists ? $alternatives[$collectionName] + $lev : $lev;
                } elseif ($exists) {
                    $alternatives[$collectionName] += $threshold;
                }
            }
        }

        foreach ($collection as $item) {
            $lev = levenshtein($name, $item);
            if ($lev <= \strlen($name) / 3 || false !== strpos($item, $name)) {
                $alternatives[$item] = isset($alternatives[$item]) ? $alternatives[$item] - $lev : $lev;
            }
        }

        $alternatives = array_filter($alternatives, function ($lev) use ($threshold) { return $lev < 2 * $threshold; });
        ksort($alternatives, SORT_NATURAL | SORT_FLAG_CASE);

        return array_keys($alternatives);
    }

    /**
     * Sets the default Command name.
     *
     * @param string $commandName     The Command name
     * @param bool   $isSingleCommand Set to true if there is only one command in this application
     *
     * @return self
     */
    public function setDefaultCommand($commandName, $isSingleCommand = false)
    {
        $this->defaultCommand = $commandName;

        if ($isSingleCommand) {
            // Ensure the command exist
            $this->find($commandName);

            $this->singleCommand = true;
        }

        return $this;
    }

    /**
     * @internal
     */
    public function isSingleCommand()
    {
        return $this->singleCommand;
    }

    private function splitStringByWidth($string, $width)
    {
        // str_split is not suitable for multi-byte characters, we should use preg_split to get char array properly.
        // additionally, array_slice() is not enough as some character has doubled width.
        // we need a function to split string not by character count but by string width
        if (false === $encoding = mb_detect_encoding($string, null, true)) {
            return str_split($string, $width);
        }

        $utf8String = mb_convert_encoding($string, 'utf8', $encoding);
        $lines = [];
        $line = '';
        foreach (preg_split('//u', $utf8String) as $char) {
            // test if $char could be appended to current line
            if (mb_strwidth($line.$char, 'utf8') <= $width) {
                $line .= $char;
                continue;
            }
            // if not, push current line to array and make new line
            $lines[] = str_pad($line, $width);
            $line = $char;
        }

        $lines[] = \count($lines) ? str_pad($line, $width) : $line;

        mb_convert_variables($encoding, 'utf8', $lines);

        return $lines;
    }

    /**
     * Returns all namespaces of the command name.
     *
     * @param string $name The full name of the command
     *
     * @return string[] The namespaces of the command
     */
    private function extractAllNamespaces($name)
    {
        // -1 as third argument is needed to skip the command short name when exploding
        $parts = explode(':', $name, -1);
        $namespaces = [];

        foreach ($parts as $part) {
            if (\count($namespaces)) {
                $namespaces[] = end($namespaces).':'.$part;
            } else {
                $namespaces[] = $part;
            }
        }

        return $namespaces;
    }

    private function init()
    {
        if ($this->initialized) {
            return;
        }
        $this->initialized = true;

        foreach ($this->getDefaultCommands() as $command) {
            $this->add($command);
        }
    }
}
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            