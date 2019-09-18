<?php

namespace League\Flysystem\Adapter;

use ErrorException;
use League\Flysystem\Adapter\Polyfill\StreamedCopyTrait;
use League\Flysystem\AdapterInterface;
use League\Flysystem\Config;
use League\Flysystem\Util;
use League\Flysystem\Util\MimeType;
use RuntimeException;

class Ftp extends AbstractFtpAdapter
{
    use StreamedCopyTrait;

    /**
     * @var int
     */
    protected $transferMode = FTP_BINARY;

    /**
     * @var null|bool
     */
    protected $ignorePassiveAddress = null;

    /**
     * @var bool
     */
    protected $recurseManually = false;

    /**
     * @var bool
     */
    protected $utf8 = false;

    /**
     * @var array
     */
    protected $configurable = [
        'host',
        'port',
        'username',
        'password',
        'ssl',
        'timeout',
        'root',
        'permPrivate',
        'permPublic',
        'passive',
        'transferMode',
        'systemType',
        'ignorePassiveAddress',
        'recurseManually',
        'utf8',
        'enableTimestampsOnUnixListings',
    ];

    /**
     * @var bool
     */
    protected $isPureFtpd;

    /**
     * Set the transfer mode.
     *
     * @param int $mode
     *
     * @return $this
     */
    public function setTransferMode($mode)
    {
        $this->transferMode = $mode;

        return $this;
    }

    /**
     * Set if Ssl is enabled.
     *
     * @param bool $ssl
     *
     * @return $this
     */
    public function setSsl($ssl)
    {
        $this->ssl = (bool) $ssl;

        return $this;
    }

    /**
     * Set if passive mode should be used.
     *
     * @param bool $passive
     */
    public function setPassive($passive = true)
    {
        $this->passive = $passive;
    }

    /**
     * @param bool $ignorePassiveAddress
     */
    public function setIgnorePassiveAddress($ignorePassiveAddress)
    {
        $this->ignorePassiveAddress = $ignorePassiveAddress;
    }

    /**
     * @param bool $recurseManually
     */
    public function setRecurseManually($recurseManually)
    {
        $this->recurseManually = $recurseManually;
    }

    /**
     * @param bool $utf8
     */
    public function setUtf8($utf8)
    {
        $this->utf8 = (bool) $utf8;
    }

    /**
     * Connect to the FTP server.
     */
    public function connect()
    {
        if ($this->ssl) {
            $this->connection = ftp_ssl_connect($this->getHost(), $this->getPort(), $this->getTimeout());
        } else {
            $this->connection = ftp_connect($this->getHost(), $this->getPort(), $this->getTimeout(