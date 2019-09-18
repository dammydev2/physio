<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\HttpFoundation;

/**
 * HeaderBag is a container for HTTP headers.
 *
 * @author Fabien Potencier <fabien@symfony.com>
 */
class HeaderBag implements \IteratorAggregate, \Countable
{
    protected $headers = [];
    protected $cacheControl = [];

    /**
     * @param array $headers An array of HTTP headers
     */
    public function __construct(array $headers = [])
    {
        foreach ($headers as $key => $values) {
            $this->set($key, $values);
        }
    }

    /**
     * Returns the headers as a string.
     *
     * @return string The headers
     */
    public function __toString()
    {
        if (!$headers = $this->all()) {
            return '';
        }

        ksort($headers);
        $max = max(array_map('strlen', array_keys($headers))) + 1;
        $content = '';
        foreach ($headers as $name => $values) {
            $name = ucwords($name, '-');
            foreach ($values as $value) {
                $content .= sprintf("%-{$max}s %s\r\n", $name.':', $value);
            }
        }

        return $content;
    }

    /**
     * Returns the headers.
     *
     * @return array An array of headers
     */
    public functio