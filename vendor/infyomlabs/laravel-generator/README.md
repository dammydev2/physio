<?php

namespace InfyOm\Generator\Generators;

use InfyOm\Generator\Common\CommandData;
use InfyOm\Generator\Utils\FileUtil;
use InfyOm\Generator\Utils\GeneratorFieldsInputUtil;

class TestTraitGenerator extends BaseGenerator
{
    /** @var CommandData */
    private $commandData;

    /** @var string */
    private $path;

    /** @var string */
    private $fileName;

    public function __construct(CommandData $commandData)
    {
        $this->commandData = $commandData;
        $this->path = $commandData->config->pathApiTestTraits;
        $this->fileName = 'Make'.$this->commandData->modelName.'Trait.php';
    }

    public function generate()
    {
        $templateData = get_template('test.trait', 'laravel-generator');

        $templateData = $this->fillTemplate($templateData);

        FileUtil::createFile($this->path, $this->fileName, $templateData);

        $this->commandData->commandObj->comment("\nTestTrait created: ");
        $this->commandData->commandObj->info($this->fileName);
    }

    private function fillTemplate($templateData)
    {
    