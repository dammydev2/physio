}
        $renderedAttributes = '';
        if (\count($attributes) > 0) {
            $flags = ENT_QUOTES | ENT_SUBSTITUTE;
            foreach ($attributes as $attribute => $value) {
                $renderedAttributes .= sprintf(
                    ' %s="%s"',
                    htmlspecialchars($attribute, $flags, $this->charset, false),
                    htmlspecialchars($value, $flags, $this->charset, false)
                );
            }
        }

        return new Response(sprintf('<hx:include src="%s"%s>%s</hx:include>', $uri, $renderedAttributes, $content));
    }

    private function templateExists(string $template): bool
    {
        if ($this->templating instanceof EngineInterface) {
            try {
                return $this->templating->exists($template);
 