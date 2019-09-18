<?php

namespace Illuminate\Console;

use Illuminate\Support\Str;
use InvalidArgumentException;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class Parser
{
    /**
     * Parse the given console command definition into an array.
     *
     * @param  string  $expression
     * @return array
     *
     * @throws \InvalidArgumentException
     */
    public static function parse($expression)
    {
        $name = static::name($expression);

        if (preg_match_all('/\{\s*(.*?)\s*\}/', $expression, $matches)) {
            if (count($matches[1])) {
                return array_merge([$name], static::parameters($matches[1]));
            }
        }

        return [$name, [], []];
    }

    /**
     * Extract the name of the command from the expression.
     *
     * @param  string  $expression
     * @return string
     *
     * @throws \InvalidArgumentException
     */
    protected static function name($expression)
    {
        if (trim($expression) === '') {
            throw new InvalidArgumentException('Console command definition is empty.');
        }

        if (! preg_match('/[^\s]+/', $expression, $matches)) {
            throw new InvalidArgumentException('Unable to determine command name from signature.');
        }

        return $matches[0];
    }

    /**
     * Extract all of the parameters from the tokens.
     *
     * @param  array  $tokens
     * @return array
     */
    protected static function parameters(array $tokens)
    {
        $arguments = [];

        $options = [];

        foreach ($tokens as $token) {
            if (preg_match('/-{2,}(.*)/', $token, $matches)) {
                $options[] = static::parseOption($matches[1]);
            } else {
                $arguments[] = static::parseArgument($token);
            }
        }

        return [$arguments, $options];
    }

    /**
     * Parse an argument expression.
     *
     * @param  string  $token
     * @return \Symfony\Component\Console\Input\InputArgument
     */
    protected static function parseArgument($token)
    {
        [$token, $description] = static::extractDescription($token);

        switch (true) {
            case Str::endsWith($token, '?*'):
                return new InputArgument(trim($token, '?*'), InputArgument::IS_ARRAY, $description);
            case Str::endsWith($token, '*'):
                return new InputArgument(trim($token, '*'), InputArgument::IS_ARRAY | InputArgument::REQUIRED, $description);
            case Str::endsWith($token, '?'):
                retur