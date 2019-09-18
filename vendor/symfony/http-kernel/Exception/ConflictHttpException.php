<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\HttpKernel\Tests;

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\HttpKernel\Client;
use Symfony\Component\HttpKernel\Tests\Fixtures\TestClient;

/**
 * @group time-sensitive
 */
class ClientTest extends TestCase
{
    public function testDoRequest()
    {
        $client = new Client(new TestHttpKernel());

        $client->request('GET', '/');
        $th