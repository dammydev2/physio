
     */
    protected function validateInterfaceStatement(Interface_ $stmt)
    {
        $this->ensureCanDefine($stmt, self::INTERFACE_TYPE);
        $this->ensureInterfacesExist($stmt->extends, $stmt);
    }

    /**
     * Validate a trait definition statement.
     *
     * @param Trait_ $stmt
     */
    protected function validateTraitStatement(Trait_ $stmt)
    {
        $this->ensureCanDefine($stmt, self::TRAIT_TYPE);
    }

    /**
     * Validate a `new` expression.
     *
     * @param New_ $stmt
     */
    protected function validateNewExpression(New_ $stmt)
    {
        // if class name is an expression or an anonymous class, give it a pass for now
        if (!$stmt->class instanceof Expr && !$stmt->class instanceof Class_) {
            $this->ensureClassExists($this->getFullyQualifiedName($stmt->class), $stmt);
        }
    }

    /**
     * Validate a class constant fetch expression's class.
     *
     * @param ClassConstFetch $stmt
     */
    protected function validateClassConstFetchExpression(ClassConstFetch $stmt)
    {
        // there is no need to check exists for ::class const for php 5.5 or newer
        if (\strtolower($stmt->name) === 'class' && $this->atLeastPhp55) {
            return;
        }

        // if class name is an expression, give it a pass for now
        if (!$stmt->class instanceof Expr) {
            $this->ensureClassOrInterfaceExists($this->getFullyQualifiedName($stmt->class), $stmt);
        }
    }

    /**
     * Validate a class constant fetch expression's class.
     *
     * @param StaticCall $stmt
     */
    protected function validateStaticCallExpression(StaticCall $stmt)
    {
        // if class name is an expression, give it a pass for now
        if (!$stmt->class instanceof Expr) {
            $this->ensureMethodExists($this->getFullyQualifiedName($stmt->class), $stmt->name, $stmt);
        }
    }

    /**
     * Ensure that no class, interface or trait name collides with a new definition.
     *
     * @throws FatalErrorException
     *
     * @param Stmt   $stmt
     * @param string $scopeType
     */
    protected function ensureCanDefine(Stmt $stmt, $scopeType = self::CLASS_TYPE)
    {
        $name = $this->getFullyQualifiedName($stmt->name);

        // check for name collisions
        $errorType = null;
        if ($this->classExists($name)) {
            $errorType = self::CLASS_TYPE;
        } elseif ($this->interfaceExists($name)) {
            $errorType = self::INTERFACE_TYPE;
        } elseif ($this->traitExists($name)) {
            $errorType = self::TRAIT_TYPE;
        }

        if ($errorType !== null) {
            throw $this->createError(\sprintf('%s named %s already exists', \ucfirst($errorType), $name), $stmt);
        }

        // Store creation for the rest of this code snippet so we can find local
        // issue too
        $this->currentScope[\strtolower($name)] = $scopeType;
    }

    /**
     * Ensure that a referenced class exists.
     *
     * @throws FatalErrorException
     *
     * @param string $name
     * @param Stmt   $stmt
     */
    protected function ensureClassExists($name, $stmt)
    {
        if (!$this->classExists($name)) {
            throw $this->createError(\sprintf('Class \'%s\' not found', $name), $stmt);
        }
    }

    /**
     * Ensure that a referenced class _or interface_ exists.
     *
     * @throws FatalErrorException
     *
     * @param string $name
     * @param Stmt   $stmt
     */
    protected function ensureClassOrInterfaceExists($name, $stmt)
    {
        if (!$this->classExists($name) && !$this->interfaceExists($name)) {
            throw $this->createError(\sprintf('Class \'%s\' not found', $name), $stmt);
        }
    }

    /**
     * Ensure that a referenced class _or trait_ exists.
     *
     * @throws FatalErrorException
     *
     * @param string $name
     * @param Stmt   $stmt
     */
    protected function ensureClassOrTraitExists($name, $stmt)
    {
        if (!$this->classExists($name) && !$this->traitExists($name)) {
            throw $this->createError(\sprintf('Class \'%s\' not found', $name), $stmt);
        }
    }

    /**
     * Ensure that a statically called method exists.
     *
     * @throws FatalErrorException
     *
     * @param string $class
     * @param string $name
     * @param Stmt   $stmt
     */
    protected function ensureMethodExists($class, $name, $stmt)
    {
        $this->ensureClassOrTraitExists($class, $stmt);

        // let's pretend all calls to self, parent and static are valid
        if (\in_array(\strtolower($class), ['self', 'parent', 'static'])) {
            return;
        }

        // ... and all calls to classes defined right now
        if ($this->findInScope($class) === self::CLASS_TYPE) {
            return;
        }

        // if method name is an expression, give it a pass for now
        if ($name instanceof Expr) {
            return;
        }

        if (!\method_exists($class, $name) && !\method_exists($class, '__callStatic')) {
            throw $this->createError(\sprintf('Call to undefined method %s::%s()', $class, $name), $stmt);
        }
    }

    /**
     * Ensure that a referenced interface exists.
     *
     * @throws FatalErrorException
     *
     * @param Interface_[] $interfaces
     * @param Stmt         $stmt
     */
    protected function ensureInterfacesExist($interfaces, $stmt)
    {
        foreach ($interfaces as $interface) {
            /** @var string $name */
            $name = $this->getFullyQualifiedName($interface);
            if (!$this->interfaceExists($name)) {
                throw $this->createError(\sprintf('Interface \'%s\' not found', $name), $stmt);
            }
        }
    }

    /**
     * Get a symbol type key for storing in the scope name cache.
     *
     * @deprecated No longer used. Scope type should be passed into ensureCanDefine directly.
     * @codeCoverageIgnore
     *
     * @param Stmt $stmt
     *
     * @return string
     */
    protected function getScopeType(Stmt $stmt)
    {
        if ($stmt instanceof Class_) {
            return self::CLASS_TYPE;
        } elseif ($stmt instanceof Interface_) {
            return self::INTERFACE_TYPE;
        } elseif ($stmt instanceof Trait_) {
            return self::TRAIT_TYPE;
        }
    }

    /**
     * Check whether a class exists, or has been defined in the current code snippet.
     *
     * Gives `self`, `static` and `parent` a free pass.
     *
     * @param string $name
     *
     * @return bool
     */
    protected function classExists($name)
    {
        // Give `self`, `static` and `parent` a pass. This will actually let
        // some errors through, since we're not checking whether the keyword is
        // being used in a class scope.
        if (\in_array(\strtolower($name), ['self', 'static', 'parent'])) {
            return true;
        }

        return \class_exists($name) || $this->findInScope($name) === self::CLASS_TYPE;
    }

    /**
     * Check whether an interface exists, or has been defined in the current code snippet.
     *
     * @param string $name
     *
     * @return bool
     */
    protected function interfaceExists($name)
    {
        return \interface_exists($name) || $this->findInScope($name) === self::INTERFACE_TYPE;
    }

    /**
     * Check whether a trait exists, or has been defined in the current code snippet.
     *
     * @param string $name
     *
     * @return bool
     */
    protected function traitExists($name)
    {
        return \trait_exists($name) || $this->findInScope($name) === self::TRAIT_TYPE;
    }

    /**
     * Find a symbol in the current code snippet scope.
     *
     * @param string $name
     *
     * @return string|null
     */
    protected function findInScope($name)
    {
        $name = \strtolower($name);
        if (isset($this->currentScope[$name])) {
            return $this->currentScope[$name];
        }
    }

    /**
     * Error creation factory.
     *
     * @param string $msg
     * @param Stmt   $stmt
     *
     * @return FatalErrorException
     */
    protected function createError($msg, $stmt)
    {
        return new FatalErrorException($msg, 0, E_ERROR, null, $stmt->getLine());
    }
}
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                <?php

