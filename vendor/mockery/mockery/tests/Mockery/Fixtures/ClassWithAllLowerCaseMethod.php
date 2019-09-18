RNING,
            E_USER_ERROR        => LogLevel::ERROR,
            E_USER_WARNING      => LogLevel::WARNING,
            E_USER_NOTICE       => LogLevel::NOTICE,
            E_STRICT            => LogLevel::NOTICE,
            E_RECOVERABLE_ERROR => LogLevel::ERROR,
            E_DEPRECATED        => LogLevel::NOTICE,
            E_USER_DEPRECATED   => LogLevel::NOTICE,
        );
    }

    /**
     * @private
     */
    public function handleException($e)
    {
        $this->logger->log(
            $this->uncaughtExceptionLevel === null ? LogLevel::ERROR : $this->uncaughtExceptionLevel,
            sprintf('Uncaught Exception %s: "%s" at %s line %s', Utils::getClass($e), $e->getMessage(), $e->getFile(), $e->getLine()),
            array('exception' => $e)
        );

        if ($this->previousExceptionHandler) {
            call_user_fun