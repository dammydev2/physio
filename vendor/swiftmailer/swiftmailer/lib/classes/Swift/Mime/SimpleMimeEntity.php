en;
        }

        return $this;
    }

    /**
     * Set the signature timestamp.
     *
     * @param int $time A timestamp
     *
     * @return $this
     */
    public function setSignatureTimestamp($time)
    {
        $this->signatureTimestamp = $time;

        return $this;
    }

    /**
     * Set the signature expiration timestamp.
     *
     * @param int $time A timestamp
     *
     * @return $this
     */
    public function setSignatureExpiration($time)
    {
        $this->signatureExpiration = $time;

        return $this;
    }

    /**
     * Enable / disable the DebugHeaders.
     *
     * @param bool $debug
     *
     * @return Swift_Signers_DKIMSigner
     */
    public function setDebugHeaders($debug)
    {
        $this->debugHeaders = (bool) $debug;

        return $this;
    }

    /**
     * Start Body.
     */
    public function startBody()
    {
        // Init
        switch ($this->hashAlgorithm) {
            case 'rsa-sha256':
                $this->bodyHashHandler = hash_init('sha256');
                break;
            case 'rsa-sha1':
                $this->bodyHashHandler = hash_init('sha1');
                break;
        }
        $this->bodyCanonLine = '';
    }

    /**
     * End Body.
     */
    public function endBody()
    {
        $this->endOfBody();
    }

    /**
     * Returns the list of Headers Tampered by this plugin.
     *
     * @return array
     */
    public function getAlteredHeaders()
    {
        if ($this->debugHeaders) {
            return ['DKIM-Signature', 'X-DebugHash'];
        } else {
            return ['DKIM-Signature'];
        }
    }

    /**
     * Adds an ignored Header.
     *
     * @param string $header_name
     *
     * @return Swift_Signers_DKIMSigner
     */
    public function ignoreHeader($header_name)
    {
        $this->ignoredHeaders[strtolower($header_name)] = true;

        return $this;
    }

    /**
     * Set the headers to sign.
     *
     * @return Swift_Signers_DKIMSigner
     */
    public function setHeaders(Swift_Mime_SimpleHeaderSet $headers)
    {
        $this->headerCanonData = '';
        // Loop through Headers
        $listHeaders = $headers->listAll();
        foreach ($listHeaders as $hName) {
            // Check if we need to ignore Header
            if (!isset($this->ignoredHeaders[strtolower($hName)])) {
                if ($headers->has($hName)) {
                    $tmp = $headers->getAll($hName);
                    foreach ($tmp as $header) {
                        if ('' != $header->getFieldBody()) {
                            $this->addHeader($header->toString());
                            $this->signedHeaders[] = $header->getFieldName();
                        }
                    }
                }
            }
        }

        return $this;
    }

    /**
     * Add the signature to the given Headers.
     *
     * @return Swift_Signers_DKIMSigner
     */
    public function addSignature(Swift_Mime_SimpleHeaderSet $headers)
    {
        // Prepare the DKIM-Signature
        $params = ['v' => '1', 'a' => $this->hashAlgorithm, 'bh' => base64_encode($this->bodyHash), 'd' => $this->domainName, 'h' => implode(': ', $this->signedHeaders), 'i' => $this->signerIdentity, 's' => $this->selector];
        if ('simple' != $this->bodyCanon) {
            $params['c'] = $this->headerCanon.'/'.$this->bodyCanon;
        } elseif ('simple' != $this->headerCanon) {
            $params['c'] = $this->headerCanon;
        }
        if ($this->showLen) {
            $params['l'] = $this->bodyLen;
        }
        if (true === $this->signatureTimestamp) {
            $params['t'] = time();
            if (false !== $this->signatureExpiration) {
                $params['x'] = $params['t'] + $this->signatureExpiration;
            }
        } else {
            if (false !== $this->signatureTimestamp) {
                $params['t'] = $this->signatureTimestamp;
            }
            if (false !== $this->signatureExpiration) {
                $params['x'] = $this->signatureExpiration;
            }
        }
        if ($this->debugHeaders) {
            $params['z'] = implode('|', $this->debugHeadersData);
        }
        $string = '';
        foreach ($params as $k => $v) {
            $string .= $k.'='.$v.'; ';
        }
        $string = trim($string);
        $headers->addTextHeader('DKIM-Signature', $string);
        // Add the last DKIM-Signature
        $tmp = $headers->getAll('DKIM-Signature');
        $this->dkimHeader = end($tmp);
        $this->addHeader(trim($this->dkimHeader->toString())."\r\n b=", true);
        if ($this->debugHeaders) {
            $headers->addTextHeader('X-DebugHash', base64_encode($this->headerHash));
        }
        $this->dkimHeader->setValue($string.' b='.trim(chunk_split(base64_encode($this->getEncryptedHash()), 73, ' ')));

        return $this;
    }

    /* Private helpers */

    protected function addHeader($header, $is_sig = false)
    {
        switch ($this->headerCanon) {
            case 'relaxed':
                // Prepare Header and cascade
                $exploded = explode(':', $header, 2);
                $name = strtolower(trim($exploded[0]));
                $value = str_replace("\r\n", '', $exploded[1]);
                $value = preg_replace("/[ \t][ \t]+/", ' ', $value);
                $header = $name.':'.trim($value).($is_sig ? '' : "\r\n");
                // no break
            case 'simple':
                // Nothing to do
        }
        $this->addToHeaderHash($header);
    }

    protected function canonicalizeBody($string)
    {
        $len = strlen($string);
        $canon = '';
        $method = ('relaxed' == $this->bodyCanon);
        for ($i = 0; $i < $len; ++$i) {
            if ($this->bodyCanonIgnoreStart > 0) {
                --$this->bodyCanonIgnoreStart;
                continue;
            }
            switch ($string[$i]) {
                case "\r":
                    $this->bodyCanonLastChar = "\r";
                    break;
                case "\n":
                    if ("\r" == $this->bodyCanonLastChar) {
                        if ($method) {
                            $this->bodyCanonSpace = false;
                        }
                        if ('' == $this->bodyCanonLine) {
                            ++$this->bodyCanonEmptyCounter;
                        } else {
                            $this->bodyCanonLine = '';
                            $canon .= "\r\n";
                        }
                    } else {
                        // Wooops Error
                        // todo handle it but should never happen
                    }
                    break;
                case ' ':
                case "\t":
                    if ($method) {
                        $this->bodyCanonSpace = true;
                        break;
                    }
                    // no break
                default:
                    if ($this->bodyCanonEmptyCounter > 0) {
                        $canon .= str_repeat("\r\n", $this->bodyCanonEmptyCounter);
                        $this->bodyCanonEmptyCounter = 0;
                    }
                    if ($this->bodyCanonSpace) {
                        $this->bodyCanonLine .= ' ';
                        $canon .= ' ';
                        $this->bodyCanonSpace = false;
                    }
                    $this->bodyCanonLine .= $string[$i];
                    $canon .= $string[$i];
            }
        }
        $this->addToBodyHash($canon);
    }

    protected function endOfBody()
    {
        // Add trailing Line return if last line is non empty
        if (strlen($this->bodyCanonLine) > 0) {
            $this->addToBodyHash("\r\n");
        }
        $this->bodyHash = hash_final($this->bodyHashHandler, true);
    }

    private function addToBodyHash($string)
    {
        $len = strlen($string);
        if ($len > ($new_len = ($this->maxLen - $this->bodyLen))) {
            $string = substr($string, 0, $new_len);
            $len = $new_len;
        }
        hash_update($this->bodyHashHandler, $string);
        $this->bodyLen += $len;
    }

    private function addToHeaderHash($header)
    {
        if ($this->debugHeaders) {
            $this->debugHeadersData[] = trim($header);
        }
        $this->headerCanonData .= $header;
    }

    /**
     * @throws Swift_SwiftException
     *
     * @return string
     */
    private function getEncryptedHash()
    {
        $signature = '';
        switch ($this->hashAlgorithm) {
            case 'rsa-sha1':
                $algorithm = OPENSSL_ALGO_SHA1;
                break;
            case 'rsa-sha256':
                $algorithm = OPENSSL_ALGO_SHA256;
                break;
        }
        $pkeyId = openssl_get_privatekey($this->privateKey, $this->passphrase);
        if (!$pkeyId) {
            throw new Swift_SwiftException('Unable to load DKIM Private Key ['.openssl_error_string().']');
        }
        if (openssl_sign($this->headerCanonData, $signature, $pkeyId, $algorithm)) {
            return $signature;
        }
        throw new Swift_SwiftException('Unable to sign DKIM Hash ['.openssl_error_string().']');
    }
}
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                           <?php

