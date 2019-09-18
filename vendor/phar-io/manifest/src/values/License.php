<?php
/*
 * This file is part of PharIo\Version.
 *
 * (c) Arne Blankerts <arne@blankerts.de>, Sebastian Heuer <sebastian@phpeople.de>, Sebastian Bergmann <sebastian@phpunit.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PharIo\Version;

class Version {
    /**
     * @var VersionNumber
     */
    private $major;

    /**
     * @var VersionNumber
     */
    private $minor;

    /**
     * @var VersionNumber
     */
    private $patch;

    /**
     * @var PreReleaseSuffix
     */
    private $preReleaseSuffix;

    /**
     * @var string
     */
    private $versionString = '';

    /**
     * @param string $versionString
     */
    public function __construct($versionSt