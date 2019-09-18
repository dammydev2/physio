<?php

/*
 * This file is part of SwiftMailer.
 * (c) 2004-2009 Chris Corbyn
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * A MIME Header.
 *
 * @author Chris Corbyn
 */
interface Swift_Mime_Header
{
    /** Text headers */
    const TYPE_TEXT = 2;

    /**  headers (text + params) */
    const TYPE_PARAMETERIZED = 6;

    /** Mailbox and address headers */
    const TYPE_MAILBOX = 8;

    /** Date and time headers */
    const TYPE_DATE = 16;

    /** Identification headers */
    const TYPE_ID = 32;

    /** Address path headers */
    const TYPE_PATH = 64;

    /**
     * Get the type of Header that this instance represents.
     *
     * @see TYPE_TEXT, TYPE_PARAMETERIZED, TYPE_MAILBOX
     * @see TYPE_DATE, TYPE_ID, TYPE_PATH
     *
     * @return int
     */
    public function getFieldType();

    /**
     * Set the model for the field body.
     *
     * The actual types needed will vary depending upon the type of Header.
     *
     * @param mixed $model
     */
    public function setFieldBodyModel($model);

    /**
     * Set the charset used wh