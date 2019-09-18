<?php

/*
 * This file is part of SwiftMailer.
 * (c) 2004-2009 Chris Corbyn
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * An abstract means of reading and writing data in terms of characters as opposed
 * to bytes.
 *
 * Classes implementing this interface may use a subsystem which requires less
 * memory than working with large strings of data.
 *
 * @author Chris Corbyn
 */
interface Swift_CharacterStream
{
    /**
     * Set the character set used in this CharacterStream.
     *
     * @param string $charset
     */
    public function setCharacterSet($charset);

    /**
     * Set the CharacterReaderFactory for multi charset support.
     *
     * @param Swift_CharacterReaderFactory $factory
     */
    public function setCharacterReaderFactory(Swift_CharacterReaderFactory $factory);

    /**
     * Overwrite this character stream using the byte sequence in the byte stream.
     *
     * @param Swift_OutputByteStream $os output stream to read from
     */
    public function importByteStream(Swift_OutputByteStream $os);

    /**
     * Import a string a bytes into this CharacterStream, overwriting any existing
     * data in the stream.
     *
     * @param string $string
     */
    public function importString($string);

    /**
     * Read $length characters from the stream and move the internal 