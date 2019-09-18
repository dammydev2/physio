<?php

/*
 * This file is part of SwiftMailer.
 * (c) 2004-2009 Chris Corbyn
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Egulias\EmailValidator\EmailValidator;
use Egulias\EmailValidator\Validation\RFCValidation;

/**
 * A Mailbox Address MIME Header for something like From or Sender.
 *
 * @author Chris Corbyn
 */
class Swift_Mime_Headers_MailboxHeader extends Swift_Mime_Headers_AbstractHeader
{
    /**
     * The mailboxes used in this Header.
     *
     * @var string[]
     */
    private $mailboxes = [];

    /**
     * The strict EmailValidator.
     *
     * @var EmailValidator
     */
    private $emailValidator;

    private $addressEncoder;

    /**
     * Creates a new MailboxHeader with $name.
     *
     * @param string $name of Header
     */
    public function __construct($name, Swift_Mime_HeaderEncoder $encoder, EmailValidator $emailValidator, Swift_AddressEncoder $addressEncoder = null)
    {
        $this->setFieldName($name);
        $this->setEncoder($encoder);
        $this->emailValidator = $emailValidator;
        $this->addressEncoder = $addressEncoder ?? new Swift_AddressEncoder_IdnAddressEncoder();
    }

    /**
     * Get the t