<?php

namespace Illuminate\Foundation\Console;

use Illuminate\Support\Arr;
use Illuminate\Console\Command;
use League\Flysystem\MountManager;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\ServiceProvider;
use League\Flysystem\Filesystem as Flysystem;
use League\Flysystem\Adapter\Local as LocalAdapter;

class VendorPublishCommand extends Command
{
    /**
     * The filesystem instance.
     *
     * @var \Illuminate\Filesystem\Filesystem
     */
    protected $files;

    /**
     * The provider to publish.
     *
     * @var string
     */
    protected $provider = null;

    /**
     * The tags to publish.
     *
     * @var array
     */
    protected $tags = [];

    /**
     * The console command signature.
     *
     * @var string
     */
    protected $signature = 'vendor:publish {--force : Overwrite any existing files}
                    {--all : Publish assets for all service providers without prompt}
                    {--provider= : The service provider that has assets you want to publish}
                    {--tag=* : One or many tags that have assets you want to publish}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Publish any publishable assets from vendor packages';

    /**
     * Create a new command instance.
     *
     * @param  \Illuminate\Filesystem\Filesystem  $files
     * @return void
     */
    public function __construct(Filesystem $files)
    {
        parent::__construct();

        $this->files = $files;
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $this->determineWhatShouldBePublished();

        foreach ($this->tags ?: [null] as $tag) {
            $this->publishTag($tag);
        }

        $this->info('Publishing complete.');
    }

    /**
     * Determine the provider or tag(s) to publish.
     *
     * @return void
     */
    protected function determineWhatShouldBePublished()
    {
        if ($this->option('all')) {
            return;
        }

        [$this->provider, $this->tags] = [
            $this->option('provider'), (array) $this->option('tag'),
        ];

        if (! $this->provider && ! $this->tags) {
            $this->promptForProviderOrTag();
        }
    }

    /**
     * Prompt for which provider or tag to publish.
     *
     * @return void
     */
    protected function promptForProviderOrTag()
    {
        $choice = $this->choice(
            "Which provider or tag's files would you like to publish?",
            $choices = $this->publishableChoices()
        );

        if ($choice == $choices[0] || is_null($choice)) {
            return;
        }

        $this->parseChoice($choice);
    }

    /**
     * The choices available via the prompt.
     *
     * @