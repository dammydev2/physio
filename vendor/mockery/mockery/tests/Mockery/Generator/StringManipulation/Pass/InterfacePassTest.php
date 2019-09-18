<?php

/*
 * This file is part of the Monolog package.
 *
 * (c) Jordi Boggiano <j.boggiano@seld.be>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Monolog\Formatter;

use Exception;
use Monolog\Utils;
use Throwable;

/**
 * Encodes whatever record data is passed to it as json
 *
 * This can be useful to log to databases or remote APIs
 *
 * @author Jordi Boggiano <j.boggiano@seld.be>
 */
class JsonFormatter extends NormalizerFormatter
{
    const BATCH_MODE_JSON = 1;
    const BATCH_MODE_NEWLINES = 2;

    protected $batchMode;
    protected $appendNewline;

    /**
     * @var bool
     */
    protected $includeStacktraces = false;

    /**
     * @param int $batchMode
     * @param bool $appendNewline
     */
    public function __construct($batchMode = self::BATCH_MODE_JSON, $appendNewline = true)
    {
        $this->batchMode = $batchMode;
        $this->appendNewline = $appendNewline;
    }

    /**
     * The batch mode option configures the formatting style for
     * multiple records. By default, multiple records will be
     * formatted as a JSON-encoded array. However, for
     * compatibility with some API endpoints, alternative styles
     * are available.
     *
     * @return int
     */
    public function getBatchMode()
    {
        return $this->batchMode;
    }

    /**
     * True if newlines are appended to every formatted record
     *
     * @return bool
     */
    public function isAppendingNewlines()
    {
        return $this->appendNewline;
    }

    /**
     * {@inheritdoc}
     */
    public function format(array $record)
    {
        return $this->toJson($this->normalize($record), true) . ($this->appendNewline ? "\n" : '');
    }

    /**
     * {@inheritdoc}
     */
    public function formatBatch(array $records)
    {
        switch ($this->batchMode) {
            case static::BATCH_MODE_NEWLINES: