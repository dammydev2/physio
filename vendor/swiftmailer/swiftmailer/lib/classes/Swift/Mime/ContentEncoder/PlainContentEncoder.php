<?php

/*
 * This file is part of SwiftMailer.
 * (c) 2004-2009 Chris Corbyn
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * MIME Message Signer used to apply S/MIME Signature/Encryption to a message.
 *
 * @author Romain-Geissler
 * @author Sebastiaan Stok <s.stok@rollerscapes.net>
 * @author Jan Flora <jf@penneo.com>
 */
class Swift_Signers_SMimeSigner implements Swift_Signers_BodySigner
{
    protected $signCertificate;
    protected $signPrivateKey;
    protected $encryptCert;
    protected $signThenEncrypt = true;
    protected $signLevel;
    protected $encryptLevel;
    protected $signOptions;
    protected $encryptOptions;
    protected $encryptCipher;
    protected $extraCerts = null;
    protected $wrapFullMessage = false;

    /**
     * @var Swift_StreamFilters_StringReplacementFilterFactory
     */
    protected $replacementFactory;

    /**
     * @var Swift_Mime_SimpleHeaderFactory
     */
    protected $headerFactory;

    /**
     * Constructor.
     *
     * @param string|null $signCertificate
     * @param string|null $signPrivateKey
     * @param string|null $encryptCertificate
     */
    public function __construct($signCertificate = null, $signPrivateKey = null, $encryptCertificate = null)
    {
        if (null !== $signPrivateKey) {
            $this->setSignCertificate($signCertificate, $signPrivateKey);
        }

        if (null !== $encryptCertificate) {
            $this->setEncryptCertificate($encryptCertificate);
        }

        $this->replacementFactory = Swift_DependencyContainer::getInstance()
            ->lookup('transport.replacementfactory');

        $this->signOptions = PKCS7_DETACHED;
        $this->encryptCipher = OPENSSL_CIPHER_AES_128_CBC;
    }

    /**
     * Set the certificate location to use for signing.
     *
     * @see https://secure.php.net/manual/en/openssl.pkcs7.flags.php
     *
     * @param string       $certificate
     * @param string|array $privateKey  If the key needs an passphrase use array('file-location', 'passphrase') instead
     * @param int          $signOptions Bitwise operator options for openssl_pkcs7_sign()
     * @param string       $extraCerts  A file containing intermediate certificates needed by the signing certificate
     *
     * @return $this
     */
    public function setSignCertificate($certificate, $privateKey = null, $signOptions = PKCS7_DETACHED, $extraCerts = null)
    {
        $this->signCertificate = 'file://'.str_replace('\\', '/', realpath($certificate));

        if (null !== $privateKey) {
            if (is_array($privateKey)) {
                $this->signPrivateKey = $privateKey;
                $this->signPrivateKey[0] = 'file://'.str_replace('\\', '/', realpath($privateKey[0]));
            } else {
                $this->signPrivateKey = 'file://'.str_replace('\\', '/', realpath($privateKey));
            }
        }

        $this->signOptions = $signOptions;
        $this->extraCerts = $extraCerts ? realpath($extraCerts) : null;

        return $this;
    }

    /**
     * Set the certificate location to use for encryption.
     *
     * @see https://secure.php.net/manual/en/openssl.pkcs7.flags.php
     * @see https://secure.php.net/manual/en/openssl.ciphers.php
     *
     * @param string|array $recipientCerts Either an single X.509 certificate, or an assoc array of X.509 certificates.
     * @param int          $cipher
     *
     * @return $this
     */
    public function setEncryptCertificate($recipientCerts, $cipher = null)
    {
        if (is_array($recipientCerts)) {
            $this->encryptCert = [];

            foreach ($recipientCerts as $cert) {
                $this->encryptCert[] = 'file://'.str_replace('\\', '/', realpath($cert));
            }
        } else {
            $this->encryptCert = 'file://'.str_replace('\\', '/', realpath($recipientCerts));
        }

        if (null !== $cipher) {
            $this->encryptCipher = $cipher;
        }

        return $this;
    }

    /**
     * @return string
     */
    public function getSignCertificate()
    {
        return $this->signCertificate;
    }

    /**
     * @return string
