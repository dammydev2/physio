#10;`%s`"', esc($this->utf8Encode($attr['class'])));
        }
        $map = static::$controlCharsMap;

        if (isset($attr['ellipsis'])) {
            $class = 'sf-dump-ellipsis';
            if (isset($attr['ellipsis-type'])) {
                $class = sprintf('"%s sf-dump-ellipsis-%s"', $class, $attr['ellipsis-type']);
            }
            $label = esc(substr($value, -$attr['ellipsis']));
            $style = str_replace(' title="', " title=\"$v\n", $style);
            $v = sprintf('<span class=%s>%s</span>', $class, substr($v, 0, -\strlen($label)));

            if (!empty($attr['ellipsis-tail'])) {
                $tail = \strlen(esc(substr($value, -$attr['ellipsis'], $attr['ellipsis-tail'])));
                $v .