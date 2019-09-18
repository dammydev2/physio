<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\HttpKernel\HttpCache;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Esi implements the ESI capabilities to Request and Response instances.
 *
 * For more information, read the following W3C notes:
 *
 *  * ESI Language Specification 1.0 (http://www.w3.org/TR/esi-lang)
 *
 *  * Edge Architecture Specification (http://www.w3.org/TR/edge-arch)
 *
 * @author Fabien Potencier <fabien@symfony.com>
 */
class Esi extends AbstractSurrogate
{
    public function getName()
    {
        return 'esi';
    }

    /**
     * {@inheritdoc}
     */
    public function addSurrogateControl(Response $response)
    {
        if (false !== strpos($response->getContent(), '<esi:include')) {
            $response->headers->set('Surrogate-Control', 'content="ESI/1.0"');
        }
    }

    /**
     * {@inheritdoc}
     */
    public function renderIncludeTag($uri, $alt = null, $ignoreErrors = true, $comment = '')
    {
        $html = sprintf('<esi:include src="%s"%s%s />',
            $uri,
            $ignoreErrors ? ' onerror="continue"' : '',
            $alt ? sprintf(' alt="%s"', $alt) : ''
        );

        if (!empty($comment)) {
            return sprintf("<esi:comment text=\"%s\" />\n%s", $comment, $html);
        }

        return $html;
    }

    /**
     * {@inheritdoc}
     */
    public function process(Request $request, Response $response)
    {
        $type = $response->headers->get('Content-Type');
        if (empty($type)) {
            $type = 'text/html';
        }

        $parts = explode(';', $type);
        if (!\in_array($parts[0], $this->contentTypes)) {
            return $response;
        }

        // we don't use a proper XML parser here as we can have ESI tags in a plain text response
        $content = $response->getContent();
        $content = preg_replace('#<esi\:remove>.*?</esi\:remove>#s', '', $content);
        $cont