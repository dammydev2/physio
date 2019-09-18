       \sprintf(
                            'Class "%s" does not exist',
                            $listener['class']
                        )
                    );
                }

                $listenerClass = new ReflectionClass($listener['class']);

                if (!$listenerClass->implementsInterface(TestListener::class)) {
                    throw new Exception(
                        \sprintf(
                            'Class "%s" does not implement the PHPUnit\Framework\TestListener interface',
                            $listener['class']
                        )
                    );
                }

                if (\count($listener['arguments']) == 0) {
                    $listener = new $listener['class'];
                } else {
                    $listener = $listenerClass->newInstanceArgs(
                        $listener['arguments']
                    );
                }

                $arguments['listeners'][] = $listener;
            }

            $loggingConfiguration = $arguments['configuration']->getLoggingConfiguration();

            if (isset($loggi