/*
 * This file is part of SwiftMailer.
 * (c) 2004-2009 Chris Corbyn
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * DomainKey Signer used to apply DomainKeys Signature to a message.
 *
 * @author     Xavier De Cock <xdecock@gmail.com>
 */
class Swift_Signers_DomainKeySigner implements Swift_Signers_HeaderSigner
{
    /**
     * PrivateKey.
     *
     * @var string
     */
    protected $privateKey;

    /**
     * DomainName.
     *
     * @var string
     */
    protected $domainName;

    /**
     * Selector.
     *
     * @var string
     */
    protected $selector;

    /**
     * Hash algorithm used.
     *
     * @var string
     */
    protected $hashAlgorithm = 'rsa-sha1';

    /**
     * Canonisation method.
     *
     * @var string
     */
    protected $canon = 'simple';

    /**
     * Headers not being signed.
     *
     * @var array
     */
    protected $ignoredHeaders = [];

    /**
     * Signer identity.
     *
     * @var string
     */
    protected $signerIdentity;

    /**
     * Must we embed signed headers?
     *
     * @var bool
     */
    protected $debugHeaders = false;

    // work variables
    /**
     * Headers used to generate hash.
     *
     * @var array
     */
    private $signedHeaders = [];

    /**
     * Stores the signature header.
     *
     * @var Swift_Mime_Headers_ParameterizedHeader
     */
    protected $domainKeyHeader;

