<?php

/*
 * This file is part of SwiftMailer.
 * (c) 2004-2009 Chris Corbyn
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * A null KeyCache that does not cache at all.
 *
 * @author Chris Corbyn
 */
class Swift_KeyCache_NullKeyCache implements Swift_KeyCache
{
    /**
     * Set a string into the cache under $itemKey for the namespace $nsKey.
     *
     * @see MODE_WRITE, MODE_APPEND
     *
     * @param string $nsKey
     * @param string $itemKey
     * @param string $string
     * @param int    $mode
     */
    public function setString($nsKey, $itemKey, $string, $mode)
    {
    }

    /**
     * Set a ByteStream into the cache under $itemKey for the namespace $nsKey.
     *
     * @see MODE_WRITE, MODE_APPEND
     *
     * @param string $nsKey
     * @param string $itemKey
     * @param int    $mode
     */
    public function importFromByteStream($nsKey, $itemKey, Swift_OutputByteStream $os, $mode)
    {
    }

    /**
     * Provides a