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
 * Ssi implements the SSI capabilities to Request and Response instances.
 *
 * @author Sebastian Krebs <krebs.seb@gmail.com>
 */
class Ssi extends AbstractSurrogate
{
    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'ssi';
    }

    /**
     * {@inheritdoc}
     */
    public function addSurrogateControl(Response $response)
    {
        if (false !== strpos($response->getContent(), '<!--#include')) {
            $response->headers->set('Surrogate-Control', 'content="SSI/1.0"');
        }
    }

    /**
     * {@inheritdoc}
     */
    public function renderIncludeTag($uri, $alt = null, $ignoreErrors = true, $comment = '')
    {
        return sprintf('<!--#include virtual="%s" -->', $uri);
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

        // we don't use a proper XML parser here as we can have SSI tags in a plain text response
        $content = $response->getContent();

        $chunks = preg_split('#<!--\#include\s+(.*?)\s*-->#', $content, -1, PREG_SPLIT_DELIM_CAPTURE);
  