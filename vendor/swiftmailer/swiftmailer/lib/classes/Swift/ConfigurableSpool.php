<?php

/*
 * This file is part of SwiftMailer.
 * (c) 2004-2009 Chris Corbyn
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * The EventDispatcher which handles the event dispatching layer.
 *
 * @author Chris Corbyn
 */
class Swift_Events_SimpleEventDispatcher implements Swift_Events_EventDispatcher
{
    /** A map of event types to their associated listener types */
    private $eventMap = [];

    /** Event listeners bound to this dispatcher */
    private $listeners = [];

    /** Listeners queued to have an Event bubbled up the stack to them */
    private $bubbleQueue = [];

    /**
     * Create a new EventDispatcher.
     */
    public function __construct()
    {
        $this->eventMap = [
            'Swift_Events_CommandEvent' => 'Swift_Events_CommandListener',
            'Swift_Events_ResponseEvent' => 'Swift_Events_ResponseListener',
            'Swift_Events_SendEvent' => 'Swift_Events_SendListener',
            'Swift_Events_TransportChangeEvent' => 'Swift_Events_TransportChangeListener',
            'Swift_Events_TransportExceptionEvent' => 'Swift_Events_TransportExceptionListener',
            ];
    }

    /**
     * Create a new SendEvent for $source and $message.
     *
     * @return Swift_Events_SendEvent
     */
    public fu