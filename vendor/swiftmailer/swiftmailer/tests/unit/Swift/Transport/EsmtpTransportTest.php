<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\Console;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Command\HelpCommand;
use Symfony\Component\Console\Command\ListCommand;
use Symfony\Component\Console\CommandLoader\CommandLoaderInterface;
use Symfony\Component\Console\Event\ConsoleCommandEvent;
use Symfony\Component\Console\Event\ConsoleErrorEvent;
use Symfony\Component\Console\Event\ConsoleTerminateEvent;
use Symfony\Component\Console\Exception\CommandNotFoundException;
use Symfony\Component\Console\Exception\ExceptionInterface;
use Symfony\Component\Console\Exception\LogicException;
use Symfony\Component\Console\Exception\NamespaceNotFoundException;
use Symfony\Component\Console\Formatter\OutputFormatter;
use Symfony\Component\Console\Helper\DebugFormatterHelper;
use Symfony\Component\Console\Helper\FormatterHelper;
use Symfony\Component\Console\Helper\Helper;
use Symfony\Component\Console\Helper\HelperSet;
use Symfony\Component\Console\Helper\ProcessHelper;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputAwareInterface;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\StreamableInputInterface;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Output\ConsoleOutputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Debug\ErrorHandler;
use Symfony\Component\Debug\Exception\FatalThrowableError;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * An Application is the container for a collection of commands.
 *
 * It is the main entry point of a Console application.
 *
 * This class is optimized for a standard CLI environment.
 *
 * Usage:
 *
 *     $app = new Application('myapp', '1.0 (stable)');
 *     $app->add(new SimpleCommand());
 *     $app->run();
 *
 * @author Fabien Potencier <fabien@symfony.com>
 */
class Application
{
    private $commands = [];
    private $wantHelps = false;
    private $runningCommand;
    private $name;
    private $version;
    private $commandLoader;
    private $catchExceptions = true;
    private $autoExit = true;
    private $definition;
    private $helperSet;
    private $dispatcher;
    private $terminal;
    private $defaultCommand;
    private $singleCommand = false;
    private $initialized;

    /**
     * @param string $name    The name of the application
     * @param string $version The version of the application
     */
    public function __construct(string $name = 'UNKNOWN', string $version = 'UNKNOWN')
    {
        $this->name = $name;
        $this->version = $version;
        $this->terminal = new Terminal();
        $this->defaultCommand = 'list';
    }