/*
 * This file is part of Psy Shell.
 *
 * (c) 2012-2018 Justin Hileman
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Psy\CodeCleaner;

use PhpParser\Node;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\ClassConstFetch;
use PhpParser\Node\Expr\ConstFetch;
use PhpParser\Node\Identifier;
use Psy\Exception\FatalErrorException;

/**
 * Validate that namespaced constant references will succeed.
 *
 * This pass throws a FatalErrorException rather than letting PHP run
 * headfirst into a real fatal error and die.
 *
 * @todo Detect constants defined in the current code snippet?
 *       ... Might not be worth it, since it would need to both be defining and
 *       referencing a namespaced constant, which doesn't seem like that big of
 *       a target for failure
 */
class ValidConstantPass extends NamespaceAwarePass
{
    /**
     * Validate that namespaced constant references will succeed.
     *
     * Note that this does not (yet) detect constants defined in the current code
     * snippet. It won't happen very often, so we'll punt for now.
     *
     * @throws FatalErrorException if a constant reference is not defined
     *
     * @param Node $node
     */
    public function leaveNode(Node $node)
    {
        if ($node instanceof ConstFetch && \count($node->name->parts) > 1) {
            $name = $this->getFullyQualifiedName($node->name);
            if (!\defined($name)) {
                $msg = \sprintf('Undefined constant %s', $name);
                throw new FatalErrorException($msg, 0, E_ERROR, null, $node->getLine());
            }
        } elseif ($node instanceof ClassConstFetch) {
            $this->validateClassConstFetchExpression($node);
        }
    }

