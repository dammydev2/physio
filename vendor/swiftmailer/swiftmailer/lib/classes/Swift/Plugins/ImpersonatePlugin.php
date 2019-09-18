<?php

/*
 * This file is part of SwiftMailer.
 * (c) 2004-2009 Chris Corbyn
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * A generic IoBuffer implementation supporting remote sockets and local processes.
 *
 * @author     Chris Corbyn
 */
class Swift_Transport_StreamBuffer extends Swift_ByteStream_AbstractFilterableInputStream implements Swift_Transport_IoBuffer
{
    /** A primary socket */
    private $stream;

    /** The input stream */
    private $in;

    /** The output stream */
    private $out;

    /** Buffer initialization parameters */
    private $params = [];

    /** The ReplacementFilterFactory */
    private $replacementFactory;

    /** Translations performed on data being streamed into the buffer */
    private $translations = [];

    /**
     * Create a new StreamBuffer using $replacementFactory for transformations.
     */
    public function __construct(Swift_ReplacementFilterFactory $replacementFactory)
    {
        $this->replacementFactory = $replacementFactory;
    }

    /**
     * Perform any initialization needed, using the given $params.
     *
     * Parameters will vary depending upon the type of IoBuffer used.
     */
    public function initialize(array $params)
    {
        $this->params = $params;
        switch ($params['type']) {
            case self::TYPE_PROCESS:
                $this->establishProcessConnection();
                break;
            case self::TYPE_SOCKET:
            default:
                $this->establishSoc