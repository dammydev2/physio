<?php

/*
 * This file is part of SwiftMailer.
 * (c) 2004-2009 Chris Corbyn
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * Makes sure a connection to a POP3 host has been established prior to connecting to SMTP.
 *
 * @author     Chris Corbyn
 */
class Swift_Plugins_PopBeforeSmtpPlugin implements Swift_Events_TransportChangeListener, Swift_Plugins_Pop_Pop3Connection
{
    /** A delegate connection to use (mostly a test hook) */
    private $connection;

    /** Hostname of the POP3 server */
    private $host;

    /** Port number to connect on */
    private $port;

    /** Encryption type to use (if any) */
    private $crypto;

    /** Username to use (if any) */
    private $username;

    /** Password to use (if any) */
    private $password;

    /** Established connection via TCP socket */
    private $socket;

    /** Connect timeout in seconds */
    p