<?php

class Swift_Mime_ContentEncoder_QpContentEncoderAcceptanceTest extends \PHPUnit\Framework\TestCase
{
    private $samplesDir;
    private $factory;

    protected function setUp()
    {
        $this->samplesDir = realpath(__DIR__.'/../../../../_samples/charsets');
        $this->factory = new Swift_CharacterReaderFactory_SimpleCharacterReaderFactory();
    }

    protected function tearDown()
    {
        Swift_Preferences::getInstance()->setQPDotEscape(false);
    }

    public function testEncodingAndDecodingSamples()
    {
        $sampleFp = opendir($this->samplesDir);
        while (false !== $encodingDir = readdir($sampleFp)) {
            if ('.' == substr($encodingDir, 0, 1)) {
                continue;
            }

            $encoding = $encodingDir;
            $charStream = new Swift_CharacterStream_NgCharacterStream(
                $this->factory, $encoding);
            $encoder = new Swift_Mime_ContentEncoder_QpContentEncoder($charStream);

            $sampleDir = $this->samplesDir.'/'.$encodingDir;

            i