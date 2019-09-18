<?php

namespace Doctrine\DBAL\Driver\Mysqli;

use Doctrine\DBAL\Driver\Connection;
use Doctrine\DBAL\Driver\PingableConnection;
use Doctrine\DBAL\Driver\ServerInfoAwareConnection;
use Doctrine\DBAL\ParameterType;
use mysqli;
use const MYSQLI_INIT_COMMAND;
use const MYSQLI_OPT_CONNECT_TIMEOUT;
use const MYSQLI_OPT_LOCAL_INFILE;
use const MYSQLI_READ_DEFAULT_FILE;
use const MYSQLI_READ_DEFAULT_GROUP;
use const MYSQLI_SERVER_PUBLIC_KEY;
use function defined;
use function floor;
use function func_get_args;
use function in_array;
use function ini_get;
use function mysqli_errno;
use function mysqli_error;
use function mysqli_init;
use function mysqli_options;
use function restore_error_handler;
use function set_error_handler;
use function sprintf;
use function stripos;

class MysqliConnection implements Connection, PingableConnection, ServerInfoAwareConnection
{
    /**
     * Name of the option to set connection flags
     */
    public const OPTION_FLAGS = 'flags';

    /** @var mysqli */
    