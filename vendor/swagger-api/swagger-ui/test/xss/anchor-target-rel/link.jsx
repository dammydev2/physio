<?php

/*
 * This file is part of SwiftMailer.
 * (c) 2004-2009 Chris Corbyn
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * Dependency Injection container.
 *
 * @author  Chris Corbyn
 */
class Swift_DependencyContainer
{
    /** Constant for literal value types */
    const TYPE_VALUE = 0x00001;

    /** Constant for new instance types */
    const TYPE_INSTANCE = 0x00010;

    /** Constant for shared instance types */
    const TYPE_SHARED = 0x00100;

    /** Constant for aliases */
    const TYPE_ALIAS = 0x01000;

    /** Constant for arrays */
    const TYPE_ARRAY = 0x10000;

    /** Singleton instance */
    private static $instance = null;

    /** The data container */
    private $store = [];

    /** The current endpoint in the data container */
    private $endPoint;

    /**
     * Constructor should not be used.
     *
     * Use {@link getInstance()} instead.
     */
    public function __construct()
    {
    }

    /**
     * Returns a singleton of the DependencyContainer.
     *
     * @return self
     */
    public static function getInstance()
    {
        if (!isset(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * List the names