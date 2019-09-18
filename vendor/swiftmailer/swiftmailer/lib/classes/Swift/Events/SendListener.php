 * @return bool
     */
    protected function tokenNeedsEncoding($token)
    {
        return preg_match('/[()<>\[\]:;@\,."]/', $token) || parent::tokenNeedsEncoding($token);
    }

    /**
     * Return an array of strings conforming the the name-addr spec of RFC 2822.
     *
     * @param string[] $mailboxes
     *
     * @return string[]
     */
    private function createNameAddressStrings(array $mailboxes)
    {
        $strings = [];

        foreach ($mailboxes as $email => $name) {
            $mailboxStr = $this->addressEncoder->encodeString($email);
            if (null !== $name) {
                $nameStr = $this->createDisplayNameString($name, empty($strings));
                $mailboxStr = $nameStr.' <'.$mailboxStr.'>';
            }
      