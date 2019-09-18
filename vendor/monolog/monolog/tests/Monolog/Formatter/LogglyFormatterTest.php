  array('enableSslOnlyMode', 'enableSslOnlyMode', true, false),
            array('ipMasks', 'setAllowedIpMasks', array('127.0.0.*')),
            array('headersLimit', 'setHeadersLimit', 2500),
            array('enableEvalListener', 'startEvalRequestsListener', true, false),
        );
    }

    /**
     * @dataProvider provideConnectorMethodsOptionsSets
     */
    public function testOptionCallsConnectorMethod($option, $method, $value, $isArgument = true)
    {
        $expectCall = $this->connector->expects($this->once())->method($method);
        if ($isArgument) {
            $expectCall->with($value);
        }
        new PHPConsoleHandler(array($option => $value), $this->connector);
    }

    public function testOptionDetectDumpTraceAndSource()
    {
        new PHPConsoleHandler(array('detectDumpTraceAndSource' => true), $this->connector);
        $this->assertTrue($this->connector->getDebugDispatcher()->detectTraceAndSource);
    }

    public static function provideDumperOptionsValues()
    {
        return array(
            array('dumperLevelLimit', 'levelLimit', 1001),
            array('dumperItemsCountLimit', 'itemsCountLimit', 1002),
            array('dumperItemSizeLimit