<?php

namespace Illuminate\Database\Console\Migrations;

use Illuminate\Console\Command;
use Illuminate\Console\ConfirmableTrait;
use Symfony\Component\Console\Input\InputOption;

class RefreshCommand extends Command
{
    use ConfirmableTrait;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'migrate:refresh';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reset and re-run all migrations';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        if (! $this->confirmToProceed()) {
            return;
        }

        // Next we'll gather some of the options so that we can have the right options
        // to pass to the commands. This includes options such as which database to
        // use and the path to use for the migration. Then we'll run the command.
        $database = $this->input->getOption('database');

        $path = $this->input->getOption('path');

        // If t