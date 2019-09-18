<?php

/*
 * This file is part of SwiftMailer.
 * (c) 2004-2009 Chris Corbyn
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * Allows customization of Messages on-the-fly.
 *
 * @author Chris Corbyn
 * @author Fabien Potencier
 */
class Swift_Plugins_DecoratorPlugin implements Swift_Events_SendListener, Swift_Plugins_Decorator_Replacements
{
    /** The replacement map */
    private $replacements;

    /** The body as it was before replacements */
    private $originalBody;

    /** The original headers of the message, before replacements */
    private $originalHeaders = [];

    /** Bodies of children before they are replaced */
    private $originalChildBodies = [];

    /** The Message that was last replaced */
    private $lastMessage;

    /**
     * Create a new DecoratorPlugin with $replacements.
     *
     * The $replacements can either be an associative array, or an implementation
     * of {@link Swift_Plugins_Decorator_Replacements}.
     *
     * When using an array, it should be of the form:
     * <code>
     * $replacements = array(
    