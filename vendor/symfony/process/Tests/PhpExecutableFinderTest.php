<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\Routing\Generator\Dumper;

use Symfony\Component\Routing\Matcher\Dumper\CompiledUrlMatcherDumper;

/**
 * CompiledUrlGeneratorDumper creates a PHP array to be used with CompiledUrlGenerator.
 *
 * @author Fabien Potencier <fabien@symfony.com>
 * @author Tobias Schultze <http://tobion.de>
 * @author Nicolas Grekas <p@tchwork.com>
 */
class CompiledUrlGeneratorDumper extends GeneratorDumper
{
    public function getCompiledRoutes(): array
    {
        $compiledRoutes = [];
        foreach ($this->getRoutes()->all() as $name => $route) {
            $compiledRoute = $route->compile();

            $compiledRoutes[$name] = [
                $compiledRoute->getVariables(),
                $route->getDefaults(),
                $route->getRequirements(),
                $compiledRoute->getTokens(),
                $compiledRoute->getHostTokens(),
                $route->getSchemes(),
            ];
        }

        return $compiledRoutes;
    }

    /**
     * {@inheritdoc}
     */
    public function dump(array $options = [])
    {
        return <<<EOF
<?php
