lateData = fill_template($this->commandData->dynamicVars, $templateData);

        $templateData = str_replace('$FIELDS$', implode("\n\n", $this->htmlFields), $templateData);

        FileUtil::createFile($this->path, 'fields.blade.php', $templateData);
        $this->commandData->commandInfo('fields.blade.php created');
    }

    private function generateForm()
    {
        $templateData = get_template('vuejs.views.form', $this->templateType);

        $templateData = fill_template($this->commandData->dynamicVars, $templateData);

        FileUtil::createFile($this->path, 'form.blade.php', $templateData);
        $this->commandData->commandInfo('form.blade.php created');
    }

    private function generateShow()
    {
        $templateData = get_template('vuejs.views.show', $this->templateType);

        $templateData = fill_template($this->commandData->dynamicVars, $templateData);

        FileUtil::createFile($this->path, 'show.blade.php', $templateData);
        $this->commandData->commandInfo('show.blade.php created');
    }

    private function generateDelete()
    {
        $templateData = get_template('vuejs.views.delete', $this->templateType);

        $templateData = fill_template($this->commandData->dynamicVars, $templateData);

        FileUtil::createFile($this->path, 'delete.blade.php', $templateData);
        $this->comma