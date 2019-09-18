* @var MethodProphecy[] $methodProphecies */
                    foreach ($methodProphecies as $methodProphecy) {
                        $this->numAssertions += \count($methodProphecy->getCheckedPredictions());
                    }
                }
            }

            if (isset($t)) {
                throw $t;
            }
        }
    }

    private function handleDependencies(): bool
    {
        if (!empty($this->dependencies) && !$this->inIsolation) {
            $className  = \get_class($this);
            $passed     = $this->result->passed();
            $passedKeys = \array_keys($passed);
            $numKeys    = \count($passedKeys);

            for ($i = 0; $i < $numKeys; $i++) {
                $pos = \strpos($passedKeys[$i], ' with data set');

                if ($pos !== false) {
                    $passedKeys[$i] = \substr($passedKeys[$i], 0, $pos);
                }
            }

            $passedKeys = \array_flip(\array_unique($passedKeys));

            foreach ($this->dependencies as $dependency) {
                $deepClone    = false;
                $shallowClone = false;

                if (\strpos($dependency, 'clone ') === 0) {
                    $deepClone  = true;
                    $dependency = \substr($dependency, \strlen('clone '));
                } elseif (\strpos($dependency, '!clone ') === 0) {
                    $deepClone  = false;
                    $dependency = \substr($dependency, \strlen('!clone '));
                }

                if (\strpos($dependency, 'shallowClone ') === 0) {
                    $shallowClone = true;
                    $dependency   = \substr($dependency, \strlen('shallowClone '));
                } elseif (\strpos($dependency, '!shallowClone ') === 0) {
                    $shallowClone = false;
                    $dependency   = \substr($dependency, \strlen('!shallowClone '));
                }

                if (\strpos($dependency, '::') === false) {
                    $dependency = $className . '::' . $dependency;
                }

                if (!isset($passedKeys[$dependency])) {
                    if (!\is_callable($dependency, false, $callableName) || $dependency !== $callableName) {
                        $this->markWarningForUncallableDependency($dependency);
                    } else {
                        $this->markSkippedForMissingDependecy($dependency);
                    }

                    return false;
                }

                if (isset($passed[$dependency])) {
                    if ($passed[$dependency]['size'] != \PHPUnit\Util\Test::UNKNOWN &&
                        $this->getSize() != \PHPUnit\Util\Test::UNKNOWN &&
                        $passed[$dependency]['size'] > $this->getSize()) {
                        $this->result->addError(
                            $this,
                            new SkippedTestError(
                                'This test depends on a test that is larger than itself.'
                            ),
                            0
                        );

                        return false;
                    }

                    if ($deepClone) {
                        $deepCopy = new DeepCopy;
                        $deepCopy->skipUncloneable(false);

                        $this->dependencyInput[$dependency] = $deepCopy->copy($passed[$dependency]['result']);
                    } elseif ($shallowClone) {
                        $this->dependencyInput[$dependency] = clone $passed[$dependency]['result'];
                    } else {
                        $this->dependencyInput[$dependency] = $passed[$dependency]['result'];
                    }
                } else {
                    $this->dependencyInput[$dependency] = null;
                }
            }
        }

        return true;
    }

    private function markSkippedForMissingDependecy(string $dependency): void
    {
        $this->status = BaseTestRunner::STATUS_SKIPPED;
        $this->result->startTest($this);
        $this->result->addError(
            $this,
            new SkippedTestError(
                \sprintf(
                    'This test depends on "%s" to pass.',
                    $dependency
                )
            ),
            0
        );
        $this->result->endTest($this, 0);
    }

    private function markWarningForUncallableDependency(string $dependency): void
    {
        $this->status = BaseTestRunner::STATUS_WARNING;
        $this->result->startTest($this);
        $this->result->addWarning(
            $this,
            new Warning(
                \sprintf(
                    'This test depends on "%s" which does not exist.',
                    $dependency
                )
            ),
            0
        );
        $this->result->endTest($this, 0);
    }

    /**
     * Get the mock object generator, creating it if it doesn't exist.
     */
    private function getMockObjectGenerator(): MockGenerator
    {
        if ($this->mockObjectGenerator === null) {
            $this->mockObjectGenerator = new MockGenerator;
        }

        return $this->mockObjectGenerator;
    }

    private function startOutputBuffering(): void
    {
        \ob_start();

        $this->outputBufferingActive = true;
        $this->outputBufferingLevel  = \ob_get_level();
    }

    /**
     * @throws RiskyTestError
     */
    private function stopOutputBuffering(): void
    {
        if (\ob_get_level() !== $this->outputBufferingLevel) {
            while (\ob_get_level() >= $this->outputBufferingLevel) {
                \ob_end_clean();
            }

            throw new RiskyTestError(
                'Test code or tested code did not (only) close its own output buffers'
            );
        }

        $this->output = \ob_get_contents();

        if ($this->outputCallback !== false) {
            $this->output = (string) \call_user_func($this->outputCallback, $this->output);
        }

        \ob_end_clean();

        $this->outputBufferingActive = false;
        $this->outputBufferingLevel  = \ob_get_level();
    }

    private function snapshotGlobalState(): void
    {
        if ($this->runTestInSeparateProcess || $this->inIsolation ||
            (!$this->backupGlobals === true && !$this->backupStaticAttributes)) {
            return;
        }

        $this->snapshot = $this->createGlobalStateSnapshot($this->backupGlobals === true);
    }

    /**
     * @throws RiskyTestError
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws \InvalidArgumentException
     */
    private function restoreGlobalState(): void
    {
        if (!$this->snapshot instanceof Snapshot) {
            return;
        }

        if ($this->beStrictAboutChangesToGlobalState) {
            try {
                $this->compareGlobalStateSnapshots(
                    $this->snapshot,
                    $this->createGlobalStateSnapshot($this->backupGlobals === true)
                );
            } catch (RiskyTestError $rte) {
                // Intentionally left empty
            }
        }

        $restorer = new Restorer;

        if ($this->backupGlobals === true) {
            $restorer->restoreGlobalVariables($this->snapshot);
        }

        if ($this->backupStaticAttributes) {
            $restorer->restoreStaticAttributes($this->snapshot);
        }

        $this->snapshot = null;

        if (isset($rte)) {
            throw $rte;
        }
    }

    private function createGlobalStateSnapshot(bool $backupGlobals): Snapshot
    {
        $blacklist = new Blacklist;

        foreach ($this->backupGlobalsBlacklist as $globalVariable) {
            $blacklist->addGlobalVariable($globalVariable);
        }

        if (!\defined('PHPUNIT_TESTSUITE')) {
            $blacklist->addClassNamePrefix('PHPUnit');
            $blacklist->addClassNamePrefix('SebastianBergmann\CodeCoverage');
            $blacklist->addClassNamePrefix('SebastianBergmann\FileIterator');
            $blacklist->addClassNamePrefix('SebastianBergmann\Invoker');
            $blacklist->addClassNamePrefix('SebastianBergmann\Timer');
            $blacklist->addClassNamePrefix('PHP_Token');
            $blacklist->addClassNamePrefix('Symfony');
            $blacklist->addClassNamePrefix('Text_Template');
            $blacklist->ad