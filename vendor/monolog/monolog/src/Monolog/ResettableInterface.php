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
 * Sends notifications through the hipchat api to a hipchat room
 *
 * Notes:
 * API token - HipChat API token
 * Room      - HipChat Room Id or name, where messages are sent
 * Name      - Name used to send the message (from)
 * notify    - Should the message trigger a notification in the clients
 * version   - The API version to use (HipChatHandler::API_V1 | HipChatHandler::API_V2)
 *
 * @author Rafael Dohms <rafael@doh.ms>
 * @see    https://www.hipchat.com/docs/api
 */
class HipChatHandler extends SocketHandler
{
    /**
     * Use API version 1
     */
    const API_V1 = 'v1';

    /**
     * Use API version v2
     */
    const API_V2 = 'v2