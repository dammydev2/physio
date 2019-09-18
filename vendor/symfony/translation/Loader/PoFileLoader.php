foo (en)'], 'en');
        $translator->addResource('array', ['bar' => 'bar (fr)'], 'fr');

        // force catalogue loading
        $translator->trans('bar');

        $translator->setFallbackLocales(['fr_FR', 'fr']);
        $this->assertEquals('bar (fr)', $translator->trans('bar'));
    }

    /**
     * @dataProvider      getInvalidLocalesTests
     * @expectedException \Symfony\Component\Translation\Exception\InvalidArgumentException
     */
    public function testSetFallbackInvalidLocales($locale)
    {
        $translator = new Translator('fr');
        $translator->setFallbackLocales(['fr', $locale]);
    }

    /**
     * @dataProvider getValidLocalesTests
     */
    public function testSetFallbackValidLocales($locale)
    {
        $translator = new Translator($locale);
        $translator->setFallbackLocales(['fr', $locale]);
        // no assertion. this method just asserts that no exception is thrown
        $this->addToAssertionCount(1);
    }

    public function testTransWithFallbackLocale()
    {
        $translator = new Translator('fr_FR');
        $translator->setFallbackLocales(['en']);

        $translator->addLoader('array', new ArrayLoader());
        $translator->addResource('array', ['bar' => 'foobar'], 'en');

        $this->assertEquals('foobar', $translator->trans('bar'));
    }

    /**
     * @dataProvider      getInvalidLocalesTests
     * @expectedException \Symfony\Component\Translation\Exception\InvalidArgumentException
     */
    public function testAddResourceInvalidLocales($locale)
    {
        $translator = new Translator('fr');
        $translator->addResource('array', ['foo' => 'foofoo'], $locale);
    }

    /**
     * @dataProvider getValidLocalesTests
     */
    public function testAddResourceValidLocales($locale)
    {
        $translator = new Translator('fr');
        $translator->addResource('array', ['foo' => 'foofoo'], $locale);
        // no assertion. this method just asserts that no exception is thrown
        $this->addToAssertionCount(1);
    }

    public function testAddResourceAfterTrans()
    {
        $translator = new Translator('fr');
        $translator->addLoader('array', new ArrayLoader());

        $translator->setFallbackLocales(['en']);

        $translator->addResource('array', ['foo' => 'foofoo'], 'en');
        $this->assertEquals('foofoo', $translator->trans('foo'));

        $translator->addResource('array', ['bar' => 'foobar'], 'en');
        $this->assertEquals('foobar', $translator->trans('bar'));
    }

    /**
     * @dataProvider      getTransFileTests
     * @expectedException \Symfony\Component\Translation\Exception\NotFoundResourceException
     */
    public function testTransWithoutFallbackLocaleFile($format, $loader)
    {
        $loaderClass = 'Symfony\\Component\\Translation\\Loader\\'.$loader;
        $translator = new Translator('en');
        $translator->addLoader($format, new $loaderClass());
        $translator->addResource($format, __DIR__.'/fixtures/non-existing', 'en');
        $translator->addResource($format, __DIR__.'/fixtures/resources.'.$format, 'en');

        // force catalogue loading
        $translator->trans('foo');
    }

    /**
     * @dataProvider getTransFileTests
     */
    public function testTransWithFallbackLocaleFile($format, $loader)
    {
        $loaderClass = 'Symfony\\Component\\Translation\\Loader\\'.$loader;
        $translator = new Translator('en_GB');
        $translator->addLoader($format, new $loaderClass());
        $translator->addResource($format, __DIR__.'/fixtures/non-existing', 'en_GB');
        $translator->addResource($format, __DIR__.'/fixtures/resources.'.$format, 'en', 'resources');

        $this->assertEquals('bar', $translator->trans('foo', [], 'resources'));
    }

    public function testTransWithIcuFallbackLocale()
    {
        $translator = new Translator('en_GB');
        $translator->addLoader('array', new ArrayLoader());
        $translator->addResource('array', ['foo' => 'foofoo'], 'en_GB');
        $translator->addResource('array', ['bar' => 'foobar'], 'en_001');
        $translator->addResource('array', ['baz' => 'foobaz'], 'en');
        $this->assertSame('foofoo', $translator->trans('foo'));
        $this->assertSame('foobar', $translator->trans('bar'));
        $this->assertSame('foobaz', $translator->trans('baz'));
    }

    public function testTransWithIcuVariantFallbackLocale()
    {
        $translator = new Translator('en_GB_scouse');
        $translator->addLoader('array', new ArrayLoader());
        $translator->addResource('array', ['foo' => 'foofoo'], 'en_GB_scouse');
        $translator->addResource('array', ['bar' => 'foobar'], 'en_GB');
        $translator->addResource('array', ['baz' => 'foobaz'], 'en_001');
        $translator->addResource('array', ['qux' => 'fooqux'], 'en');
        $this->assertSame('foofoo', $translator->trans('foo'));
        $this->assertSame('foobar', $translator->trans('bar'));
        $this->assertSame('foobaz', $translator->trans('baz'));
        $this->assertSame('fooqux', $translator->trans('qux'));
    }

    public function testTransWithIcuRootFallbackLocale()
    {
        $translator = new Translator('az_Cyrl');
        $translator->addLoader('array', new ArrayLoader());
        $translat