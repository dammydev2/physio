annot resolve statically
            return null;
        }

        // if no alias exists prepend current namespace
        return FullyQualified::concat($this->namespace, $name, $name->getAttributes());
    }

    /**
     * Get resolved class name.
     *
     * @param Name $name Class ame to resolve
     *
     * @return Name Resolved name
     */
    public function getResolvedClassName(Name $name) : Name {
        return $this->getResolvedName($name, Stmt\Use_::TYPE_NORMAL);
    }

    /**
     * Get possible ways of writing a fully qualified name (e.g., by making use of aliases).
     *
     * @param string $name Fully-qualified name (without leading namespace separator)
     * @param int    $type One of Stmt\Use_::TYPE_*
     *
     * @return Name[] Possible representations of the name
     */
    public function getPossibleNames(string $name, int $type) : array {
        $lcName = strtolower($name);

        if ($type === Stmt\Use_::TYPE_NORMAL) {
            // self, parent and static must always be unqualified
            if ($lcName === "self" || $lcName === "parent" || $lcName === "static") {
                return [new Name($name)];
            }
        }

        // Collect possible ways to write this name, starting with the fully-qualified name
        $possibleNames = [new FullyQualified($name)];

        if (null !== $nsRelativeName = $this->getNamespaceRelativeName($name, $lcName, $type)) {
            // Make sure there is no alias that makes the normally namespace-relative name
            // into something else
            if (null === $this->resolveAlias($nsRelativeName, $type)) {
                $possibleNames[] = $nsRelativeName;
            }
        }

        // Check for relevant namespace use statements
        foreach ($this->origAliases[Stmt\Use_::TYPE_NORMAL] as $alias => $orig) {
            $lcOrig = $orig->toLowerString();
            if (0 === strpos($lcName, $lcOrig . '\\')) {
                $possibleNames[] = new Name($alias . substr($name, strlen($lcOrig)));
            }
        }

        // Check for relevant type-specific use statements
        foreach ($this->origAliases[$type] as $alias => $orig) {
            if ($type === Stmt\Use_::TYPE_CONSTANT) {
                // Constants are are complicated-sensitive
                $normalizedOrig = $this->normalizeConstName($orig->toString());
                if ($normalizedOrig === $this->normalizeConstName($name)) {
                    $possibleNames[] = new Name($alias);
                }
            } else {
                // Everything else is case-insensitive
                if ($orig->toLowerString() === $lcName) {
                    $possibleNames[] = new Name($alias);
                }
            }
        }

        return $possibleNames;
    }

    /**
     * Get shortest representation of this fully-qualified name.
     *
     * @param string $name Fully-qualified name (without leading namespace separator)
     * @param int    $type One of Stmt\Use_::TYPE_*
     *
     * @return Name Shortest representation
     */
    public function getShortName(string $name, int $type) : Name {
        $possibleNames = $this->getPossibleNames($name, $type);

        // Find shortest name
        $shortestName = null;
        $shortestLength = \INF;
        foreach ($possibleNames as $possibleName) {
            $length = strlen($possibleName->toCodeString());
            if ($length < $shortestLength) {
                $shortestName = $possibleName;
                $shortestLength = $length;
            }
        }

       return $shortestName;
    }

    private function resolveAlias(Name $name, $type) {
        $firstPart = $name->getFirst();

        if ($name->isQualified()) {
            // resolve aliases for qualified names, always against class alias table
            $checkName = strtolower($firstPart);
            if (isset($this->aliases[Stmt\Use_::TYPE_NORMAL][$checkName])) {
                $alias = $this->aliases[Stmt\Use_::TYPE_NORMAL][$checkName];
                return FullyQualified::concat($alias, $name->slice(1), $name->getAttributes());
            }