    /**
     * Validate a class constant fetch expression.
     *
     * @throws FatalErrorException if a class constant is not defined
     *
     * @param ClassConstFetch $stmt
     */
    protected function validateClassConstFetchExpression(ClassConstFetch $stmt)
    {
        // For PHP Parser 4.x
        $constName = $stmt->name instanceof Identifier ? $stmt->name->toString() : $stmt->name;

        // give the `class` pseudo-constant a pass
        if ($constName === 'class') {
            return;
        }

        // if class name is an expression, give it a pass for now
        if (!$stmt->class instanceof Expr) {
            $className = $this->getFullyQualifiedName($stmt->class);

            // if the class doesn't exist, don't throw an exception… it might be
            // defined in the same line it's used or something stupid like that.
            if (\class_exists($className) || \interface_exists($className)) {
                $refl = new \ReflectionClass($className);
                if (!$refl->hasConstant($constName)) {
                    $constType = \class_exists($className) ? 'Class' : 'Interface';
                    $msg = \sprintf('%s constant \'%s::%s\' not found', $constType, $className, $constName);
                    throw new FatalErrorException($msg, 0, E_ERROR, null, $stmt->getLine());
                }
            }
        }
    }
}
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                              <?php

/*
 * This file is part of Psy Shell.
 *
 * (c) 2012-2018 Justin Hileman
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Psy\CodeCleaner;

use PhpParser\Node;
use PhpParser\Node\Identifier;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Namespace_;
use Psy\Exception\FatalErrorException;

/**
 * Validate that the constructor method is not static, and does not have a
 * return type.
 *
 * Checks both explicit __construct methods as well as old-style constructor
 * methods with the same name as the class (for non-namespaced classes).
 *
 * As of PHP 5.3.3, methods with the same name as the last element of a
 * namespaced class name will no longer be treated as constructor. This change
 * doesn't affect non-namespaced classes.
 *
 * @author Martin Hasoň <martin.hason@gmail.com>
 */
class ValidConstructorPass extends CodeCleanerPass
{
    private $namespace;

    public function beforeTraverse(array $nodes)
    {
        $this->namespace = [];
    }

    /**
     * Validate that the constructor is not static and does not have a return type.
     *
     * @throws FatalErrorException the constructor function is static
     * @throws FatalErrorException the constructor function has a return type
     *
     * @param Node $node
     */
    public function enterNode(Node $node)
    {
        if ($node instanceof Namespace_) {
            $this->namespace = isset($node->name) ? $node->name->parts : [];
        } elseif ($node instanceof Class_) {
            $constructor = null;
            foreach ($node->stmts as $stmt) {
                if ($stmt instanceof ClassMethod) {
                    // If we find a new-style constructor, no need to look for the old-style
                    if ('__construct' === \strtolower($stmt->name)) {
                        $this->validateConstructor($stmt, $node);

                        return;
                    }

                    // We found a possible old-style constructor (unless there is also a __construct method)
                    if (empty($this->namespace) && \strtolower($node->name) === \strtolower($stmt->name)) {
                        $constructor = $stmt;
                    }
                }
            }

            if ($constructor) {
                $this->validateConstructor($constructor, $node);
            }
        }
    }

