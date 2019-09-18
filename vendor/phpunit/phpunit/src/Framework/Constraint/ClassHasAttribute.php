($invocation)) {
                $this->parametersMatcher->verify();
            }
        } catch (ExpectationFailedException $e) {
            throw new ExpectationFailedException(
                \sprintf(
                    "Expectation failed for %s when %s\n%s",
                    $this->methodNameMatcher->toString(),
                    $this->invocationMatcher->toString(),
                    $e->getMessage()
                ),
                $e->getComparisonFailure()
            );
        }

        if ($this->stub) {
            return $this->stub->invoke($invocation);
        }

        return $invocation->generateReturnValue();
    }

    /**
     * @throws RuntimeException
     * @throws ExpectationFailedException
     *
     * @return bool
     */
    public function matches(Invocation $invocation)
    {
        if ($this->afterMatchBuilderId !== null) {
            $builder = $invocation->getObject()
                                  ->__phpunit_getInvocationMocker()
                                  ->lookupId($this->afterMatchBuilderId);

            if (!$builder) {
                throw new RuntimeException(
                    \sprintf(
                        'No builder found for match builder identification <%s>',
                        $this->afterMatchBuilderId
                    )
                );
            }

            $matcher = $builder->getMatcher();

            if (!$matcher) {
                return false;
            }

            if (!$matcher->invocationMatcher->hasBeenInvoked()) {
                return false;
            }
        }

        if ($this->invocationMatcher === null) {
            throw new RuntimeException(
                'No invocation matcher is set'
            );
        }

        if ($this->methodNameMatcher === null) {
            throw new RuntimeException('No method matcher is set');
        }

        if (!$this->invocationMatcher->mat