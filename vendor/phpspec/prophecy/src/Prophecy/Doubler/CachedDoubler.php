<?php

/*
 * This file is part of the Prophecy.
 * (c) Konstantin Kudryashov <ever.zet@gmail.com>
 *     Marcello Duarte <marcello.duarte@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Prophecy\Prophecy;

use SebastianBergmann\Comparator\ComparisonFailure;
use Prophecy\Comparator\Factory as ComparatorFactory;
use Prophecy\Call\Call;
use Prophecy\Doubler\LazyDouble;
use Prophecy\Argument\ArgumentsWildcard;
use Prophecy\Call\CallCenter;
use Prophecy\Exception\Prophecy\ObjectProphecyException;
use Prophecy\Exception\Prophecy\MethodProphecyException;
use Prophecy\Exception\Prediction\AggregateException;
use Prophecy\Exception\Prediction\PredictionException;

/**
 * Object prophecy.
 *
 * @author Konstantin Kudryashov <ever.zet@gmail.com>
 */
class ObjectProphecy implements ProphecyInterface
{
    private $lazyDouble;
    private $callCenter;
    private $revealer;
    private $comparatorFactory;

    /**
     * @var MethodProphecy[][]
     */
    private $methodProphecies = array();

    /**
     * Initializes object prophecy.
     *
     * @param LazyDouble        $lazyDouble
     * @param CallCenter        $callCenter
     * @param RevealerInterface $revealer
     * @param ComparatorFactory $comparatorFactory
     */
    public function __construct(
        LazyDouble $lazyDouble,
        CallCenter $callCenter = null,
        RevealerInterface $revealer = null,
        ComparatorFactory $comparatorFactory = null
    ) {
        $this->lazyDouble = $lazyDouble;
        $this->callCenter = $callCenter ?: new CallCenter;
        $this->