    /**
     * @throws FatalErrorException the constructor function is static
     * @throws FatalErrorException the constructor function has a return type
     *
     * @param Node $constructor
     * @param Node $classNode
     */
    private function validateConstructor(Node $constructor, Node $classNode)
    {
        if ($constructor->isStatic()) {
            // For PHP Parser 4.x
            $className = $classNode->name instanceof Identifier ? $classNode->name->toString() : $classNode->name;

            $msg = \sprintf(
                'Constructor %s::%s() cannot be static',
                \implode('\\', \array_merge($this->namespace, (array) $className)),
                $constructor->name
            );
            throw new FatalErrorException($msg, 0, E_ERROR, null, $classNode->getLine());
        }

        if (\method_exists($constructor, 'getReturnType') && $constructor->getReturnType()) {
            // For PHP Parser 4.x
            $className = $classNode->name instanceof Identifier ? $classNode->name->toString() : $classNode->name;

            $msg = \sprintf(
                'Constructor %s::%s() cannot declare a return type',
                \implode('\\', \array_merge($this->namespace, (array) $className)),
                $constructor->name
            );
            throw new FatalErrorException($msg, 0, E_ERROR, null, $classNode->getLine());
        }
    }
}
                                                                                                                                                                                                                    <?php

/*
 * This file is part of Psy Shell.
 *
 * (c) 2012-2018 Justin Hileman
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Psy\CodeCleaner;

use PhpParser\Node;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Stmt\Do_;
use PhpParser\Node\Stmt\Function_;
use PhpParser\Node\Stmt\If_;
use PhpParser\Node\Stmt\Switch_;
use PhpParser\Node\Stmt\While_;
use Psy\Exception\FatalErrorException;

/**
 * Validate that function calls will succeed.
 *
 * This pass throws a FatalErrorException rather than letting PHP run
 * headfirst into a real fatal error and die.
 */
class ValidFunctionNamePass extends NamespaceAwarePass
{
    private $conditionalScopes = 0;

    /**
     * Store newly defined function names on the way in, to allow recursion.
     *
     * @param Node $node
     */
    public function enterNode(Node $node)
    {
        parent::enterNode($node);

        if (self::isConditional($node)) {
            $this->conditionalScopes++;
        } elseif ($node instanceof Function_) {
            $name = $this->getFullyQualifiedName($node->name);

            // @todo add an "else" here which adds a runtime check for instances where we can't tell
            // whether a function is being redefined by static analysis alone.
            if ($this->conditionalScopes === 0) {
                if (\function_exists($name) ||
                    isset($this->currentScope[\strtolower($name)])) {
                    $msg = \sprintf('Cannot redeclare %s()', $name);
                    throw new FatalErrorException($msg, 0, E_ERROR, null, $node->getLine());
                }
            }

            $this->currentScope[\strtolower($name)] = true;
        }
    }

    /**
     * Validate that function calls will succeed.
     *
     * @throws FatalErrorException if a function is redefined
     * @throws FatalErrorException if the function name is a string (not an expression) and is not defined
     *
     * @param Node $node
     */
    public function leaveNode(Node $node)
    {
        if (self::isConditional($node)) {
            $this->conditionalScopes--;
        } elseif ($node instanceof FuncCall) {
            // if function name is an expression or a variable, give it a pass for now.
            $name = $node->name;
            if (!$name instanceof Expr && !$name instanceof Variable) {
                $shortName = \implode('\\', $name->parts);
                $fullName  = $this->getFullyQualifiedName($name);
                $inScope   = isset($this->currentScope[\strtolower($fullName)]);
                if (!$inScope && !\function_exists($shortName) && !\function_exists($fullName)) {
                    $message = \sprintf('Call to undefined function %s()', $name);
                    throw new FatalErrorException($message, 0, E_ERROR, null, $node->getLine());
                }
            }
        }
    }

