aster::PREFIX_PROTECTED.'message'], "class@anonymous\0")) {
            $a[Caster::PREFIX_PROTECTED.'message'] = preg_replace_callback('/class@anonymous\x00.*?\.php0x?[0-9a-fA-F]++/', function ($m) {
                return \class_exists($m[0], false) ? get_parent_class($m[0]).'@anonymous' : $m[0];
            }, $a[Caster::PREFIX_PROTECTED.'message']);
        }

        if (isset($a[Caster::PREFIX_PROTECTED.'file'], $a[Caster::PREFIX_PROTECTED.'line'])) {
            $a[Caster::PREFIX_PROTECTED.'file'] = new LinkStub($a[Caster::PREFIX_PROTECTED.'file'], $a[Caster::PREFIX_PROTECTED.'line']);
        }

        return $a;
    }

    private static function traceUnshift(&$trace, $class, $file, $line)
    {
        if (isset($trace[0]['file'], $trace[0]['line']) && $trace[0]['file'] === $file && $trace[0]['line'] === $line) {
            return;
        }
        array_unshift($trace, [
            'function' => $class ? 'new '.$class : null,
            'file' => $file,
            'line' => $line,
        ]);
    }

    private static function extractSource($srcLines, $line, $srcContext, $title, $lang, $file = null)
    {
        $srcLines = explode("\n", $srcLines);
        $src = [];

        for ($i = $line - 1 - $srcContext; $i <= $line - 1 + $srcContext; ++$i) {
            $src[] = (isset($srcLines[$i]) ? $srcLines[$i] : '')."\n";
        }

        $srcLines = [];
        $ltrim = 0;
        do {
            $pad = null;
            for ($i = $srcContext << 1; $i >= 0; --$i) {
                if (isset($src[$i][$ltrim]) && "\r" !== ($c = $src[$i][$ltrim]) && "\n" !== $c) {
                    if (null === $pad) {
                        $pad = $c;
                    }
                    if ((' ' !== $c && "\t" !== $c) || $pad !== $c) {
                        break;
                    }
                }
            }
            ++$ltrim;
        } while (0 > $i && null !== $pad);

        --$ltrim;

        foreach ($src as $i => $c) {
            if ($ltrim) {
                $c = isset($c[$ltrim]) && "\r" !== $c[$ltrim] ? substr($c, $ltrim) : ltrim($c, " \t");
            }
            $c = substr($c, 0, -1);
            if ($i !== $srcContext) {
                $c = new C