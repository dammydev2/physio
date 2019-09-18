<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\HttpKernel\Fragment;

use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\HttpKernel\Controller\ControllerReference;

/**
 * Renders a URI that represents a resource fragment.
 *
 * This class handles the rendering of resource fragments that are included into
 * a main resource. The handling of the rendering is managed by specialized renderers.
 *
 * @author Fabien Potencier <fabien@symfony.com>
 *
 * @see FragmentRendererInterface
 */
class FragmentHandler
{
    private $debug;
    private $renderers = [];
    private $requestStack;

    /**
     * @param RequestStack                $requestStack The Request stack that controls the lifecycle of requests
     * @param FragmentRendererInterface[] $renderers    An array of FragmentRendererInterface instances
     * @param bool                        $debug        Whether the debug mode is enabled or not
     */
    public function __construct(RequestStack $requestStack, array $renderers = [], bool $debug = false)
    {
        $this->requestStack = $requestStack;
        foreach ($renderers as $renderer) {
            $this->addRenderer($renderer);
        }
        $this->debug = $debug;
    }

    /**
     * Adds a renderer.
     */
    public function addRenderer(FragmentRendererInterface $renderer)
    {
        $this->renderers[$renderer->getName()] = $renderer;
    }

    /**
     * Renders a URI and returns the Response content.
     *
     * Available options:
     *
     *  * ignore_errors: true to return an empty string in case of an error
     *
     * @param string|ControllerReference $uri      A URI as a string or a C