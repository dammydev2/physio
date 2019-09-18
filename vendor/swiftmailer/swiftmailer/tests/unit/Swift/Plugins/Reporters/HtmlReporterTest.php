<?php

class Swift_Transport_Esmtp_Auth_NTLMAuthenticatorTest extends \SwiftMailerTestCase
{
    private $message1 = '4e544c4d535350000100000007020000';
    private $message2 = '4e544c4d53535000020000000c000c003000000035828980514246973ea892c10000000000000000460046003c00000054004500530054004e00540002000c0054004500530054004e00540001000c004d0045004d0042004500520003001e006d0065006d006200650072002e0074006500730074002e0063006f006d0000000000';
    private $message3 = '4e544c4d5353500003000000180018006000000076007600780000000c000c0040000000080008004c0000000c000c0054000000000000009a0000000102000054004500530054004e00540074006500730074004d0045004d00420045005200bf2e015119f6bdb3f6fdb768aa12d478f5ce3d2401c8f6e9caa4da8f25d5e840974ed8976d3ada46010100000000000030fa7e3c677bc301f5ce3d2401c8f6e90000000002000c0054004500530054004e00540001000c004d0045004d0042004500520003001e006d0065006d006200650072002e0074006500730074002e0063006f006d000000000000000000';

    protected function setUp()
    {
        if (!function_exists('openssl_encrypt') || !function_exists('bcmul')) {
            $this->markTestSkipped('One of the required functions is not available.');
        }
    }

    public function testKeywordIsNtlm()
    {
        $login = $this->getAuthenticator();
        $this->assertEquals('NTLM', $login->getAuthKeyword());
    }

    public function testMessage1Generator()
    {
        $login = $this->getAuthenticator();
        $message1 = $this->invokePrivateMethod('createMessage1', $login);

        $this->assertEquals($this->message1, bin2hex($message1), '%s: We send the smallest ntlm message which should never fail.');
    }

    public function testLMv1Generator()
    {
        $password = 'test1234';
        $challenge = 'b019d38bad875c9d';
        $lmv1 = '1879f60127f8a877022132ec221bcbf3ca016a9f76095606';

        $login = $this->getAuthenticator();