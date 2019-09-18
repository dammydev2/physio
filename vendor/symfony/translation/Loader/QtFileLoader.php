his->assertCount(2, $resources);
        $this->assertContains(__DIR__.\DIRECTORY_SEPARATOR.'fixtures'.\DIRECTORY_SEPARATOR.'empty.yml', $resources);
        $this->assertContains(__DIR__.\DIRECTORY_SEPARATOR.'fixtures'.\DIRECTORY_SEPARATOR.'resources.yml', $resources);
    }

    /**
     * @dataProvider getTransTests
     */
    public function testTrans($expected, $id, $translation, $parameters, $locale, $domain)
    {
        $translator = new Translator('en');
        $translator->addLoader('array', new ArrayLoader());
        $translator->addResource('array', [(string) $id => $translation], $locale, $domain);

        $this->assertEquals($expected, $translator->trans($id, $parameters, $domain, $locale));
    }

    /**
     * @dataProvider      getInvalidLocalesTests
     * @expectedException \Symfony\Component\Translation\Exception\InvalidArgumentException
     */
    public function testTransInvalidLocale($locale)
    {
        $translator = new Translator('en');
        $translator->addLoader('array', new ArrayLoader());
        $translator->addResource('array', ['foo' => 'foofoo'], 'en');

        $translator->trans('foo', [], '', $locale);
    }

    /**
     * @dataProvider      getValidLocalesTests
     */
    public function testTransValidLocale($locale)
    {
        $translator = new Translator($locale);
        $translator->addLoader('array', new ArrayLoader());
        $translator->addResource('array', ['test' => 'OK'], $locale);

        $this->assertEquals('OK', $translator->trans('test'));
        $this->assertEquals('OK', $translator->trans('test', [], null, $locale));
    }

    /**
     * @dataProvider getFlattenedTransTests
     */
    public function testFlattenedTrans($expected, $messages, $id)
    {
        $translator = new Translator('en');
        $translator->addLoader('array', new ArrayLoader());
        $translator->addResource('array', $messages, 'fr', '');

        $this->assertEquals($expected, $translator->trans($id, [], '', 'fr'));
    }

    /**
     * @dataProvider getTransChoiceTests
     * @group legacy
     */
    public function testTransChoice($expected, $id, $translation, $number, $parameters, $locale, $domain)
    {
        $translator = new Translator('en');
        $translator->addLoader('array', new ArrayLoader());
        $translator->addResource('array', [(string) $id => $translation], $locale, $domain);

        $this->assertEquals($expected, $translator->transChoice($id, $number, $parameters, $domain, $locale));
    }

    /**
     * @dataProvider      getInvalidLocalesTests
     * @expectedException \Symfony\Component