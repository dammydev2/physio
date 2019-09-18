<?php
/**
 * Mockery
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://github.com/padraic/mockery/blob/master/LICENSE
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to padraic@php.net so we can send you a copy immediately.
 *
 * @category   Mockery
 * @package    Mockery
 * @copyright  Copyright (c) 2010 Pádraic Brady (http://blog.astrumfutura.com)
 * @license    http://github.com/padraic/mockery/blob/master/LICENSE New BSD License
 */

namespace Mockery;

use Mockery\Generator\Generator;
use Mockery\Generator\MockConfigurationBuilder;
use Mockery\Loader\Loader as LoaderInterface;

class Container
{
    const BLOCKS = \Mockery::BLOCKS;

    /**
     * Store of mock objects
     *
     * @var array
     */
    protected $_mocks = array();

    /**
     * Order number of allocation
     *
     * @var int
     */
    protected $_allocatedOrder = 0;

    /**
     * Current ordered number
     *
     * @var int
     */
    protected $_currentOrder = 0;

    /**
     * Ordered groups
     *
     * @var array
     */
    protected $_groups = array();

    /**
     * @var Generator
     */
    protected $_generator;

    /**
     * @var LoaderInterface
     */
    protected $_loader;

    /**
     * @var array
     */
    protected $_namedMocks = array();

    public function __construct(Generator $generator = null, LoaderInterface $loader = null)
    {
        $this->_generator = $generator ?: \Mockery::getDefaultGenerator();
        $this->_loader = $loader ?: \Mockery::getDefaultLoader();
    }

    /**
     * Generates a new mock object for this container
     *
     * I apologies in advance for this. A God Method just fits the API which
     * doesn't require differentiating between classes, interfaces, abstra