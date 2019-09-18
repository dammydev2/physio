<?php

namespace InfyOm\Generator\Commands;

use Illuminate\Console\Command;
use InfyOm\Generator\Common\CommandData;
use InfyOm\Generator\Generators\API\APIControllerGenerator;
use InfyOm\Generator\Generators\API\APIRequestGenerator;
use InfyOm\Generator\Generators\API\APIRoutesGenerator;
use InfyOm\Generator\Generators\API\APITestGenerator;
use InfyOm\Generator\Generators\MigrationGenerator;
use InfyOm\Generator\Generators\ModelGenerator;
use InfyOm\Generator\Generators\RepositoryGenerator;
use InfyOm\Generator\Generators\RepositoryTestGenerator;
use InfyOm\Generator\Generators\Scaffold\ControllerGenerator;
use InfyOm\Generator\Generators\Scaffold\MenuGenerator;
use InfyOm\Generator\Generators\Scaffold\RequestGenerator;
use InfyOm\Generator\Generators\Scaffold\RoutesGenerator;
use InfyOm\Generator\Generators\Scaffold\ViewGenerator;
use InfyOm\Generator\Generators\TestTraitGenerator;
use InfyOm\Generator\Gen