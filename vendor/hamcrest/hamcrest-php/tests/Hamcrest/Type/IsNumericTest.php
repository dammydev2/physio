) {
            $routeGenerator = new RoutesGenerator($this->commandData);
            $routeGenerator->generate();
        }

        if (!$this->isSkip('menu') and $this->commandData->config->getAddOn('menu.enabled')) {
            $menuGenerator = new MenuGenerator($this->commandData);
            $menuGenerator->generate();
        }
    }

    public function performPostActions($runMigration = false)
    {
        if ($this->commandData->getOption('save')) {
            $this->saveSchemaFile();
        }

        if ($runMigration) {
            if ($this->commandData->getOption('forceMigrate')) {
                $this->call('migrate');
            } elseif (!$this->commandData->getOption('fromTable') and !$this->isSkip('migration')) {
                if ($this->commandData->getOption('jsonFromGUI')) {
                    $this->call('migrate');
                } elseif ($this->confirm("\nDo you want to migrate database? [y|N]", false)) {
                    $this->call('migrate');
                }
            }
        }
        if (!$this->isSkip('dump-autoload')) {
            $this->info('Generating autoload files');
            $this->composer->dumpOptimized();
        }
    }

    public function isSkip($skip)
    {
        if ($this->commandData->getOption('skip')) {
            return in_array($skip, (array) $this->commandData->getOption('skip'));
        }

        return false;
    }

    public function performPostActionsWithMigration()
    {
        $this->performPostActions(true);
    }

    private function saveSchemaFile()
    {
        $fileFields = [];

        foreach ($this->commandData->fields as $field) {
            $fileFields[] = [
                'name'        => $field->name,
                'dbType'      => $field->dbInput,
                'htmlType'    => $field->htmlInput,
         