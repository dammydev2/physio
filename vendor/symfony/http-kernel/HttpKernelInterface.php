<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\HttpKernel\DataCollector;

use Symfony\Component\Debug\Exception\SilencedErrorContext;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Log\DebugLoggerInterface;

/**
 * LogDataCollector.
 *
 * @author Fabien Potencier <fabien@symfony.com>
 */
class LoggerDataCollector extends DataCollector implements LateDataCollectorInterface
{
    private $logger;
    private $containerPathPrefix;
    private $currentRequest;
    private $requestStack;

    public function __construct($logger = null, string $containerPathPrefix = null, RequestStack $requestStack = null)
    {
        if (null !== $logger && $logger instanceof DebugLoggerInterface) {
            $this->logger = $logger;
        }

        $this->containerPathPrefix = $containerPathPrefix;
        $this->requestStack = $requestStack;
    }

    /**
     * {@inheritdoc}
     */
    public function collect(Request $request, Response $response, \Exception $exce