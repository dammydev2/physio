<?php

/*
 * This file is part of SwiftMailer.
 * (c) 2004-2009 Chris Corbyn
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * A KeyCache which streams to and from disk.
 *
 * @author Chris Corbyn
 */
class Swift_KeyCache_DiskKeyCache implements Swift_KeyCache
{
    /** Signal to place pointer at start of file */
    const POSITION_START = 0;

    /** Signal to place pointer at end of file */
    const POSITION_END = 1;

    /** Signal to leave pointer in whatever position it currently is */
    const POSITION_CURRENT = 2;

    /**
     * An InputStream for cloning.
     *
     * @var Swift_KeyCache_KeyCacheInputStream
     */
    private $stream;

    /**
     * A path to write to.
     *
     * @var string
     */
    private $path;

    /**
     * Stored keys