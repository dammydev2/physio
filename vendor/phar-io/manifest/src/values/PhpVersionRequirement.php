<?php

namespace PharIo\Version;

class VersionConstraintValue {
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
     * @var string
     */
    private $label = '';

    /**
     * @var string
     */
    private $buildMetaData = '';

    /**
     * @var string
     */
    private $versionString = '';

    /**
     * @param string $versionString
     */
    public function __construct($versionString) {
        $this->versionString = $versionString;

        $this->parseVersion($versionString);
    }

    /**
     * @return string
     */
    public function getLabel() {
        return $this->label;
    }

    /**
     * @return strin