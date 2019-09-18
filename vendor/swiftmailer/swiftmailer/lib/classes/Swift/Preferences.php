<?php

/*
 * This file is part of SwiftMailer.
 * (c) 2004-2009 Chris Corbyn
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * A MIME part, in a multipart message.
 *
 * @author Chris Corbyn
 */
class Swift_Mime_MimePart extends Swift_Mime_SimpleMimeEntity
{
    /** The format parameter last specified by the user */
    protected $userFormat;

    /** The charset last specified by the user */
    protected $userCharset;

    /** The delsp parameter last specified by the user */
    protected $userDelSp;

    /** The nesting level of this MimePart */
    private $nestingLevel = self::LEVEL_ALTERNATIVE;

    /**
     * Create a new MimePart with $headers, $encoder and $cache.
     *
     * @param string $charset
     */
    public function __construct(Swift_Mime_SimpleHeaderSet $headers, Swift_Mime_ContentEncoder $encoder, Swift_KeyCache $cache, Swift_IdGenerator $idGenerator, $charset = null)
    {
        parent::__construct($headers, $encoder, $cache, $idGenerator);
        $this->setContentType('text/plain');
        if (null !== $charset) {
            $this->setCharset($charset);
        }
    }

    /**
     * Set the body of this entity, either as a string, or as an instance of
     * {@link Swift_OutputByteStream}.
     *
     * @param mixed  $body
     * @param string $contentType optional
     * @param string $charset     optional
     *
     * @return $this
     */
    public function setBody($body, $contentType = null, $charset = null)
    {
        if (isset($charset)) {
            $this->setCharset($charset);
        }
        $body = $this->convertString($body);

        parent::setBody($body, $contentType);

        return $this;
    }

    /**
     * Get the character set of this entity.
     *
     * @return string
     */
    public function getCharset()
    {
        return $this->getHeaderParameter('Content-Type', 'charset');
    }

    /**
     * Set the character set of this entity.
     *
     * @param string $charset
     *
     * @return $this
     */
    public function setCharset($charset)
    {
        $this->setHeaderParameter('Content-Type', 'charset', $charset);
  