'retryAfter') && ! isset($job->retryAfter)) {
            return;
        }

        $delay = $job->retryAfter ?? $job->retryAfter();

        return $delay instanceof DateTimeInterface
                        ? $this->secondsUntil($delay) : $delay;
    }

    /**
     * Get the expiration timestamp for an object-based queue handler.
     *
     * @param  mixed  $job
     * @return mixed
     */
    public function getJobExpiration($job)
    {
        if (! method_exists($job, 'retryUntil') && ! isset($job->timeoutAt)) {
            return;
        }

        $expiration = $job->timeoutAt ?? $job->retryUntil();

        return $expiration instanceof DateTimeInterface
                        ? $expiration->getTimestamp() : $expiration;
    }

    /**
     * Create a typical, string based queue payload array.
     *
     * @param  string  $job
     * @param  string  $queue
     * @param  mixed  $data
     * @return array
     */
    protected function createStringPayload($job, $queue, $data)
    {
        return $this->withCreatePayloadHooks($queue, [
            'displayName' => is_string($job) ? explode('@', $job)[0] : null,
            'job' => $job,
            'maxTries' => null,
            'delay' => null,
            'timeout' => null,
            'data' => $data,
        ]);
    }

    /**
     * Register a callback to be executed when creating job payl