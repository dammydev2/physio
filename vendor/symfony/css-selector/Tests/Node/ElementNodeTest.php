\\b/", $doc))) {
                        $deprecations[] = sprintf($deprecation, $class);
                    }
                }
            }

            if (!$doc) {
                continue;
            }

            $finalOrInternal = false;

            foreach (['final', 'internal'] as $annotation) {
                if (false !== \strpos($doc, $annotation) && preg_match('#\n\s+\* @'.$annotation.'(?:( .+?)\.?)?\r?\n\s+\*(?: @|/$|\r?\n)#s', $doc, $notice)) {
                    $message = isset($notice[1]) ? preg_replace('#\.?\r?\n( \*)? *(?= |\r?\n|$)#', '', $notice[1]) : '';
                    self::${$annotation.'Methods'}[$class][$method->name] = [$class, $message];
                    $finalOrInternal = true;
                }
            }

            if ($finalOrInternal || $method->isConstructor() || false === \strpos($doc, '@param') || StatelessInvocation::class === $class) {
                