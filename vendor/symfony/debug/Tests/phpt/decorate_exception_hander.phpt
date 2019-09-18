<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\Finder\Comparator;

/**
 * Comparator.
 *
 * @author Fabien Potencier <fabien@symfony.com>
 */
class Comparator
{
    private $target;
    private $operator = '==';

    /**
     * Gets the target value.
     *
     * @return string The target value
     */
    public function getTarget()
    {
        return $this->target;
    }

    /**
     * Sets the target value.
     *
     * @param string $target The target value
     */
    public function setTarget($target)
    {
        $this->target = $target;
    }

    /**
     * Gets the comparison operator.
     *
     * @return string The operator
     */
    public function getOperator()
    {
        return $this->operator;
    }

    /**
     * Sets the comparis