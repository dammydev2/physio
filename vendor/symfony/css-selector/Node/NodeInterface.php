c function testHtmlIds($css, array $elementsId)
    {
        $translator = new Translator();
        $translator->registerExtension(new HtmlExtension($translator));
        $document = new \DOMDocument();
        $document->strictErrorChecking = false;
        $internalErrors = libxml_use_internal_errors(true);
        $document->loadHTMLFile(__DIR__.'/Fixtures/ids.html');
        $document = simplexml_import_dom($document);
        $elements = $document->xpath($translator->cssToXPath($css));
        $this->assertCount(\count($elementsId), $elementsId);
        foreach ($elements as $element) {
            if (null !== $element->attributes()->id) {
                $this->assertTrue(\in_array($element->att