(Invoker::class)) {
                $this->writeMessage('Error', 'Package phpunit/php-invoker is required for enforcing time limits');
            }

            if (!\extension_loaded('pcntl') || \strpos(\ini_get('disable_functions'), 'pcntl') !== false) {
                $this->writeMessage('Error', 'PHP extension pcntl is required for enforcing time limits');
            }
        }
        $result->enforceTimeLimit($arguments['enforceTimeLimit']);
        $result->setDefaultTimeLimit($arguments['defaultTimeLimit']);
        $result->setTimeoutForSmallTests($arguments['timeoutForSmallTests']);
        $result->setTimeoutForMediumTests($arguments['timeoutForMediumTests']);
        $result->setTimeoutForLargeTests($arguments['timeoutForLargeTests']);

        if ($suite instanceof TestSuite) {
    