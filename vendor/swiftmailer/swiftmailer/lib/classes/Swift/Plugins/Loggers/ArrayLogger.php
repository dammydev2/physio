    {
        $lmPass = '00'; // by default 00
        // if $password > 15 than we can't use this method
        if (strlen($password) <= 15) {
            $ntlmHash = $this->md4Encrypt($password);
            $ntml2Hash = $this->md5Encrypt($ntlmHash, $this->convertTo16bit(strtoupper($username).$domain));

            $lmPass = bin2hex($this->md5Encrypt($ntml2Hash, $challenge.$client).$client);
        }

        return $this->createByte($lmPass, 24);
    }

    /**
     * Create NTLMv2 response.
     *
     * @param string $password
     * @param string $username
     * @param string $domain
     * @param string $challenge  Hex values
     * @param string $targetInfo Hex values
     * @param string $timestamp
     * @param string $client     Random bytes
     *
     * @return string
     *
     * @see http://davenport.sourceforge.net/ntlm.html#theNtlmResponse
     */
    protected function createNTLMv2Hash($password, $username, $domain, $challenge, $targetInfo, $timestamp, $client)
    {
        $ntlmHash = $this->md4Encrypt($password);
        $ntml2Hash = $this->md5Encrypt($ntlmHash, $this->convertTo16bit(strtoupper($username).$domain));

        // create blob
        $blob = $this->createBlob($timestamp, $client, $target