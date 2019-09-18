<?php

namespace InfyOm\Generator\Generators\VueJs;

use InfyOm\Generator\Common\CommandData;
use InfyOm\Generator\Generators\BaseGenerator;
use InfyOm\Generator\Utils\FileUtil;

class ModelJsConfigGenerator extends BaseGenerator
{
    /** @var CommandData */
    private $commandData;

    /** @var string */
    private $path;

    /** @var string */
    private $fileName;

    /** @var string */
    private $templateType;

    /** @var array */
    private $htmlFields;

    public function __construct(CommandData $commandData)
    {
        $this->commandData = $commandData;
        $this->path = $commandData->config->modelJsPath;
        $this->fileName = $this->commandData->config->mCamel.'-config.js';
    }

    public function generate()
    {
        if (!file_exists($this->path)) {
            mkdir($this->path, 0755, true);
        }
        $this->commandData->commandComment("\nGenerating VueJsConfigModel...");
        $this->generateModelJs();
        $this->commandData->commandComment('ModelJsConfig created.');
    }

    private function generateModelJs()
    {
        $templateData = get_template('vuejs.js.model-config', 'laravel-generator');
        $fieldsRow = '';
        $i = 0;
        $lenghtFields = count($this->commandData->fields);
        foreach ($this->commandData->fields as $field) {
            if ($i == $lenghtFields - 1) {
                $fieldsRow .= "\t".$field->n