<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\Console\Input;

use Symfony\Component\Console\Exception\InvalidArgumentException;
use Symfony\Component\Console\Exception\LogicException;

/**
 * A InputDefinition represents a set of valid command line arguments and options.
 *
 * Usage:
 *
 *     $definition = new InputDefinition([
 *         new InputArgument('name', InputArgument::REQUIRED),
 *         new InputOption('foo', 'f', InputOption::VALUE_REQUIRED),
 *     ]);
 *
 * @author Fabien Potencier <fabien@symfony.com>
 */
class InputDefinition
{
    private $arguments;
    private $requiredCount;
    private $hasAnArrayArgument = false;
    private $hasOptional;
    private $options;
    private $shortcuts;

    /**
     * @param array $definition An array of InputArgument and InputOption instance
     */
    public function __construct(array $definition = [])
    {
        $this->setDefinition($definition);
    }

    /**
     * Sets the definition of the input.
     */
    public function setDefinition(array $definition)
    {
        $arguments = [];
        $options = [];
  