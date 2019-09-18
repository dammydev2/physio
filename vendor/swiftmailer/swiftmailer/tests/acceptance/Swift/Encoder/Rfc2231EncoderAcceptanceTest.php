<?php

class Swift_ByteStream_ArrayByteStreamTest extends \PHPUnit\Framework\TestCase
{
    public function testReadingSingleBytesFromBaseInput()
    {
        $input = ['a', 'b', 'c'];
        $bs = $this->createArrayStream($input);
        $output = [];
        while (false !== $bytes = $bs->read(1)) {
            $output[] = $bytes;
        }
        $this->assertEquals($input, $output,
            '%s: Bytes read from stream should be the same as bytes in constructor'
            );
    }

    public function testReadingMultipleBytesFromBaseInput()
    {
        $input = ['a', 'b', 'c', 'd'];
        $bs = $this->createArrayStream($input);
        $output = [];
        while (false !== $bytes = $bs->read(2)) {
            $output[] = $bytes;
        }
        $this->assertEquals(['ab', 'cd'], $output,
            '%s: Bytes read from stream should be in pairs'
            );
    }

    public function testReadingOddOffsetOnLastByte()
    {
        $input = ['a', 'b', 'c', 'd', 'e'];
        $bs = $this->createArrayStream($input);
        $output = [];
        while (false !== $bytes = $bs->read(2)) {
            $output[] = $bytes;
        }
        $this->assertEquals(['ab', 'cd', 'e'], $output,
            '%s: Bytes read from stream should be in pairs except final read'
            );
    }

    public function testSettingPointerPartway()
    {
        $input = ['a', 'b', 'c'];
        $bs = $this->createArrayStream($input);
        $bs->setReadPointer(1);
        $this->assertEquals('b', $bs->read(1),
            '%s: Byte should be second byte since pointer as at offset 1'
            );
    }

    public function testResettingPointerAfterExhaustion()
    {
        $input = ['a', 'b', 'c'];

 