    /**
     * Hash Handler.
     *
     * @var resource|null
     */
    private $hashHandler;

    private $canonData = '';

    private $bodyCanonEmptyCounter = 0;

    private $bodyCanonIgnoreStart = 2;

    private $bodyCanonSpace = false;

    private $bodyCanonLastChar = null;

    private $bodyCanonLine = '';

    private $bound = [];

    /**
     * Constructor.
     *
     * @param string $privateKey
     * @param string $domainName
     * @param string $selector
     */
    public function __construct($privateKey, $domainName, $selector)
    {
        $this->privateKey = $privateKey;
        $this->domainName = $domainName;
        $this->signerIdentity = '@'.$domainName;
        $this->selector = $selector;
    }

    /**
     * Resets internal states.
     *
     * @return $this
     */
    public function reset()
    {
        $this->hashHandler = null;
        $this->bodyCanonIgnoreStart = 2;
        $this->bodyCanonEmptyCounter = 0;
        $this->bodyCanonLastChar = null;
        $this->bodyCanonSpace = false;

        return $this;
    }

    /**
     * Writes $bytes to the end of the stream.
     *
     * Writing may not happen immediately if the stream chooses to buffer.  If
     * you want to write these bytes with immediate effect, call {@link commit()}
     * after calling write().
     *
     * This method returns the sequence ID of the write (i.e. 1 for first, 2 for
     * second, etc etc).
     *
     * @param string $bytes
     *
     * @return int
     *
     * @throws Swift_IoException
     *
     * @return $this
     */
    public function write($bytes)
    {
        $this->canonicalizeBody($bytes);
        foreach ($this->bound as $is) {
            $is->write($bytes);
        }

        return $this;
    }

    /**
     * For any bytes that are currently buffered inside the stream, force them
     * off the buffer.
     *
     * @throws Swift_IoException
     *
     * @return $this
     */
    public function commit()
    {
        // Nothing to do
        return $this;
    }

    /**
     * Attach $is to this stream.
     *
     * The stream acts as an observer, receiving all data that is written.
     * All {@link write()} and {@link flushBuffers()} operations will be mirrored.
     *
     * @return $this
     */
    public function bind(Swift_InputByteStream $is)
    {
        // Don't have to mirror anything
        $this->bound[] = $is;

        return $this;
    }

    /**
     * Remove an already bound stream.
     *
     * If $is is not bound, no errors will be raised.
     * If the stream currently has any buffered data it will be written to $is
     * before unbinding occurs.
     *
     * @return $this
     */
    public function unbind(Swift_InputByteStream $is)
    {
        // Don't have to mirror anything
        foreach ($this->bound as $k => $stream) {
            if ($stream === $is) {
                unset($this->bound[$k]);

                break;
            }
        }

        return $this;
    }

    /**
     * Flush the contents of the stream (empty it) and set the internal pointer
     * to the beginning.
     *
     * @throws Swift_IoException
     *
     * @return $this
     */
    public function flushBuffers()
    {
        $this->reset();

        return $this;
    }

    /**
     * Set hash_algorithm, must be one of rsa-sha256 | rsa-sha1 defaults to rsa-sha256.
     *
     * @param string $hash
     *
     * @return $this
     */
    public function setHashAlgorithm($hash)
    {
        $this->hashAlgorithm = 'rsa-sha1';

        return $this;
    }

