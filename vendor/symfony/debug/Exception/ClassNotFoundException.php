stopwatch->start($eventName, 'section');
                try {
                    $this->dispatcher->dispatch($eventName, $event);
                } finally {
                    if ($e->isStarted()) {
                        $e->stop();
                    }
                }
            } finally {
                $this->postDispatch($eventName, $event);
            }
        } finally {
            $this->postProcess($eventName);
        }

        return $event;
    }

    /**
     * {@inheritdoc}
     */
    public function getCalledListeners()
    {
        if (null === $this->callStack) {
            return [];
        }

        $called = [];
        foreach ($this->callStack as $listener) {
            list($eventName) = $this->callStack->getInfo();

            $called[] = $listener->getInfo($eventName);
        }

        return $called;
    }

    /**
     * {@inh