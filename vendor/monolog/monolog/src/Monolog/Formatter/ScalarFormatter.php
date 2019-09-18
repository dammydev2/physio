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

use RollbarNotifier;
use Exception;
use Monolog\Logger;

/**
 * Sends errors to Rollbar
 *
 * If the context data contains a `payload` key, that is used as an array
 * of payload options to RollbarNotifier's report_message/report_exception methods.
 *
 * Rollbar's context info will contain the context + extra keys from the log record
 * merged, and then on top of that a few keys:
 *
 *  - level (rollbar level name)
 *  - monolog_level (monolog level name, raw level, as rollbar only has 5 but monolog 8)
 *  - channel
 *  - datetime (unix timestamp)
 *
 * @author Paul Statezny <paulstatezny@gmail.com>
 */
class RollbarHandler extends AbstractProcessingHandler
{
    /**
     * Rollbar notifier
     *
     * @var RollbarNotifier
     */
    protected $rollbarNotifier;

