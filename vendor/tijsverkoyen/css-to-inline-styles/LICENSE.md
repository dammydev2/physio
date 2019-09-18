#!/usr/bin/env php
<?php
error_reporting(E_ALL);
// Possible options and their default values.
$options = array(
    'output' => 'swagger.json',
    'stdout' => false,
    'exclude' => null,
    'bootstrap' => false,
    'help' => false,
    'version' => false,
    'debug' => false,
);
$aliases = array(
    'o' => 'output',
    'e' => 'exclude',
    'b' => 'bootstrap',
    'v' => 'version',
    'h' => 'help',
    'd' => 'debug'
);
$needsArgument = array(
    'output',
    'exclude',
    'bootstrap',
);
$paths = array();
$error = false;
try {
    // Parse cli arguments
    for ($i = 1; $i < $argc; $i++) {
        $arg = $argv[$i];
        if (substr($arg, 0, 2) === '--') { // longopt
            $option = substr($arg, 2);
        } elseif ($arg[0] === '-') { // shortopt
            if (array_key_exists(substr($arg, 1), $aliases)) {
                $option = $aliases[$arg[1]];
            } else {
                throw new Exception('Unknown option: "' . $arg . '"');
            }
        } else {
            $paths[] = $arg;
            continue;
        }
        if (array_key_exists($option, $options) === false) {
            throw new Exception('Unknown option: "' . $arg . '"');
        }
        if (in_array($option, $needsArgument)) {
            if (empty($argv[$i + 1]) || $argv[$i + 1][0] === '-') {
                throw new Exception('Missing argument for "' . $arg . '"');
            }
            $options[$option] = $argv[$i + 1];
            $i++;
        }