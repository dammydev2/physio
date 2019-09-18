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

use Mockery\HigherOrderMessage;
use Mockery\MockInterface;
use Mockery\ExpectsHigherOrderMessage;
use Mockery\Exception\BadMethodCallException;

class Mock implements MockInterface
{
    /**
     * Stores an array of all expectation directors for this mock
     *
     * @var array
     */
    protected $_mockery_expectations = array();

    /**
     * Stores an inital number of expectations that can be manipulated
     * while using the getter method.
     *
     * @var int
     */
    protected $_mockery_expectations_count = 0;

    /**
     * Flag to indicate whether we can ignore method calls missing from our
     * expectations
     *
     * @var bool
     */
    protected $_mockery_ignoreMissing = false;

    /**
     * Flag to indicate whether we can defer method calls missing from our
     * expectations
     *
     * @var bool
     */
    protected $_mockery_deferMissing = false;

    /**
     * Flag to indicate whether this mock was verified
     *
     * @var bool
     */
    protected $_mockery_verified = false;

    /**
     * Given name of the mock
     *
     * @var string
     */
    protected $_mockery_name = null;

    /**
     * Order number of allocation
     *
     * @var int
     */
    protected $_mockery_allocatedOrder = 0;

    /**
     * Current ordered number
     *
     * @var int
     */
    protected $_mockery_currentOrder = 0;

    /**
     * Ordered groups
     *
     * @var array
     */
    protected $_mockery_groups = array();

    /**
     * Mock container containing this mock object
     *
     * @var \Mockery\Container
     */
    protected $_mockery_container = null;

    /**
     * Instance of a core object on which methods are called in the event
     * it has been set, and an expectation for one of the object's methods
     * does not exist. This implements a simple partial mock proxy system.
     *
     * @var object
     */
    protected $_mockery_partial = null;

    /**
     * Flag to