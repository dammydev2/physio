<?php

/*
 * This file is part of SwiftMailer.
 * (c) 2004-2009 Chris Corbyn
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * The default email message class.
 *
 * @author Chris Corbyn
 */
class Swift_Mime_SimpleMessage extends Swift_Mime_MimePart
{
    const PRIORITY_HIGHEST = 1;
    const PRIORITY_HIGH = 2;
    const PRIORITY_NORMAL = 3;
    const PRIORITY_LOW = 4;
    const PRIORITY_LOWEST = 5;

    /**
     * Create a new SimpleMessage with $headers, $encoder and $cache.
     *
     * @param string $charset
     */
    public function __construct(Swift_Mime_SimpleHeaderSet $headers, Swift_Mime_ContentEncoder $encoder, Swift_KeyCache $cache, Swift_IdGenerator $idGenerator, $charset = null)
    {
        parent::__construct($headers, $encoder, $cache, $idGenerator, $charset);
        $this->getHeaders()->defineOrdering([
            'Return-Path',
            'Received',
            'DKIM-Signature',
            'DomainKey-Signature',
            'Sender',
            'Message-ID',
            'Date',
            'Subject',
            'From',
            'Reply-To',
            'To',
            'Cc',
            'Bcc',
            'MIME-Version',
            'Content-Type',
            'Content-Transfer-Encoding',
            ]);
        $this->getHeaders()->setAlwaysDisplayed(['Date', 'Message-ID', 'From']);
        $this->getHeaders()->addTextHeader('MIME-Version', '1.0');
        $this->setDate(new DateTimeImmutable());
        $this->setId($this->getId());
        $this->getHeaders()->addMailboxHeader('From');
    }

    /**
     * Always returns {@link LEVEL_TOP} for a message instance.
     *
     * @return int
     */
    public function getNestingLevel()
    {
        return self::LEVEL_TOP;
    }

    /**
     * Set the subject of this message.
     *
     * @param string $subject
     *
     * @return $this
     */
    public function setSubject($subject)
    {
        if (!$this->setHeaderFieldModel('Subject', $subject)) {
            $this->getHeaders()->addTextHeader('Subject', $subject);