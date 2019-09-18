<?php declare(strict_types=1);
/*
 * This file is part of PHPUnit.
 *
 * (c) Sebastian Bergmann <sebastian@phpunit.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace PHPUnit\Framework\MockObject;

use ReflectionClass;
use ReflectionException;
use ReflectionMethod;
use Text_Template;

final class MockMethod
{
    /**
     * @var Text_Template[]
     */
    private static $templates = [];

    /**
     * @var string
     */
    private $className;

    /**
     * @var string
     */
    private $methodName;

    /**
     * @var bool
     */
    private $cloneArguments;

    /**
     * @var string string
     */
    private $modifier;

    /**
     * @var string
     */
    private $argumentsForDeclaration;

    /**
     * @var string
     */
    private $argumentsForCall;

    /**
     * @var string
     */
    private $returnType;

    /**
     * @var string
     */
    private $reference;

    /**
     * @var bool
     */
    private $callOriginalMethod;

    /**
     * @var bool
     */
    private $static;

    /**
     * @var ?string
     */
    private $deprecation;

    /**
     * @var bool
     */
    private $allowsReturnNull;

    public static function fromReflection(ReflectionMethod $method, bool $callOriginalMethod, bool $cloneArguments): self
    {
        if ($method->isPrivate()) {
            $modifier = 'private';
        } elseif ($method->isProtected()) {
            $modifier = 'protected';
        } else {
            $modifier = 'public';
        }

        if ($method->isStatic()) {
            $modifier .= ' static';
        }

        if ($method->returnsReference()) {
            $reference = '&';
        } else {
            $reference = '';
        }

        if ($method->hasReturnType()) {
            $returnType = (string) $method->getReturnType();
        } else {
            $returnType = '';
        }

        $docComment = $method->getDocComment();

        if (\is_string($docComment)
            && \preg_match('#\*[ \t]*+@deprecated[ \t]*+(.*?)\r?+\n[ \t]*+\*(?:[ \t]*+@|/$)#s', $docComment, $deprecation)
        ) {
            $deprecation = \trim(\preg_replace('#[ \t]*\r?\n[ \t]*+\*[ \t]*+#', ' ', $deprecation[1]));
        } else {
            $deprecation = null;
        }

        return new self(
            $method->getDeclaringClass()->getName(),
            $method->getName(),
            $cloneArguments,
            $modifier,
            self::getMethodParameters($method),
            self::getMethodParameters($method, true),
            $returnType,
            $reference,
            $callOriginalMethod,
            $method->isStatic(),
            $deprecation,
            $method->hasReturnType() && $method->getReturnType()->allowsNull()
        );
    }

    public static function fromName(string $fullClassName, string $methodName, bool $cloneArguments): self
    {
        return new self(
            $fullClassName,
            $methodName,
            $cloneArguments,
            'public',
            '',
            '',
            '',
            '',
            false,
            false,
            null,
            false
        );
    }

    public function __construct(string $className, string $methodName, bool $cloneArguments, string $modifier, string $argumentsForDeclaration, string $argumentsForCall, string $returnType, string $reference, bool $callOriginalMethod, bool $static, ?string $deprecation, bool $allowsReturnNull)
    {
        $this->className               = $className;
        $this->methodName              = $methodName;
        $this->cloneArguments          = $cloneArguments;
        $this->modifier                = $modifier;
        $this->argumentsForDeclaration = $argumentsForDeclaration;
        $this->argumentsForCall        = $argumentsForCall;
        $this->returnType              = $returnType;
        $this->reference               = $reference;
        $this->callOriginalMethod      = $callOriginalMethod;
        $this->static                  = $static;
        $this->deprecation             = $deprecation;
        $this->allowsReturnNull        = $allowsReturnNull;
    }

    public function getName(): string
    {
        return $this->methodName;
    }

    /**
     * @throws \ReflectionException
     * @throws \PHPUnit\Framework\MockObject\