<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\Finder\Iterator;

/**
 * SortableIterator applies a sort on a given Iterator.
 *
 * @author Fabien Potencier <fabien@symfony.com>
 */
class SortableIterator implements \IteratorAggregate
{
    const SORT_BY_NONE = 0;
    const SORT_BY_NAME = 1;
    const SORT_BY_TYPE = 2;
    const SORT_BY_ACCESSED_TIME = 3;
    const SORT_BY_CHANGED_TIME = 4;
    const SORT_BY_MODIFIED_TIME = 5;
    const SORT_BY_NAME_NATURAL = 6;

    private $iterator;
    private $sort;

    /**
     * @param \Traversable $iterator The Iterator to filter
     * @param int|callable $sort     The sort type (SORT_BY_NAME, SORT_BY_TYPE, or a PHP callback)
     *
     * @throws \InvalidArgumentException
     */
    public function __construct(\Traversable $iterator, $sort, bool $reverseOrder = false)
    {
        $this->iterator = $iterator;
        $order = $reverseOrder ? -1 : 1;

        if (self::SORT_BY_NAME === $sort) {
            $this->sort = function ($a, $b) use ($order) {
                return $order * strcmp($a->getRealpath(