<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\HttpKernel\Profiler;

use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Exception\ConflictingHeadersException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\DataCollector\DataCollectorInterface;
use Symfony\Component\HttpKernel\DataCollector\LateDataCollectorInterface;
use Symfony\Contracts\Service\ResetInterface;

/**
 * Profiler.
 *
 * @author Fabien Potencier <fabien@symfony.com>
 */
class Profiler implements ResetInterface
{
    private $storage;

    /**
     * @var DataCollectorInterface[]
     */
    private $collectors = [