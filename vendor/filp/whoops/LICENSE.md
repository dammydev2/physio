ic' => 'color:#FFFFFF',
                'protected' => 'color:#FFFFFF',
                'private' => 'color:#FFFFFF',
                'meta' => 'color:#FFFFFF',
                'key' => 'color:#BCD42A',
                'index' => 'color:#ef7c61',
            ];
            $this->htmlDumper->setStyles($styles);
        }

        return $this->htmlDumper;
    }

    /**
     * Format the given value into a human readable string.
     *
     * @param  mixed $value
     * @return string
     */
    public function dump($value)
    {
        $dumper = $this->getDumper();

        if ($dumper) {
            // re-use the same DumpOutput instance, so it won't re-render the global styles/scripts on each dump.
            // exclude verbose information (e.g. exception stack traces)
            if (class_exists('Symfony\Component\VarDumper\Caster\Caster')) {
                $cloneVar = $this->getCloner()->cloneVar($value, Caster::EXCLUDE_VERBOSE);
                // Symfony VarDumper 2.6 Caster class dont exist.
            } else {
 