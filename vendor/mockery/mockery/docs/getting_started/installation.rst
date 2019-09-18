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

class ExpectationDirector
{
    /**
     * Method name the director is directing
     *
     * @var string
     */
    protected $_name = null;

    /**
     * Mock object the director is attached to
     *
     * @var \Mockery\MockInterface
     */
    protected $_mock = null;

    /**
     * Stores an array of all expectations for this mock
     *
     * @var array
     */
    protected $_expectations = array();

    /**
     * The expected order of next call
     *
     * @var int
     */
    protected $_expectedOrder = null;

    /**
     * Stores an array of all default expectatio