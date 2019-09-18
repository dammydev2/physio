       }
        } elseif (isset($this->arguments['bootstrap'])) {
            $this->handleBootstrap($this->arguments['bootstrap']);
        }

        if (isset($this->arguments['printer']) &&
            \is_string($this->arguments['printer'])) {
            $this->arguments['printer'] = $this->handlePrinter($this->arguments['printer']);
        }

        if (isset($this->arguments['test']) && \is_string($this->arguments['test']) && \substr($this->arguments['test'], -5, 5) == '.phpt') {
            $test = new PhptTestCase($this->arguments['test']);

            $this->arguments['test'] = new TestSuite;
            $this->arguments['test']->addTest($test);
        }

        if (!isset($this->arguments['test'])) {
            $this->showHelp();
            exit(TestRunner::EXCEPTION_EXIT);
        }
    }

    /**
     * Handles the loading of the PHPUnit\Runner\TestSuiteLoader implementation.
     */
    protected function handleLoader(string $loaderClass, string $loaderFile = ''): ?TestSuiteLoader
    {
        if (!\class_exists($loa