<?php
namespace Prettus\Repository\Generators\Commands;

use File;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Prettus\Repository\Generators\BindingsGenerator;
use Prettus\Repository\Generators\FileAlreadyExistsException;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class BindingsCommand extends Command
{

    /**
     * The name of command.
     *
     * @var string
     */
    protected $name = 'make:bindings';

    /**
     * The description of command.
     *
     * @var string
     */
    protected $description = 'Add repository bindings to service provider.';

    /**
     * The type of class being generated.