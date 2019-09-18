<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\VarDumper\Dumper;

use Symfony\Component\VarDumper\Cloner\Cursor;
use Symfony\Component\VarDumper\Cloner\Stub;

/**
 * CliDumper dumps variables for command line output.
 *
 * @author Nicolas Grekas <p@tchwork.com>
 */
class CliDumper extends AbstractDumper
{
    public static $defaultColors;
    public static $defaultOutput = 'php://stdout';

    protected $colors;
    protected $maxStringWidth = 0;
    protected $styles = [
        // See http://en.wikipedia.org/wiki/ANSI_escape_code#graphics
        'default' => '38;5;208',
        'num' => '1;38;5;38',
        'const' => '1;38;5;208',
        'str' => '1;38;5;113',
        'note' => '38;5;38',
        'ref' => '38;5;247',
        'public' => '',
        'protected' => '',
        'private' => '',
        'meta' => '38;5;170',
        'key' => '38;5;113',
        'index' => '38;5;38',
    ];

    protected static $controlCharsRx = '/[\x00-\x1F\x7F]+/';
    protected static $controlCharsMap = [
        "\t" => '\t',
        "\n" => '\n',
        "\v" => '\v',
        "\f" => '\f',
        "\r" => '\r',
        "\033" => '\e',
    ];

    protected $collapseNextHash = false;
    protected $expandNextHash = false;

    /**
     * {@inheritdoc}
     */
    public function __construct($output = null, string $charset = null, int $flags = 0)
    {
        parent::__construct($output, $charset, $flags);

        if ('\\' === \DIRECTORY_SEPARATOR && !$this->isWindowsTrueColor()) {
            // Use only the base 16 xterm c