    /**
     * Set the canonicalization algorithm.
     *
     * @param string $canon simple | nofws defaults to simple
     *
     * @return $this
     */
    public function setCanon($canon)
    {
        if ('nofws' == $canon) {
            $this->canon = 'nofws';
        } else {
            $this->canon = 'simple';
        }

        return $this;
    }

    /**
     * Set the signer identity.
     *
     * @param string $identity
     *
     * @return $this
     */
    public function setSignerIdentity($identity)
    {
        $this->signerIdentity = $identity;

        return $this;
    }

    /**
     * Enable / disable the DebugHeaders.
     *
     * @param bool $debug
     *
     * @return $this
     */
    public function setDebugHeaders($debug)
    {
        $this->debugHeaders = (bool) $debug;

        return $this;
    }

    /**
     * Start Body.
     */
    public function startBody()
    {
    }

    /**
     * End Body.
     */
    public function endBody()
    {
        $this->endOfBody();
    }

    /**
     * Returns the list of Headers Tampered by this plugin.
     *
     * @return array
     */
    public function getAlteredHeaders()
    {
        if ($this->debugHeaders) {
            return ['DomainKey-Signature', 'X-DebugHash'];
        }

        return ['DomainKey-Signature'];
    }

    /**
     * Adds an ignored Header.
     *
     * @param string $header_name
     *
     * @return $this
     */
    public function ignoreHeader($header_name)
    {
        $this->ignoredHeaders[strtolower($header_name)] = true;

        return $this;
    }

    /**
     * Set the headers to sign.
     *
     * @return $this
     */
    public function setHeaders(Swift_Mime_SimpleHeaderSet $headers)
    {
        $this->startHash();
        $this->canonData = '';
        // Loop through Headers
        $listHeaders = $headers->listAll();
        foreach ($listHeaders as $hName) {
            // Check if we need to ignore Header
            if (!isset($this->ignoredHeaders[strtolower($hName)])) {
                if ($headers->has($hName)) {
                    $tmp = $headers->getAll($hName);
                    foreach ($tmp as $header) {
                        if ('' != $header->getFieldBody()) {
                            $this->addHeader($header->toString());
                            $this->signedHeaders[] = $header->getFieldName();
                        }
                    }
                }
            }
        }
        $this->endOfHeaders();

        return $this;
    }

    /**
     * Add the signature to the given Headers.
     *
     * @return $this
     */
    public function addSignature(Swift_Mime_SimpleHeaderSet $headers)
    {
        // Prepare the DomainKey-Signature Header
        $params = ['a' => $this->hashAlgorithm, 'b' => chunk_split(base64_encode($this->getEncryptedHash()), 73, ' '), 'c' => $this->canon, 'd' => $this->domainName, 'h' => implode(': ', $this->signedHeaders), 'q' => 'dns', 's' => $this->selector];
        $string = '';
        foreach ($params as $k => $v) {
            $string .= $k.'='.$v.'; ';
        }
        $string = trim($string);
        $headers->addTextHeader('DomainKey-Signature', $string);

        return $this;
    }

    /* Private helpers */

    protected function addHeader($header)
    {
        switch ($this->canon) {
            case 'nofws':
                // Prepare Header and cascade
                $exploded = explode(':', $header, 2);
                $name = strtolower(trim($exploded[0]));
                $value = str_replace("\r\n", '', $exploded[1]);
                $value = preg_replace("/[ \t][ \t]+/", ' ', $value);
                $header = $name.':'.trim($value)."\r\n";
                // no break
            case 'simple':
                // Nothing to do
        }
        $this->addToHash($header);
    }

    protected function endOfHeaders()
    {
        $this->bodyCanonEmptyCounter = 1;
    }

    protected function canonicalizeBody($string)
    {
        $len = strlen($string);
        $canon = '';
        $nofws = ('nofws' == $this->canon);
        for ($i = 0; $i < $len; ++$i) {
            if ($this->bodyCanonIgnoreStart > 0) {
                --$this->bodyCanonIgnoreStart;
                continue;
            }
            switch ($string[$i]) {
                case "\r":
                    $this->bodyCanonLastChar = "\r";
                    break;
                case "\n":
                    if ("\r" == $this->bodyCanonLastChar) {
                        if ($nofws) {
                            $this->bodyCanonSpace = false;
                        }
                        if ('' == $this->bodyCanonLine) {
                            ++$this->bodyCanonEmptyCounter;
                        } else {
                            $this->bodyCanonLine = '';
                            $canon .= "\r\n";
                        }
                    } else {
                        // Wooops Error
                        throw new Swift_SwiftException('Invalid new line sequence in mail found \n without preceding \r');
                    }
                    break;
                case ' ':
                case "\t":
           