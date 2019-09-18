s->getAllMessages($this->catalogues[$fallback]));
            foreach ($this->catalogues[$fallback]->getResources() as $resource) {
                $fallbackCatalogue->addResource($resource);
            }
            $current->addFallbackCatalogue($fallbackCatalogue);
            $current = $fallbackCatalogue;
        }
    }

    protected function computeFallbackLocales($locale)
    {
        if (null === $this->parentLocales) {
            $parentLocales = \json_decode(\file_get_contents(__DIR__.'/Resources/data/parents.json'), true);
        }

        $locales = [];
        foreach ($this->fallbackLocales as $fallback) {
            if ($fallback === $locale) {
                continue;
            }

            $locales[] = $fallback;
        }

        while ($locale) {
            $parent = $parentLocales[$locale] ?? 