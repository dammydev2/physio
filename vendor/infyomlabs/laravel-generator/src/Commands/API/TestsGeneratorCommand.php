<?php

namespace InfyOm\Generator\Generators\VueJs;

use InfyOm\Generator\Common\CommandData;
use InfyOm\Generator\Generators\BaseGenerator;
use InfyOm\Generator\Utils\FileUtil;
use InfyOm\Generator\Utils\GeneratorFieldsInputUtil;

class ViewGenerator extends BaseGenerator
{
    /** @var CommandData */
    private $commandData;

    /** @var string */
    private $path;

    /** @var string */
    private $templateType;

    /** @var array */
    private $htmlFields;

    public function __construct(CommandData $commandData)
    {
        $this->commandData = $commandData;
        $this->path = $commandData->config->pathViews;
        $this->templateType = config('infyom.laravel_generator.templates', 'core-templates');
    }

    public function generate()
    {
        if (!file_exists($this->path)) {
            mkdir($this->path, 0755, true);
        }

        $this->commandData->commandComment("\nGenerating Views...");
        $this->generateTable();
        $this->generateIndex();
        $this->generateFields();
        $this->generateForm();
        $this->generateShow();
        $this->generateDelete();
        $this->commandData->commandComment('Views created.');
    }

    private function generateTable()
    {
        $templateData = $this->generateBladeTableBody();

        FileUtil::createFile($this->path, 'table.blade.php', $templateData);

        $this->commandData->commandInfo('table.blade.php created');
    }

    private function generateBladeTableBody()
    {
        $templateData = get_template('vuejs.views.blade_table_body', $this->templateType);
        $templateData = fill_template($this->commandData->dynamicVars, $templateData);

        return $templateData;
    }

    privat