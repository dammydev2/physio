<?php

namespace Illuminate\Database\Console\Migrations;

use Illuminate\Console\Command;
use Illuminate\Console\ConfirmableTrait;
use Symfony\Component\Console\Input\InputOption;

class FreshCommand extends Command
{
    use ConfirmableTrait;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'migrate:fresh';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Drop all tables and re-run all migrations';

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

        $database = $this->input->getOption('database');

        if ($this->option('drop-views')) {
            $this->dropAllViews($database);

            $this->info('Dropped all views successfully.');
        }

        $this->dropAllTables($database);

        $this->info('Dropped all tables successfully.');

        $this->call('migrate', array_filter([
            '--database' => $database,
            '--path' => $this->input->getOption('path'),
            '--realpath' => $this->input->getOption('realpath'),
            '--force' => true,