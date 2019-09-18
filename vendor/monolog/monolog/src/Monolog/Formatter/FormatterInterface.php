<?php

/*
 * This file is part of the Monolog package.
 *
 * (c) Jordi Boggiano <j.boggiano@seld.be>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Monolog\Handler;

use Monolog\Logger;

/**
 * MandrillHandler uses cURL to send the emails to the Mandrill API
 *
 * @author Adam Nicholson <adamnicholson10@gmail.com>
 */
class MandrillHandler extends MailHandler
{
    protected $message;
    protected $apiKey;

    /**
     * @param string                  $apiKey  A valid Mandrill API key
     * @param callable|\Swift_Message $message An example message for real messages, only the body will be replaced
     * @param int                     $level   The minimum logging level at which