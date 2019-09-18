<?php

/*
 * This file is part of SwiftMailer.
 * (c) 2004-2009 Chris Corbyn
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * Handles CRAM-MD5 authentication.
 *
 * @author Chris Corbyn
 */
class Swift_Transport_Esmtp_Auth_CramMd5Authenticator implements Swift_Transport_Esmtp_Authenticator
{
    /**
     * Get the name of the AUTH mechanism this Authenticator handles.
     *
     * @return string
     */
    public function getAuthKeyword()
    {
        return 'CRAM-MD5';
    }

    /**
     * {@inheritdoc}
     */
    public function authenticate(Swift_Transport_SmtpAgent $agent, $username, $password)
    {
        try {
            $challenge = $agent->executeCommand("AUTH CRAM-MD5\r\n", [334]);
            $challenge = base64_decode(substr($challenge, 4));
     