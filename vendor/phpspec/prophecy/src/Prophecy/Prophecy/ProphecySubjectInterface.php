        'methods_level'          => $methodsLevel,
                'methods_number'         => $methodsNumber,
                'classes_bar'            => $classesBar,
                'classes_tested_percent' => $data['testedClassesPercentAsString'] ?? '',
                'classes_level'          => $classesLevel,
                'classes_number'         => $classesNumber,
            ]
        );

        return $template->render();
    }

    protected function setCommonTemplateVariables(\Text_Template $template, AbstractNode $node): void
    {
        $template->setVar(
            [
                'id'               => $node->getId(),
                'full_path'        => $node->getPath(),
                'path_to_root' 