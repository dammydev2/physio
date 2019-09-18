*/
    public function shouldBeCalledOnce()
    {
        return $this->shouldBeCalledTimes(1);
    }

    /**
     * Checks provided prediction immediately.
     *
     * @param callable|Prediction\PredictionInterface $prediction
     *
     * @return $this
     *
     * @throws \Prophecy\Exception\InvalidArgumentException
     */
    public function shouldHave($prediction)
    {
        if (is_callable($prediction)) {
            $prediction = new Prediction\CallbackPrediction($prediction);
        }

        if (!$prediction instanceof Prediction\PredictionInterface) {
            throw new InvalidArgumentException(sprintf(
                'Expected callable or instance of PredictionInterface, but got %s.',
                gettype($prediction)
            ));
        }

        if (null === $this->promise && !$this->voidReturnType) {
            $this->willReturn();
        }

        $calls = $this->getObjectProphecy()->findProphecyMethodCalls(
            $this