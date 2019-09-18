NGTH)) {
            if (function_exists('mb_substr')) {
                $dataArray['message'] = mb_substr($dataArray['message'], 0, static::MAXIMUM_MESSAGE_LENGTH).' [truncated]';
            } else {
                $dataArray['message'] = substr($dataArray['message'], 0, static::MAXIMUM_MESSAGE_LENGTH).' [truncated]';
            }
        }

        // if we are using the legacy API then we need to send some additional information
        if ($this->version == self::API_V1) {
            $dataArray['room_id'] = $this->room;
        }

        // append the sender name if it is set
        // always append it if we use the v1 api (it is required in v1)
        if ($this->version == self::API_V1 || $this->name !== null) {
            $dataArray['from'] = (string) $this->name;
        }

        return http_build_query($dataArray);
    }

    /**
     * Builds the header of the API Call
     *
     * @param  string $content
     * @return string
     */
    private function buildHeader($content)
    {
        if ($this->version == self::API_V1) {
            $header = "POST /v1/rooms/message?format=json&auth_token={$this->token} HTTP/1.1\r\n";
        } else {
            // needed for rooms with special (spaces, etc) characters in the name
            $room = rawurlencode($this->room);
            $header = "POST /v2/room/{$room}/notification?auth_token={$this->token} HTTP/1.1\r\n";
        }

        $header .= "Host: {$this->host}\r\n";
        $header .= "Content-Type: application/x-www-form-urlencoded\r\n";
        $header .= "Content-Length: " . strlen($content) . "\r\n";
        $header .= "\r\n";

        return $header;
    }

    /**
     * Assigns a color to each level of log records.
     *
     * @param  int    $level
     * @return string
     */
    protected function getAlertColor($level)
    {
        switch (true) {
            case $level >= Logger::ERROR:
                return 'red';
            case $level >= Logger::WARNING:
                return 'yellow';
            case $level >= Logger::INFO:
                return 'green';
            case $level == Logger::DEBUG:
                return 'gray';
            default:
                return 'yellow';
        }
    }

    /**
     * {@inheritdoc}
     *
     * @param array $record
     */
    protected function write(array $record)
    {
        parent::write($record);
        $this->finalizeWrite();
    }

    /**
     * Finalizes the request by reading some bytes and then closing the socket
     *
     * If we do not read some but close the socket too early, hipchat sometimes
     * drops the request entirely.
     */
    protected function finalizeWrite()
    {
        $res = $this->getResource();
        if (is_resource($res)) {
            @fread($res, 2048);
        }
        $this->closeSocket();
    }

    /**
     * {@inheritdoc}
     */
    public function handleBatch(array $records)
    {
        if (count($records) == 0) {
            return true;
        }

        $batchRecords = $this->combineRecords($records);

        $handled = false;
        foreach ($batchRecords as $batchRecord) {
            if ($this->isHandling($batchRecord)) {
                $this->write($batchRecord);
                $handled = true;
            }
        }

        if (!$handled) {
            return false;
        }

        return false === $this->bubble;
    }

    /**
     * Combines multiple records into one. Error level of the combined record
     * will be the highest level from the given records. Datetime will be taken
     * from the first record.
     *
     * @param $records
     * @return array
     */
    private function combineRecords($records)
    {
        $batchRecord = null;
        $batchRecords = array();
        $messages = array();
        $formattedMessages = array();
        $level = 0;
        $levelName = null;
        $datetime = null;

        foreach ($records as $record) {
            $record = $this->processRecord($record);

            if ($record['level'] > $level) {
                $level = $record['level'];
                $levelName = $record['level_name'];
            }

            if (null === $datetime) {
