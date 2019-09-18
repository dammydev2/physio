<?php

/*
 * This file is part of SwiftMailer.
 * (c) 2004-2009 Chris Corbyn
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Egulias\EmailValidator\EmailValidator;

/**
 * Creates MIME headers.
 *
 * @author Chris Corbyn
 */
class Swift_Mime_SimpleHeaderFactory implements Swift_Mime_CharsetObserver
{
    /** The HeaderEncoder used by these headers */
    private $encoder;

    /** The Encoder used by parameters */
    private $paramEncoder;

    /** Strict EmailValidator */
    private $emailValidator;

    /** The charset of created Headers */
    private $charset;

    /** Swift_AddressEncoder */
    private $addressEncoder;

    /**
     * Creates a new SimpleHeaderFactory using $encoder and $paramEncoder.
     *
     * @param string|null $charset
     */
    public function __construct(Swift_Mime_HeaderEncoder $encoder, Swift_Encoder $paramEncoder, EmailValidator $emailValidator, $charset = null, Swift_AddressEncoder $addressEncoder = null)
    {
        $this->encoder = $encoder;
        $this->paramEncoder = $paramEncoder;
        $this->emailValidator = $emailValidator;
        $this->charset = $charset;
        $this->addressEncoder = $addressEncoder ?? new Swift_AddressEncoder_IdnAddressEncoder();
    }

    /**
     * Create a new Mailbox Header with a list of $addresses.
     *
     * @param string            $name
     * @param array|string|null $addresses
     *
     * @return Swi