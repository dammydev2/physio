          continue;
            }

            $hasRequiredScheme = !$requiredSchemes || isset($requiredSchemes[$context->getScheme()]);
            if ($requiredMethods && !isset($requiredMethods[$canonicalMethod]) && !isset($requiredMethods[$requestMethod])) {
                if ($hasRequiredScheme) {
                    $allow += $requiredMethods;
                }
                continue;
            }

            if (!$hasRequiredScheme) {
                $allowSchemes += $requiredSchemes;
                continue;
            }

            return $ret;
        }

        $matchedPathinfo = $this->matchHost ? $host.'.'.$pathinfo : $pathinfo;

        foreach ($this->regexpList as $offset => $regex) {
            while (preg_match($regex, $matchedPathinfo, $matche