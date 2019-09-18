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
 * Represents a cookie.
 *
 * @author Johannes M. Schmitt <schmittjoh@gmail.com>
 */
class Cookie
{
    protected $name;
    protected $value;
    protected $domain;
    protected $expire;
    protected $path;
    protected $secure;
    protected $httpOnly;
    private $raw;
    private $sameSite;
    private $secureDefault = false;

    const SAMESITE_LAX = 'lax';
    const SAMESITE_STRICT = 'strict';

    /**
     * Creates cookie from raw header string.
     *
     * @param string $cookie
     * @param bool   $decode
     *
     * @return static
     */
    public static function fromString($cookie, $decode = false)
    {
        $data = [
            'expires' => 0,
            'path' => '/',
            'domain' => null,
            'secure' => false,
            'httponly' => false,
            'raw' => !$decode,
            'samesite' => null,
        ];

        $parts = HeaderUtils::split($cookie, ';=');
        $part = array_shift($parts);

        $name = $decode ? urldecode($part[0]) : $part[0];
        $value = isset($part[1]) ? ($decode ? urldecode($part[1]) : $part[1]) : null;

        $data = HeaderUtils::combine($parts) + $data;

        if (isset($data['max-age'])) {
      