 $prevLogged = $this->loggedErrors;
        $prev = $this->loggers;
        $flush = [];

        foreach ($loggers as $type => $log) {
            if (!isset($prev[$type])) {
                throw new \InvalidArgumentException('Unknown error type: '.$type);
            }
            if (!\is_array($log)) {
                $log = [$log];
            } elseif (!\array_key_exists(0, $log)) {
                throw new \InvalidArgumentException('No logger provided');
            }
            if (null === $log[0]) {
                $this->loggedErrors &= ~$type;
            } elseif ($log[0] instanceof LoggerInterface) {
                $this->loggedErrors |= $type;
            } else {
                throw new \InvalidArgumentException('Invalid logger provided');
            }
            $this->loggers[$type] = $log + $prev[$type];

            if ($this->bootstrappingLogger && $prev[$type][0] === $this->bootstrapp