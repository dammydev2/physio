<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\HttpKernel\Event;

use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\HttpKernelInterface;

/**
 * Base class for events thrown in the HttpKernel component.
 *
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
class KernelEvent extends Event
{
    private $kernel;
    private $request;
    private $requestType;

    /**
     * @param HttpKernelInterface $kernel      The kernel in which this event was thrown
     * @param Request             $request     The request the kernel is currently processing
     * @param int                 $requestType The request type the kernel is currently processing; one of
     *                                         HttpKernelInterface::MASTER_REQUEST or HttpKernelInterface::SUB_REQUEST
     */
    public function __construct(HttpKer