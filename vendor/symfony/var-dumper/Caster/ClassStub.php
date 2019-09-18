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

use Symfony\Component\VarDumper\Cloner\Data;
use Symfony\Component\VarDumper\Cloner\DumperInterface;

/**
 * Abstract mechanism for dumping a Data object.
 *
 * @author Nicolas Grekas <p@tchwork.com>
 */
abstract class AbstractDumper implements DataDumperInterface, DumperInterface
{
    const DUMP_LIGHT_ARRAY = 1;
    const DUMP_STRING_LENGTH = 2;
    const DUMP_COMMA_SEPARATOR = 4;
    const DUMP_TRAILING_COMMA = 8;

    public static $defaultOutput = 'php://output';

    protected $line = '';
    protected $lineDumper;
    protected $outputStream;
    protected $decimalPoint; // This is locale dependent
    protected $indentPad = '  ';
    protected $flags;

    private $charset;

    /**
     * @param callable|resource|string|null $output  A line dumper callable, an opened stream or an output path, defaults to static::$defaultOutput
     * @param string|null                   $charset The default character encoding to use for non-UTF8 strings
     * @param int                           $flags   A bit field of static::DUMP_* constants to fine tune dumps representation
     */
    public function __construct($output = null, string $charset = null, int $flags = 0)
    {
        $this->flags = $flags;
        $this->setCharset($charset ?: ini_get('php.output_encoding') ?: ini_get('default_charset') ?: 'UTF-8');
        $this->decimalPoint = localeconv();
        $this->decimalPoint = $this->decimalPoint['decimal_point'];
        $this->setOutput($output ?: static::$defaultOutput);
        if (!$output && \is_string(static::$defaultOutput)) {
            static::$defaultOutput = $this->outputStream;
        }
    }

    /**
     * Sets the output destination of the dumps.
     *
     * @param callable|resource|string $output A line dumper callable, an opened stream or an output path
     *
     * @return callable|resource|string The previous output destination
     */
    public function setOutput($output)
    {
        $prev = null !== $this->outputStream ? $this->outputStream : $this->lineDumper;

        if (\is_callable($output)) {
            $this->outputStream = null;
            $this->lineDumper = $output;
        } else {
            if (\is_string($output)) {
                $output = fopen($output, 'wb');
            }
            $this->outputStream = $output;
            $this->lineDumper = [$this, 'echoLine'];
        }

        return $prev;
    }

    /**
     * Sets the default character encoding to use for non-UTF8 strings.
     *
     * @param string $charset The default character encoding to use for non-UTF8 strings
     *
     * @return string The previous charset
     */
    public function setCharset($charset)
    {
        $prev = $this->charset;

        $charset = strtoupper($charset);
        $charset = null === $charset || 'UTF-8' === $charset || 'UTF8' === $charset ? 'CP1252' : $charset;

        $this->charset = $charset;

        return $prev;
    }

    /**
     * Sets the indentation pad string.
     *
     * @param string $pad A string that will be prepended to dumped lines, repeated by nesting level
     *
     * @return string The previous indent pad
     */
    public function setIndentPad($pad)
    {
        $prev = $this->indentPad;
        $this->indentPad = $pad;

        return $prev;
    }

    /**
     * Dumps a Data object.
     *
     * @param Data                               $data   A Data object
     * @param callable|reso