    public function setDispatcher(EventDispatcherInterface $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    public function setCommandLoader(CommandLoaderInterface $commandLoader)
    {
        $this->commandLoader = $commandLoader;
    }

    /**
     * Runs the current application.
     *
     * @return int 0 if everything went fine, or an error code
     *
     * @throws \Exception When running fails. Bypass this when {@link setCatchExceptions()}.
     */
    public function run(InputInterface $input = null, OutputInterface $output = null)
    {
        putenv('LINES='.$this->terminal->getHeight());
        putenv('COLUMNS='.$this->terminal->getWidth());

        if (null === $input) {
            $input = new ArgvInput();
        }

        if (null === $output) {
            $output = new ConsoleOutput();
        }

        $renderException = function ($e) use ($output) {
            if (!$e instanceof \Exception) {
                $e = class_exists(FatalThrowableError::class) ? new FatalThrowableError($e) : new \ErrorException($e->getMessage(), $e->getCode(), E_ERROR, $e->getFile(), $e->getLine());
            }
            if ($output instanceof ConsoleOutputInterface) {
                $this->renderException($e, $output->getErrorOutput());
            } else {
                $this->renderException($e, $output);
            }
        };
        if ($phpHandler = set_exception_handler($renderException)) {
            restore_exception_handler();
            if (!\is_array($phpHandler) || !$phpHandler[0] instanceof ErrorHandler) {
                $debugHandler = true;
            } elseif ($debugHandler = $phpHandler[0]->setExceptionHandler($renderException)) {
                $phpHandler[0]->setExceptionHandler($debugHandler);
            }
        }

        $this->configureIO($input, $output);

        try {
            $exitCode = $this->doRun($input, $output);
        } catch (\Exception $e) {
            if (!$this->catchExceptions) {
                throw $e;
            }

            $renderException($e);

            $exitCode = $e->getCode();
            if (is_numeric($exitCode)) {
                $exitCode = (int) $exitCode;
                if (0 === $exitCode) {
                    $exitCode = 1;
                }
            } else {
                $exitCode = 1;
            }
        } finally {
            // if the exception handler changed, keep it
            // otherwise, unregister $renderException
            if (!$phpHandler) {
                if (set_exception_handler($renderException) === $renderException) {
                    restore_exception_handler();
                }
                restore_exception_handler();
            } elseif (!$debugHandler) {
                $finalHandler = $phpHandler[0]->setExceptionHandler(null);
                if ($finalHandler !== $renderException) {
                    $phpHandler[0]->setExceptionHandler($finalHandler);
                }
            }
        }

        if ($this->autoExit) {
            if ($exitCode > 255) {
                $exitCode = 255;
            }

            exit($exitCode);
        }

        return $exitCode;
    }

    /**
     * Runs the current application.
     *
     * @return int 0 if everything went fine, or an error code
     */
    public function doRun(InputInterface $input, OutputInterface $output)
    {
        if (true === $input->hasParameterOption(['--version', '-V'], true)) {
            $output->writeln($this->getLongVersion());

            return 0;
        }

        try {
            // Makes ArgvInput::getFirstArgument() able to distinguish an option from an argument.
            $input->bind($this->getDefinition());
        } catch (ExceptionInterface $e) {
            // Errors must be ignored, full binding/validation happens later when the command is known.
        }

        $name = $this->getCommandName($input);
        if (true === $input->hasParameterOption(['--help', '-h'], true)) {
            if (!$name) {
                $name = 'help';
                $input = new ArrayInput(['command_name' => $this->defaultCommand]);
            } else {
                $this->wantHelps = true;
            }
        }

        if (!$name) {
            $name = $this->defaultCommand;
            $definition = $this->getDefinition();
            $definition->setArguments(array_merge(
                $definition->getArguments(),
                [
                    'command' => new InputArgument('command', InputArgument::OPTIONAL, $definition->getArgument('command')->getDescription(), $name),
                ]
            ));
        }

        try {
            $this->runningCommand = null;
            // the command name MUST be the first element of the input
            $command = $this->find($name);
        } catch (\Throwable $e) {
            if (!($e instanceof CommandNotFoundException && !$e instanceof NamespaceNotFoundException) || 1 !== \count($alternatives = $e->getAlternatives()) || !$input->isInteractive()) {
                if (null !== $this->dispatcher) {
                    $event = new ConsoleErrorEvent($input, $output, $e);
                    $this->dispatcher->dispatch(ConsoleEvents::ERROR, $event);

                    if (0 === $event->getExitCode()) {
                        return 0;
                    }

                    $e = $event->getError();
                }

                throw $e;
            }

            $alternative = $alternatives[0];

            $style = new SymfonyStyle($input, $output);
            $style->block(sprintf("\nCommand \"%s\" is not defined.\n", $name), null, 'error');
            if (!$style->confirm(sprintf('Do you want to run "%s" instead? ', $alternative), false)) {
                if (null !== $this->dispatcher) {
                    $event = new ConsoleErrorEvent($input, $output, $e);
                    $this->dispatcher->dispatch(ConsoleEvents::ERROR, $event);

                    return $event->getExitCode();
                }

                return 1;
            }

            $command = $this->find($alternative);
        }

        $this->runningCommand = $command;
        $exitCode = $this->doRunCommand($command, $input, $output);
        $this->runningCommand = null;

        return $exitCode;
    }

    public function setHelperSet(HelperSet $helperSet)
    {
        $this->helperSet = $helperSet;
    }

    /**
     * Get the helper set associated with the command.
     *
     * @return HelperSet The HelperSet instance associated with this command
     */
    public function getHelperSet()
    {
        if (!$this->helperSet) {
            $this->helperSet = $this->getDefaultHelperSet();
        }

        return $this->helperSet;
    }

    public function setDefinition(InputDefinition $definition)
    {
        $this->definition = $definition;
    }

    /**
     * Gets the InputDefinition related to this Application.
     *
     * @return InputDefinition The InputDefinition instance
     */
    public function getDefinition()
    {
        if (!$this->definition) {
            $this->definition = $this->getDefaultInputDefinition();
        }

        if ($this->singleCommand) {
            $inputDefinition = $this->definition;
            $inputDefinition->setArguments();

            return $inputDefinition;
        }

        return $this->definition;
    }

    /**
     * Gets the help message.
     *
     * @return string A help message
     */
    public function getHelp()
    {
        return $this->getLongVersion();
    }

    /**
     * Gets whether to catch exceptions or not during commands execution.
     *
     * @return bool Whether to catch exceptions or not during commands execution
     */
    public function areExceptionsCaught()
    {
        return $this->catchExceptions;
    }

    /**
     * Sets whether to catch exceptions or not during commands execution.
     *
     * @param bool $boolean Whether to catch exceptions or not during commands execution
     */
    public function setCatchExceptions($boolean)
    {
        $this->catchExceptions = (bool) $boolean;
    }

    /**
     * Gets whether to automatically exit after a command execution or not.
     *
     * @return bool Whether to automatically exit after a command execution or not
     */
    public function isAutoExitEnabled()
    {
        return $this->autoExit;
    }

    /**
     * Sets whether to automatically exit after a command execution or not.
     *
     * @param bool $boolean Whether to automatically exit after a command execution or not
     */
    public function setAutoExit($boolean)
    {
        $this->autoExit = (bool) $boolean;
    }

    /**
     * Gets the name of the application.
     *
     * @return string The application name
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Sets the application name.
     *
     * @param string $name The application name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Gets the application version.
     *
     * @return string The application version
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * Sets the application version.
     *
     * @param string $version The application version
     */
    public function setVersion($version)
    {
        $this->version = $version;
    }

    /**
     * Returns the long version of the application.
     *
     * @return string The long application version
     */
    public function getLongVersion()
    {
        if ('UNKNOWN' !== $this->getName()) {
            if ('UNKNOWN' !== $this->getVersion()) {
                return sprintf('%s <info>%s</info>', $this->getName(), $this->getVersion());
            }

            return $this->getName();
        }

        return 'Console Tool';
    }

    /**
     * Registers a new command.
     *
     * @param string $name The command name
     *
     * @return Command The newly created command
     */
    public function register($name)
    {
        return $this->add(new Command($name));
    }

    /**
     * Adds an array of command objects.
     *
     * If a Command is not enabled it will not be added.
     *
     * @param Command[] $commands An array of commands
     */
    public function addCommands(array $commands)
    {
        foreach ($commands as $command) {
            $this->add($command);
        }
    }

    /**
     * Adds a command object.
     *
     * If a command with the same name already exists, it will be overridden.
     * If the command is not enabled it will not be added.
     *
     * @return Command|null The registered command if enabled or null
     */
    public function add(Command $command)
    {
        $this->init();

        $command->setApplication($this);

        if (!$command->isEnabled()) {
            $command->setApplication(null);

            return;
        }

        if (null === $command->getDefinition()) {
            throw new LogicException(sprintf('Command class "%s" is not correctly initialized. You probably forgot to call the parent constructor.', \get_class($command)));
        }

        if (!$command->getName()) {
            throw new LogicException(sprintf('The command defined in "%s" cannot have an empty name.', \get_class($command)));
        }

        $this->commands[$command->getName()] = $command;

        foreach ($command->getAliases() as $alias) {
            $this->commands[$alias] = $command;
        }

        return $command;
    }

    /**
     * Returns a registered command by name or alias.
     *
     * @param string $name The command name or alias
     *
     * @return Command A Command object
     *
     * @throws CommandNotFoundException When given command name does not exist
     */
    public function get($name)
    {
        $this->init();

        if (!$this->has($name)) {
            throw new CommandNotFoundException(sprintf('The command "%s" does not exist.', $name));
        }

        $command = $this->commands[$name];

        if ($this->wantHelps) {
            $this->wantHelps = false;

            $helpCommand = $this->get('help');
            $helpCommand->setCommand($command);

            return $helpCommand;
        }

        return $command;
    }

    /**
     * Returns true if the command exists, false otherwise.
     *
     * @param string $name The command name or alias
     *
     * @return bool true if the command exists, false otherwise
     */
    public function has($name)
    {
        $this->init();

        return isset($this->commands[$name]) || ($this->commandLoader && $this->commandLoader->has($name) && $this->add($this->commandLoader->get($name)));
    }

    /**
     * Returns an array of all unique namespaces used by currently registered commands.
     *
     * It does not return the global namespace which always exists.
     *
     * @return string[] An array of namespaces
     */
    public function getNamespaces()
    {
        $namespaces = [];
        foreach ($this->all() as $command) {
            $namespaces = array_merge($namespaces, $this->extractAllNamespaces($command->getName()));

            foreach ($command->getAliases() as $alias) {
                $namespaces = array_merge($namespaces, $this->extractAllNamespaces($alias));
            }
        }

        return array_values(array_unique(array_filter($namespaces)));
    }

    /**
     * Finds a registered namespace by a name or an abbreviation.
     *
     * @param string $namespace A namespace or abbreviation to search for
     *
     * @return string A registered namespace
     *
     * @throws NamespaceNotFoundException When namespace is incorrect or ambiguous
     */
    public function findNamespace($namespace)
    {
        $allNamespaces = $this->getNamespaces();
        $expr = preg_replace_callback('{([^:]+|)}', function ($matches) { return preg_quote($matches[1]).'[^:]*'; }, $namespace);
        $namespaces = preg_grep('{^'.$expr.'}', $allNamespaces);

        if (empty($namespaces)) {
            $message = sprintf('There are no commands defined in the "%s" namespace.', $namespace);

            if ($alternatives = $this->findAlternatives($namespace, $allNamespaces)) {
                if (1 == \count($alternatives)) {
                    $message .= "\n\nDid you mean this?\n    ";
                } else {
                    $message .= "\n\nDid you mean one of these?\n    ";
                }

                $message .= implode("\n    ", $alternatives);
            }

            throw new NamespaceNotFoundException($message, $alternatives);
        }

        $exact = \in_array($namespace, $namespaces, true);
        if (\count($namespaces) > 1 && !$exact) {
            throw new NamespaceNotFoundException(sprintf("The namespace \"%s\" is ambiguous.\nDid you mean one of these?\n%s", $namespace, $this->getAbbreviationSuggestions(array_values($namespaces))), array_values($namespaces));
        }

        return $exact ? $namespace : reset($namespaces);
    }

    /**
     * Finds a command by name or alias.
     *
     * Contrary to get, this command tries to find the best
     * match if you give it an abbreviation of a name or alias.
     *
     * @param string $name A command name or a command alias
     *
     * @return Command A Command instance
     *
     * @throws CommandNotFoundException When command name is incorrect or ambiguous
     */
    public function find($name)
    {
        $this->init();

        $aliases = [];
        $allCommands = $this->commandLoader ? array_merge($this->commandLoader->getNames(), array_keys($this->commands)) : array_keys($this->commands);
        $expr = preg_replace_callback('{([^:]+|)}', function ($matches) { return preg_quote($matches[1]).'[^:]*'; }, $name);
        $commands = preg_grep('{^'.$expr.'}', $allCommands);

        if (empty($commands)) {
            $commands = preg_grep('{^'.$expr.'}i', $allCommands);
        }

        // if no commands matched or we just matched namespaces
        if (empty($commands) || \count(preg_grep('{^'.$expr.'$}i', $commands)) < 1) {
            if (false !== $pos = strrpos($name, ':')) {
                // check if a namespace exists and contains commands
                $this->findNamespace(substr($name, 0, $pos));
            }

            $message = sprintf('Command "%s" is not defined.', $name);

            if ($alternatives = $this->findAlternatives($name, $allCommands)) {
                if (1 == \count($alternatives)) {
                    $message .= "\n\nDid you mean this?\n    ";
                } else {
                    $message .= "\n\nDid you mean one of these?\n    ";
                }
                $message .= implode("\n    ", $alternatives);
            }

            throw new CommandNotFoundException($message, $alternatives);
        }

        // filter out aliases for commands which are already on the list
        if (\count($commands) > 1) {
            $commandList = $this->commandLoader ? array_merge(array_flip($this->commandLoader->getNames()), $this->commands) : $this->commands;
            $commands = array_unique(array_filter($commands, function ($nameOrAlias) use ($commandList, $commands, &$aliases) {
                $commandName = $commandList[$nameOrAlias] instanceof Command ? $commandList[$nameOrAlias]->getName() : $nameOrAlias;
                $aliases[$nameOrAlias] = $commandName;

                return $commandName === $nameOrAlias || !\in_array($commandName, $commands);
            }));
        }

        $exact = \in_array($name, $co