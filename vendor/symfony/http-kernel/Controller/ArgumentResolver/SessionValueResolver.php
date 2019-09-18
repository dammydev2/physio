<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\HttpKernel\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * TestSessionListener.
 *
 * Saves session in test environment.
 *
 * @author Bulat Shakirzyanov <mallluhuct@gmail.com>
 * @author Fabien Potencier <fabien@symfony.com>
 */
abstract class AbstractTestSessionListener implements EventSubscriberInterface
{
    private $sessionId;
    private $sessionOptions;

    public function __construct(array $sessionOptions = [])
    {
        $this->sessionOptions = $sessionOptions;
    }

    public function onKernelRequest(GetResponseEvent $event)
    {
        if (!$event->isMasterRequest()) {
            return;
        }

        // bootstrap the session
        $session = $this->