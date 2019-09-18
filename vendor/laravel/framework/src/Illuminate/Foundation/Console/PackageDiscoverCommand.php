<?php

namespace Illuminate\Foundation\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Queue\Console\TableCommand;
use Illuminate\Auth\Console\AuthMakeCommand;
use Illuminate\Foundation\Console\UpCommand;
use Illuminate\Foundation\Console\DownCommand;
use Illuminate\Auth\Console\ClearResetsCommand;
use Illuminate\Cache\Console\CacheTableCommand;
use Illuminate\Foundation\Console\ServeCommand;
use Illuminate\Foundation\Console\PresetCommand;
use Illuminate\Queue\Console\FailedTableCommand;
use Illuminate\Foundation\Console\AppNameCommand;
use Illuminate\Foundation\Console\JobMakeCommand;
use Illuminate\Database\Console\Seeds\SeedCommand;
use Illuminate\Foundation\Console\MailMakeCommand;
use Illuminate\Foundation\Console\OptimizeCommand;
use Illuminate\Foundation\Console\RuleMakeCommand;
use Illuminate\Foundation\Console\TestMakeCommand;
use Illuminate\Foundation\Console\Ev