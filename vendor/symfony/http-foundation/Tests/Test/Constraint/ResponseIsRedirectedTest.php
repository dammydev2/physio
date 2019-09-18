<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\HttpKernel\ControllerMetadata;

/**
 * Responsible for storing metadata of an argument.
 *
 * @author Iltar van der Berg <kjarli@gmail.com>
 */
class ArgumentMetadata
{
    private $name;
    private $type;
    private $isVariadic;
    private $hasDefaultValue;
    private $defaultValue;
    private $isNullable;

    public function __construct(string $name, ?string $type, bool $isVariadic, bool $hasDefaultValue, $defaultValue, bool $isNullable = false)
    {
        $this->name = $name;
        $this->type = $type;
        $this->isVariadic = $isVariadic;
        $this->hasDefaultValue = $hasDefaultValue;
        $this->defaultValue = $defaultValue;
        $this->isNullable = $isNullable || null === $type || ($hasDefaultValue && null === $defaultValue);
    }

    /**
     * Returns the name as given in PHP, $foo would yield "foo".
     *
     * @return string
     */
    public function getNam