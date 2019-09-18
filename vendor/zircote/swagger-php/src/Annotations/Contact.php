 $analyser = new Analyser();
        $context = Context::detect(1);
        return $analyser->fromComment("<?php\n/**\n * " . implode("\n * ", explode("\n", $comment)) . "\n*/", $context);
    }

    /**
     * Create a Swagger object with Info.
     * (So it will pass validation.)
     */
    protected function createSwaggerWithInfo()
    {
        $swagger = new Swagger([
            'info' => new \Swagger\Annotations\Info([
                'title' => 'Swagger-PHP Test-API',
                'version' => 'test',
                '_context' => new Context(['unittest' => true])
            ]),
            '_context' => new Context(['unittest' => true])
        ]);
        return $swagger;
    }

    /**
     * Sorts the object to improve matching and debugging the differences.
     * Used by assertSwaggerEqualsFile
     * @param stdClass $object
     * @param string   $ori