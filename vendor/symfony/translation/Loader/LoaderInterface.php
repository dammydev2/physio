e($format, null, $locale);
        $translator->trans($msgid);

        // 2nd pass
        $translator = new Translator($locale, null, $this->tmpDir, true);
        $translator->addLoader($format, $loader);
        $translator->addResource($format, null, $locale);
        $translator->trans($msgid);
    }

    /**
     * @dataProvider runForDebugAndProduction
     */
    public function testDifferentTranslatorsForSameLocaleDoNotOverwriteEachOthersCache($debug)
    {
        /*
         * Similar to the previous test. After we used the second translator, make
         * sure there's still a usable cache for the first one.
         */

        $locale = 'any_locale';
        $format = 'some_format';
        $msgid = 'test';

        // Create a Translator and prime its cache
        $translator = new Translator($locale, null, $this->tmpDir, $debug);
        $translator->addLoader($format, new ArrayLoader());
        $translator->addResource($format, [$msgid => 'OK'], $locale);
        $translator->trans($msgid);

        // Create another Translator with a different catalogue for the same local