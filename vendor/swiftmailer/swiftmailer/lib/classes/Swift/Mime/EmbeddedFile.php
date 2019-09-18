this->connection)) {
            $this->connection->disconnect();
        } else {
            $this->command("QUIT\r\n");
            if (!fclose($this->socket)) {
                throw new Swift_Plugins_Pop_Pop3Exception(
                    sprintf('POP3 host [%s] connection could not be stopped', $this->host)
                );
            }
            $this->socket = null;
        }
    }

    /**
     * Invoked just before a Transport is started.
     */
    public function beforeTransportStarted(Swift_Events_TransportChangeEvent $evt)
    {
        if (isset($this->transport)) {
            if ($this->transport !== $evt->getTransport()) {
                return;
            }
        }

        $this->connect();
        $this->disconnect();
    }

    /**
     * Not used.
     */
    public function transportStarted(Swift_Events_TransportChangeEvent $evt)
    {
    }

    /**
     * Not used.
     */
    public function beforeTransportStopped(Swift_Events_TransportChangeEvent $evt)
    {
    }

    /**
     * Not used.
     */
   