    return $message;
    }

    protected function formatV1(array $record)
    {
        if (empty($record['datetime'])) {
            $record['datetime'] = gmdate('c');
        }
        $message = array(
            '@timestamp' => $record['datetime'],
            '@version' => 1,
            'host' => $this->systemName,
        );
        if (isset($record['message'])) {
            $message['message'] = $record['message'];
        }
        if (isset($record['channel'])) {
            $message['type'] = $record['channel'];
            $message['channel'] = $record['channel'];
        }
        if (isset($record['level_name'])) {
            $message['level'] = $record['level_name'];
        }
        if ($this->applicationName) {
            $message['type'] = $this->applicationName;
   