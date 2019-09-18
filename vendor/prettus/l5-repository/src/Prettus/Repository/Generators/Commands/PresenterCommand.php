#!/usr/bin/env php
<?php

/*
 * This file is part of Psy Shell.
 *
 * (c) 2012-2017 Justin Hileman
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

// Try to find an autoloader for a local psysh version.
// We'll wrap this whole mess in a Closure so it doesn't leak any globals.
call_user_func(function () {
    $cwd = null;

    // Find the cwd arg (if present)
    $argv = isset($_SERVER['argv']) ? $_SERVER['argv'] : array();
    foreach ($argv as $i => $arg) {
        if ($arg === '--cwd') {
            if ($i >= count($argv) - 1) {
                echo 'Missing --cwd argument.' . PHP_EOL;
                exit(1);
            }
            $cwd = $argv[$i + 1];
            break;
        }

        if (preg_match('/^--cwd=/', $arg)) {
            $cwd = substr($arg, 6);
            break;
        }
    }

    // Or fall back to the actual cwd
    if (!isset($cwd)) {
        $cwd = getcwd();
    }

    $cwd = str_replace('\\', '/', $cwd);

    $chunks = explode('/', $cwd);
    while (!empty($chunks)) {
        $path = implode('/', $chunks);

        // Find composer.json
        if (is_file($path . '/composer.json')) {
            if ($cfg = json_decode(file_get_contents($path . '/composer.json'), true)) {
                if (isset($cfg['name']) && $cfg['name'] === 'psy/psysh') {
                    // We're inside the psysh project. Let's use the local
                    // Composer autoload.
                    if (is_file($path . '/vendor/autoload.php')) {
                        require $path . '/vendor/autoload.php';
                    }

                    return;
                }
            }
        }

        // Or a composer.lock
        if (is_file($path . '/composer.lock')) {
            if ($cfg = json_decode(file_get_contents($path . '/composer.lock'), true)) {
                foreach (array_merge($cfg['packages'], $cfg['packages-dev']) as $pkg) {
                    if (isset($pkg['name']) && $pkg['name'] === 'psy/psysh') {
                        // We're inside a project which requires psysh. We'll
                        // use the local Composer autoload.
                        if (is_file($path . '/vendor/autoload.php')) {
                            require $path . '/vendor/autoload.php';
                        }

                        return;
                    }
                }
            }
        }

        array_pop($chunks);
    }
});

// We didn't find an autoloader for a local version, so use the autoloader that
// came with this script.
if (!class_exists('Psy\Shell')) {
/* <<< */
    if (is_file(__D