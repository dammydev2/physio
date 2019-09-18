->delaySeconds())
                                ? $job->delaySeconds()
                                : $options->delay
                );
            }
        }

        throw $e;
    }

    /**
     * Mark the given job as failed if it has exceeded the maximum allowed attempts.
     *
     * This will likely be because the job previously exceeded a timeout.
     *
     * @param  string  $connectionName
     * @param  \Illuminate\Contracts\Queue\Job  $job
     * @param  int  $maxTries
     * @return void
     */
    protected function markJobAsFailedIfAlreadyExceedsMaxAttempts($connectionName, $job, $maxTries)
    {
        $maxTries = ! is_null($job->maxTries()) ? $job->maxTries() : $maxTries;

        $timeoutAt = $job->timeoutAt();

        if ($timeoutAt && Carbon::now()->getTimestamp() <= $timeoutAt) {
            return;
        }

        if (! $timeoutAt && ($maxTries === 0 || $job->attempts() <= $maxTries)) {
            return;
        }

        $this->failJob($job, $e = new MaxAttemptsExceededException(
            $job->resolveName().' has been attempted too many times or run too long. The job may have p