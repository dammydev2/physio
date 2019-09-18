<?php

/*
 * This file is part of the Monolog package.
 *
 * (c) Jordi Boggiano <j.boggiano@seld.be>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Monolog;

use InvalidArgumentException;

/**
 * Monolog log registry
 *
 * Allows to get `Logger` instances in the global scope
 * via static method calls on this class.
 *
 * <code>
 * $application = new Monolog\Logger('application');
 * $api = new Monolog\Logger('api');
 *
 * Monolog\Registry::addLogger($application);
 * Monolog\Registry::addLogger($api);
 *
 * function testLogger()
 * {
 *     Monolog\Registry::api()->addError('Sent to $api Logger instance');
 *     Monolog\Registry::application()->addError('Sent to $application Logger instance');
 * }
 * </code>
 *
 * @author Tomas Tatarko <tomas@tatarko.sk>
 */
class Registry
{
    /**
     * List of all loggers in the registry (by named indexes)
     *
     * @var Logger[]
     */
    private static $loggers = array();

    /**
     * Adds new logging channel to the registry
     *
     * @param  Logger                    $logger    Instance of the logging channel
     * @param  string|null               $name      Name of the logging channel ($logger->getName() by default)
     * @param  bool                      $overwrite Overwrite instance in the registry if the given name already exists?
     * @throws \InvalidArgumentException If $overwrite set to false and named Logger instance already exists
     */
    pub