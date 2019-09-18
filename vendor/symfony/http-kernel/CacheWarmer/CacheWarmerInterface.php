ice "%s".', $k, $this->controllerTag, json_encode($attributes, JSON_UNESCAPED_UNICODE), $id));
                    }
                }
                if (!isset($methods[$action = strtolower($attributes['action'])])) {
                    throw new InvalidArgumentException(sprintf('Invalid "action" attribute on tag "%s" for service "%s": no public "%s()" method found on class "%s".', $this->controllerTag, $id, $attributes['action'], $class));
                }
                list($r, $parameters) = $methods[$action];
                $found = false;

                foreach ($parameters as $p) {
                    if ($attributes['argument'] === $p->name) {
                        if (!isset($arguments[$r->name][$p->name])) {
                            $arguments[$r->name][$p->name] = $attributes['id'];
  