    private static function isConditional(Node $node)
    {
        return $node instanceof If_ ||
            $node instanceof While_ ||
            $node instanceof Do_ ||
            $node instanceof Switch_;
    }
}
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                         <?php

/*
 * This file is part of Psy Shell.
 *
 * (c) 2012-2018 Justin Hileman
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Psy\Command;

use Psy\Output\ShellOutput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Interact with the current code buffer.
 *
 * Shows and clears the buffer for the current multi-line expression.
 */
class BufferCommand extends Command
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('buffer')
            ->setAliases(['buf'])
            ->setDefinition([
                new InputOption('clear', '', InputOption::VALUE_NONE, 'Clear the current buffer.'),
            ])
            ->setDescription('Show (or clear) the contents of the code input buffer.')
            ->setHelp(
                <<<'HELP'
Show the contents of the code buffer for the current multi-line expression.

Optionally, clear the buffer by passing the <info>--clear</info> option.
HELP
            );
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $buf = $this->getApplication()->getCodeBuffer();
        if ($input->getOption('clear')) {
            $this->getApplication()->resetCodeBuffer();
            $output->writeln($this->formatLines($buf, 'urgent'), ShellOutput::NUMBER_LINES);
        } else {
            $output->writeln($this->formatLines($buf), ShellOutput::NUMBER_LINES);
        }
    }

    /**
     * A helper method for wrapping buffer lines in `<urgent>` and `<return>` formatter strings.
     *
     * @param array  $lines
     * @param string $type  (default: 'return')
     *
     * @return array Formatted strings
     */
    protected function formatLines(array $lines, $type = 'return')
    {
        $template = \sprintf('<%s>%%s</%s>', $type, $type);

        return \array_map(function ($line) use ($template) {
            return \sprintf($template, $line);
        }, $lines);
    }
}
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                         <?php

/*
 * This file is part of Psy Shell.
 *
 * (c) 2012-2018 Justin Hileman
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Psy\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Clear the Psy Shell.
 *
 * Just what it says on the tin.
 */
class ClearCommand extends Command
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('clear')
            ->setDefinition([])
            ->setDescription('Clear the Psy Shell screen.')
            ->setHelp(
                <<<'HELP'
Clear the Psy Shell screen.

Pro Tip: If your PHP has readline support, you should be able to use ctrl+l too!
HELP
            );
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->write(\sprintf('%c[2J%c[0;0f', 27, 27));
    }
}
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                <?php

/*
 * This file is part of Psy Shell.
 *
 * (c) 2012-2018 Justin Hileman
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Psy\Command;

use Psy\Shell;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Command\Command as BaseCommand;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Helper\TableHelper;
use Symfony\Component\Console\Helper\TableStyle;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * The Psy Shell base command.
 */
abstract class Command extends BaseCommand
{
    /**
     * Sets the application instance for this command.
     *
     * @param Application $application An Application instance
     *
     * @api
     */
    public function setApplication(Application $application = null)
    {
        if ($application !== null && !$application instanceof Shell) {
            throw new \InvalidArgumentException('PsySH Commands require an instance of Psy\Shell');
        }

        return parent::setApplication($application);
    }

    /**
     * {@inheritdoc}
     */
    public function asText()
    {
        $messages = [
            '<comment>Usage:</comment>',
            ' ' . $this->getSynopsis(),
            '',
        ];

        if ($this->getAliases()) {
            $messages[] = $this->aliasesAsText();
        }

        if ($this->getArguments()) {
            $messages[] = $this->argumentsAsText();
        }

        if ($this->getOptions()) {
            $messages[] = $this->optionsAsText();
        }

        if ($help = $this->getProcessedHelp()) {
            $messages[] = '<comment>Help:</comment>';
            $messages[] = ' ' . \str_replace("\n", "\n ", $help) . "\n";
        }

        return \implode("\n", $messages);
    }

    /**
     * {@inheritdoc}
     */
    private function getArguments()
    {
        $hidden = $this->getHiddenArguments();

    