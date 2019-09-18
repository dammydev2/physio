ass name
     *
     * @return object
     */
    protected function instantiateController($class)
    {
        return new $class();
    }

    private function getControllerError($callable)
    {
        if (\is_string($callable)) {
            if (false !== strpos($callable, '::')) {
                $callable = explode('::', $callable, 2);
            } else {
                return sprintf('Function "%s" does not exist.', $callable);
            }
        }

        if (\is_object($callable)) {
            $availableMethods = $this->getClassMethodsWithoutMagicMethods($callable);
            $alternativeMsg = $availableMethods ? sprintf(' or use one of the available methods: "%s"', implode('", "', $availableMethods)) : '';

            return sprintf('Controller class "%s" cannot be called without a method name. You need to implement "__invoke"%s.', \get_class($callable), $alternativeM