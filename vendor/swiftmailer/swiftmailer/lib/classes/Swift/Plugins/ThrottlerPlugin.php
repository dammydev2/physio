<?php

/*
 * This file is part of SwiftMailer.
 * (c) 2004-2009 Chris Corbyn
 *
 * This authentication is for Exchange servers. We support version 1 & 2.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * Handles NTLM authentication.
 *
 * @author     Ward Peeters <ward@coding-tech.com>
 */
class Swift_Transport_Esmtp_Auth_NTLMAuthenticator implements Swift_Transport_Esmtp_Authenticator
{
    const NTLMSIG = "NTLMSSP\x00";
    const DESCONST = 'KGS!@#$%';

    /**
     * Get the name of the AUTH mechanism this Authenticator handles.
     *
     * @return string
     */
    public function getAuthKeyword()
    {
        return 'NTLM';
    }

    /**
     * {@inheritdoc}
     *
     * @throws \LogicException
     */
    public function authenticate(Swift_Transport_SmtpAgent $agent, $username, $password)
    {
        if (!function_exists('openssl_encrypt')) {
            throw new LogicException('The OpenSSL extension must be enabled to use the NTLM authenticator.');
        }

        if (!function_exists('bcmul')) {
            throw new LogicException('The BCMath functions must be enabled to use the NTLM authenticator.');
        }

        try {
            // execute AUTH command and filter out the code at the beginning
            // AUTH NTLM xxxx
            $response = base64_decode(substr(trim($this->sendMessage1($agent)), 4));

            // extra parameters for our unit cases
            $timestamp = func_num_args() > 3 ? func_get_arg(3) : $this->getCorrectTimestamp(bcmul(microtime(true), '1000'));
            $client = func_num_args() > 4 ? func_get_arg(4) : random_bytes(8);

            // Message 3 response
            $this->sendMessage3($response, $username, $password, $timestamp, $client, $agent);

            return true;
        } catch (Swift_TransportException $e) {
            $agent->executeCommand("RSET\r\n", [250]);

            throw $e;
        }
    }

    protected function si2bin($si, $bits = 32)
    {
        $bin = null;
        if ($si >= -pow(2, $bits - 1) && ($si <= pow(2, $bits - 1))) {
            // positive or zero
            if ($si >= 0) {
                $bin = base_convert($si, 10, 2);
                // pad to $bits bit
                $bin_length = strlen($bin);
                if ($bin_length < $bits) {
                    $bin = str_repeat('0', $bits - $bin_length).$bin;
                }
            } else {
                // negative
                $si = -$si - pow(2, $bits);
                $bin = base_convert($si, 10, 2);
                $bin_length = strlen($bin);
                if ($bin_length > $bits) {
                    $bin = str_repeat('1', $bits - $bin_length).$bin;
                }
            }
        }

        return $bin;
    }

    /**
     * Send our auth message and returns the response.
     *
     * @return string SMTP Response
     */
    protected function sendMessage1(Swift_Transport_SmtpAgent $agent)
    {
        $message = $this->createMessage1();

        return $agent->executeCommand(sprintf("AUTH %s %s\r\n", $this->getAuthKeyword(), base64_encode($message)), [334]);
    }

    /**
     * Fetch all details of our response (message 2).
     *
     * @param string $response
     *
     * @return array our response parsed
     */
    protected function parseMessage2($response)
    {
        $responseHex = bin2hex($response);
        $length = floor(hexdec(substr($responseHex, 28, 4)) / 256) * 2;
        $offset = floor(hexdec(substr($responseHex, 32, 4)) / 256) * 2;
        $challenge = hex2bin(substr($responseHex, 48, 16));
        $context = hex2bin(substr($responseHex, 64, 16));
        $targetInfoH = hex2bin(substr($responseHex, 80, 16));
        $targetName = hex2bin(substr($responseHex, $offset, $length));
        $offset = floor(hexdec(substr($responseHex, 88, 4)) / 256) * 2;
        $targetInfoBlock = substr($responseHex, $offset);
        list($domainName, $serverName, $DNSDomainName, $DNSServerName, $terminatorByte) = $this->readSubBlock($targetInfoBlock);

        return [
            $challenge,
            $context,
            $targetInfoH,
            $targetName,
            $domainName,
            $serverName,
            $DNSDomainName,
            $DNSServerName,
            hex2bin($targetInfoBlock),
            $terminatorByte,
        ];
    }

    /**
     * Read the blob information in from message2.
     *
     * @return array
     */
    protected function readSubBlock($block)
    {
        // remove terminatorByte cause it's always the same
        $block = substr($block, 0, -8);

   