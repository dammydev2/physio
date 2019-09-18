tsbW5vcHFyc3R1dnd4eXpBQk'.//38
        'NERUZHSElKS0xNTk9QUVJTVFVWV1hZWjEyMzQ1'."\r\n".//76 *
        'Njc4OTBhYmNkZWZnaGlqa2xtbm9wcXJzdHV2d3'.//38
        'h5ekFCQ0RFRkdISUpLTE1OT1BRUlNUVVZXWFla'."\r\n".//76 *
        'MTIzNDU2Nzg5MEFCQ0RFRkdISUpLTE1OT1BRUl'.//38
        'NUVVZXWFla';                                       //48

        $encoder = new Base64Encoder();
        $this->assertEquals($output, $encoder->encodeString($input), 'Lines should be no more than 76 characters');
    }

    public function testMaximumLineLengthCanBeSpecified()
    {
        $input =
        'abcdefghijklmnopqrstuvwxyz'.
        'ABCDEFGHIJKLMNOPQRSTUVWXYZ'.
        '1234567890'.
        'abcdefghijklmnopqrstuvwxyz'.
     