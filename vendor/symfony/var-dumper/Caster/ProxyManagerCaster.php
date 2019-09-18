<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\VarDumper\Server;

use Symfony\Component\VarDumper\Cloner\Data;
use Symfony\Component\VarDumper\Dumper\ContextProvider\ContextProviderInterface;

/**
 * Forwards serialized Data clones to a server.
 *
 * @author Maxime Steinhausser <maxime.steinhausser@gmail.com>
 */
class Connection
{
    private $host;
    private $contextProviders;
    private $socket;

    /**
     * @param string                     $host             The server h