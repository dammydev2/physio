<?php

/*
 * This file is part of SwiftMailer.
 * (c) 2004-2009 Chris Corbyn
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * A collection of MIME headers.
 *
 * @author Chris Corbyn
 */
class Swift_Mime_SimpleHeaderSet implements Swift_Mime_CharsetObserver
{
    /** HeaderFactory */
    private $factory;

    /** Collection of set Headers */
    private $headers = [];

    /** Field ordering details */
    private $order = [];

    /** List of fields which are required to be displayed */
    private $required = [];

    /** The charset used by Headers */
    private $charset;

    /**
     * Create a new SimpleHeaderSet with the given $factory.
     *
     * @param string $charset
     */
    public function __construct(Swift_Mime_SimpleHeaderFactory $factory, $charset = null)
    {
        $this->factory = $factory;
        if (isset($charset)) {
            $this->setCharset($charset);
        }
    }

    public function newInstance()
    {
        return new self($this->factory);
    }

    /**
     * Set the charset used by these headers.
     *
     * @param string $charset
     */
    public function setCharset($charset)
