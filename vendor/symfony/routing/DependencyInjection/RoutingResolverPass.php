P code used to match it against the path info.
     */
    private function compileRoute(Route $route, string $name, $vars, bool $hasTrailingSlash, bool $hasTrailingVar, array &$conditions): array
    {
        $defaults = $route->getDefaults();

        if (isset($defaults['_canonical_route'])) {
            $name = $defaults['_canonical_route'];
            unset($defaults['_canonical_route']);
        }

        if ($condition = $route->getCondition()) {
            $condition = $this->getExpressionLanguage()->compile($condition, ['context', 'request']);
            $condition = $conditions[$condition] ?? $conditions[$condition] = (false !== strpos($condition, '$request') ? 1 : -1) * \count($conditions);
        } else {
            $condition = null;
        }

        return [
            ['_route' => $name] + $defaults,
            $vars,
            array_flip($route->getMethods()) ?: null,
            array_flip($route->getSchemes()) ?: null,
            $hasTrailingSlash,
            $hasTrailingVar,
            $condition,
        ];
    }

    private function getExpressionLanguage()
    {
        if (null === $this->expressionLanguage) {
            if (!class_exists('Symfony\Component\ExpressionLanguage\ExpressionLanguage')) {
                throw new \LogicException('Unable to use expressions as the Symfony ExpressionLanguage component is not installed.');
            }
            $this->expressionLanguage = new ExpressionLanguage(null, $this->expressionLanguag