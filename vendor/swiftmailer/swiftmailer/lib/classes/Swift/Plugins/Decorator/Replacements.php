 Domain header
.$userSec // User header
.$workSec // Workstation header
.$this->createByte('000000009a', 8) // session key header (empty)
.$this->createByte('01020000') // FLAGS
.$this->convertTo16bit($domain) // domain name
.$this->convertTo16bit($username) // username
.$this->convertTo16bit($workstation) // workstation
.$lmResponse
        .$ntlmResponse;
    }

    /**
     * @param string $timestamp  Epoch timestamp in microseconds
     * @param string $client     Random bytes
     * @param string $targetInfo
     *
     * @return string
     */
    protected function createBlob($timestamp, $client, $targetInfo)
    {
        return $this->createByte('0101')
        .$this->createByte('00')
        .$timestamp
        .$client
        .$this->create