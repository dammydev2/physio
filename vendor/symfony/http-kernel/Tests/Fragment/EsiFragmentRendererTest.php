<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\Mime;

use Symfony\Component\Mime\Exception\LogicException;
use Symfony\Component\Mime\Part\AbstractPart;
use Symfony\Component\Mime\Part\DataPart;
use Symfony\Component\Mime\Part\Multipart\AlternativePart;
use Symfony\Component\Mime\Part\Multipart\MixedPart;
use Symfony\Component\Mime\Part\Multipart\RelatedPart;
use Symfony\Component\Mime\Part\TextPart;

/**
 * @author Fabien Potencier <fabien@symfony.com>
 *
 * @experimental in 4.3
 */
class Email extends Message
{
    const PRIORITY_HIGHEST = 1;
    const PRIORITY_HIGH = 2;
    const PRIORITY_NORMAL = 3;
    const PRIORITY_LOW = 4;
    const PRIORITY_LOWEST = 5;

    private const PRIORITY_MAP = [
        self::PRIORITY_HIGHEST => 'Highest',
        self::PRIORITY_HIGH => 'High',
        self::PRIORITY_NORMAL => 'Normal',
        self::PRIORITY_LOW => 'Low',
        self::PRIORITY_LOWEST => 'Lowest',
    ];

    private $text;
    private $textCharset;
    private $html;
    private $htmlCharset;
    private $attachments = [];

    /**
     * @return $this
     */
    public function subject(string $subject)
    {
        return $this->setHeaderBody('Text', 'Subject', $subject);
    }

    public function getSubject(): ?string
    {
        return $this->getHeaders()->getHeaderBody('Subject');
    }

    /**
     * @return $this
     */
    public function date(\DateTimeInterface $dateTime)
    {
        return $this->setHeaderBody('Date', 'Date', $dateTime);
    }

    public function getDate(): ?\DateTimeImmutable
    {
        return $this->getHeaders()->getHeaderBody('Date');
    }

    /**
     * @param Address|string $address
     *
     * @return $this
     */
    public function returnPath($address)
    {
        return $this->setHeaderBody('Path', 'Return-Path', Address::create($address));
    }

    public function getReturnPath(): ?Address
    {
        return $this->getHeaders()->getHeaderBody('Return-Path');
    }

    /**
     * @param Address|string $address
     *
     * @return $this
     */
    public function sender($address)
    {
        return $this->setHeaderBody('Mailbox', 'Sender', Address::create($address));
    }

    public function getSender(): ?Address
    {
        return $this->getHeaders()->getHeaderBody('Sender');
    }

    /**
     * @param Address|NamedAddress|string $addresses
     *
     * @return $this
     */
    public function addFrom(...$addresses)
    {
        return $this->addListAddressHeaderBody('From', $addresses);
    }

    /**
     * @param Address|NamedAddress|string $addresses
     *
     * @return $this
     */
    public function from(...$addresses)
    {
        return $this->setListAddressHeaderBody('From', $addresses);
    }

    /**
     * @return (Address|NamedAddress)[]
     */
    public function getFrom(): array
    {
        return $this->getHeaders()->getHeaderBody('From') ?: [];
    }

    /**
     * @param Address|string $addresses
     *
     * @return $this
     */
    public function addReplyTo(...$addresses)
    {
        return $this->addListAddressHeaderBody('Reply-To', $addresses);
    }

    /**
     * @param Address|string $addresses
     *
     * @return $this
     */
    public function replyTo(...$addresses)
    {
        return $this->setListAddressHeaderBody('Reply-To', $addresses);
    }

    /**
     * @return Address[]
     */
    public function getReplyTo(): array
    {
        return $this->getHeaders()->getHeaderBody('Reply-To') ?: [];
    }

    /**
     * @param Address|NamedAddress|string $addresses
     *
     * @return $this
     */
    public function addTo(...$addresses)
    {
        return $this->addListAddressHeaderBody('To', $addresses);
    }

    /**
     * @param Address|NamedAddress|string $addresses
     *
     * @return $this
     */
    public function to(...$addresses)
    {
        return $this->setListAddressHeaderBody('To', $addre