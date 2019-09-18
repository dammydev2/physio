 $a[$prefix.'typeHint'] = $v->getName();
        }

        if (isset($a[$prefix.'typeHint'])) {
            $v = $a[$prefix.'typeHint'];
            $a[$prefix.'typeHint'] = new ClassStub($v, [class_exists($v, false) || interface_exists($v, false) || trait_exists($v, false) ? $v : '', '']);
        } else {
            unset($a[$prefix.'allowsNull']);
        }

        try {
            $a[$prefix.'default'] = $v = $c->getDefaultValue();
            if ($c->isDefaultValueConstant()) {
                $a[$prefix.'default'] = new ConstStub($c->getDefaultValueConstantName(), $v);
            }
            if (null === $v) {
                unset($a[$prefix.'allowsNull']);
            }
        } catch (\ReflectionException $e) {
        }

        return $a;
    }

    public static function castProperty(\ReflectionProperty $c, array $a, Stub $stub, $isNested)
    {
        $a[Caster::PREFIX_VIRTUAL.'modifiers'] = implode(' ', \Reflection::getModifierNames($c->getModifiers()));
        self::addExtra($a, $c);

        return $a;
    }

    public static function castExtension(\ReflectionExtension $c, array $a, Stub $stub, $isNested)
    {
        self::addMap($a, $c, [
            'version' => 'getVersion',
            'dependencies' => 'getDependencies',
            'iniEntries' => 'getIniEntries',
            'isPersistent' => 'isPersistent',
            'isTemporary' => 'isTemporary',
            'constants' => 'getConstants',
            'functions' => 'getFunctions',
            'classes' => 'getClasses',
        ]);

        return $a;
    }

    public static function castZendExtension(\ReflectionZendExtension $c, array $a, Stub $stub, $isNested)
    {
        self::addMap($a, $c, [
            'version' => 'getVersion',
            'author' => 'getAuthor',
            'copyright' => 'getCopyright',
            'url' => 'getURL',
        ]);

        return $a;
    }

    public static function getSignature(array $a)
    {
        $prefix = Caster::PREFIX_VIRTUAL;
        $signature = '';

        if (isset($a[$prefix.'parameters'])) {
            foreach ($a[$prefix.'parameters']->value as $k => $param) {
                $signature .= ', ';
                if ($type = $param->getType()) {
                    if (!$param->isOptional() && $param->allowsNull()) {
                