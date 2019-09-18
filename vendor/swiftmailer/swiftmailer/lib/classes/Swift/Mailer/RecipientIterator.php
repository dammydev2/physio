<?php

/*
 * This file is part of SwiftMailer.
 * (c) 2004-2009 Chris Corbyn
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * Does real time logging of Transport level information.
 *
 * @author     Chris Corbyn
 */
class Swift_Plugins_LoggerPlugin implements Swift_Events_CommandListener, Swift_Events_ResponseListener, Swift_Events_TransportChangeListener, Swift_Events_TransportExceptionListener, Swift_Plugins_Logger
{
    /** The logger which is delegated to */
    private $logger;

    /**
     * Create a new LoggerPlugin using $logger.
     */
    public function __construct(Swift_Plugins_Logger $logger)
    {
        $this->logger = $logge