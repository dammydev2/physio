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

use Monolog\Logger;

/**
 * Formats incoming records into an HTML table
 *
 * This is especially useful for html email logging
 *
 * @author Tiago Brito <tlfbrito@gmail.com>
 */
class HtmlFormatter extends NormalizerFormatter
{
    /**
     * Translates Monolog log levels to html color priorities.
     */
    protected $logLevels = array(
        Logger::DEBUG     => '#cccccc',
        Logger::INFO      => '#468847',
        Logger::NOTICE    => '#3a87ad',
        Logger::WARNING   => '#c09853',
        Logger::ERROR     => '#f0ad4e',
        Logger::CRITICAL  => '#FF7708',
        Logger::ALERT     => '#C12A19',
        Logger::EMERGENCY => '#000000',
    );

    /**
     * @param string $dateFormat The format of the timestamp: one supported by DateTime::format
     */
    public function __construct($dateFormat = null)
    {
        parent::__construct($dateFormat);
    }

    /**
     * Creates an HTML table row
     *
     * @param  string $th       Row header content
     * @param  string $td       Row standard cell content
     * @param  bool   $escapeTd false if td content must not be html escaped
     * @return string
     */
    protected function addRow($th, $td = ' ', $escapeTd = true)
    {
        $th = htmlspecialchars($th, ENT_NOQUOTES, 'UTF-8');
        if ($escapeTd) {
            $td = '<pre>'.htmlspecialchars($td