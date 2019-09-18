<?php
/*
 * This file is part of sebastian/global-state.
 *
 * (c) Sebastian Bergmann <sebastian@phpunit.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace SebastianBergmann\GlobalState;

use ReflectionProperty;

/**
 * Restorer of snapshots of global state.
 */
class Restorer
{
    /**
     * Deletes function definitions that are not defined in a snapshot.
     *
     * @throws RuntimeException when the uopz_delete() function is not available
     *
     * @see    https://github.com/krakjoe/uopz
     */
    public function restoreFunctions(Snapshot $snapshot)
    {
        if (!\function_exists('uopz_delete')) {
            throw new RuntimeException('The uopz_delete() function is required for this operation');
        }

        $functions = \get_defined_functions();

        foreach (\array_diff($functions['user'], $snapshot->functions()) as $function) {
            uopz_delete($function);
        }
    }

    /**
     * Restores all global and super-global variables from a snapshot.
     */
    public function restoreGlobalVariables(Snapshot $snapshot)
    {
        $superGlobalArrays = $snapshot->superGlobalArrays();

        foreach ($superGlobalArrays as $superGlobalArray) {
            $this->restoreSuperGlobalArray($snapshot, $superGlobalArray);
        }

        $globalVariables = $snapshot->globalVariables();

        foreach (\array_keys($GLOBALS) as $key) {
            if ($key != 'GLOBALS' &&
                !\in_array($key, $superGlobalArrays) &&
                !$snapshot->blacklist()->isGlobalVariableBlacklisted($key)) {
                if (\array_key_exists($key, $globalVariables)) {
                    $GLOBALS[$key] = $globalVariables[$key];
                } else {
                    unset($GLOBALS[$key]);
         