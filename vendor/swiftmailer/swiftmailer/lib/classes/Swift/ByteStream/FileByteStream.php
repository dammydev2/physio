     return $this->id;
    }

    /**
     * Get the {@link Swift_Mime_SimpleHeaderSet} for this entity.
     *
     * @return Swift_Mime_SimpleHeaderSet
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * Get the nesting level of this entity.
     *
     * @see LEVEL_TOP, LEVEL_MIXED, LEVEL_RELATED, LEVEL_ALTERNATIVE
     *
     * @return int
     */
    public function getNestingLevel()
    {
        return $this->nestingLevel;
    }

    /**
     * Get the Content-type of this entity.
     *
     * @return string
     */
    public function getContentType()
    {
        return $this->getHeaderFieldModel('Content-Type');
    }

    /**
     * Get the Body Content-type of this entity.
     *
     * @return string
     */
    public function getBodyContentType()
    {
        return $this->userContentType;
    }

    /**
     * Set the Content-type of this entity.
     *
     * @param string $type
     *
     * @return $this
     */
    public function setContentType($type)
    {
        $this->setContentTypeInHeaders($type);
        // Keep track of the value so that if the content-type changes automatically
        // due to added child entities, it can be restored if they are later removed
        $this->userContentType = $type;

        return $this;
    }

    /**
     * Get the CID of this entity.
     *
     * The CID will only be present in headers if a Content-ID header is present.
     *
     * @return string
     */
    public function getId()
    {
        $tmp = (array) $this->getHeaderFieldModel($this->getIdField());

        return $this->headers->has($this->getIdField()) ? current($tmp) : $this->id;
    }

    /**
     * Set the CID of this entity.
     *
     * @param string $id
     *
     * @return $this
     */
    public function setId($id)
    {
        if (!$this->setHeaderFieldModel($this->getIdField(), $id)) {
            $this->headers->addIdHeader($this->getIdField(), $id);
        }
        $this->id = $id;

        return $this;
    }

    /**
     * Get the description of this entity.
     *
     * This value comes from the Content-Description header if set.
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->getHeaderFieldModel('Content-Description');
    }

    /**
     * Set the description of this entity.
     *
     * This method sets a value in the Content-ID header.
     *
     * @param string $description
     *
     * @return $this
     */
    public function setDescription($description)
    {
        if (!$this->setHeaderFieldModel('Content-Description', $description)) {
            $this->headers->addTextHeader('Content-Description', $description);
        }

        return $this;
    }

    /**
     * Get the maximum line length of the body of this entity.
     *
     * @return int
     */
    public function getMaxLineLength()
    {
        return $this->maxLineLength;
    }

    /**
     * Set the maximum line length of lines in this body.
     *
     * Though not enforced by the library, lines should not exceed 1000 chars.
     *
     * @param int $length
     *
     * @return $this
     */
    public function setMaxLineLength($length)
    {
        $this->maxLineLength = $length;

        return $this;
    }

    /**
     * Get all children added to this entity.
     *
     * @return Swift_Mime_SimpleMimeEntity[]
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * Set all children of this entity.
     *
     * @param Swift_Mime_SimpleMimeEntity[] $children
     * @param int                           $compoundLevel For internal use only
     *
     * @return $this
     */
    public function setChildren(array $children, $compoundLevel = null)
    {
        // TODO: Try to refactor this logic
        $compoundLevel = $compoundLevel ?? $this->getCompoundLevel($children);
        $immediateChildren = [];
        $grandchildren = [];
        $newContentType = $this->userContentType;

        foreach ($children as $child) {
            $level = $this->getNeededChildLevel($child, $compoundLevel);
            if (empty($immediateChildren)) {
                //first iteration
                $immediateChildren = [$child];
            } else {
                $nextLevel = $this->getNeededChildLevel($immediateChildren[0], $compoundLevel);
                if ($nextLevel == $level) {
                    $immediateChildren[] = $child;
                } elseif ($level < $nextLevel) {
                    // Re-assign immediateChildren to grandchildren
                    $grandchildren = array_merge($grandchildren, $immediateChildren);
                    // Set new children
                    $immediateChildren = [$child];
                } else {
                    $grandchildren[] = $child;
                }
            }
        }

        if ($immediateChildren) {
            $lowestLevel = $this->getNeededChildLevel($immediateChildren[0], $compoundLevel);

            // Determine which composite media type is needed to accommodate the
            // immediate children
            foreach ($this->compositeRanges as $mediaType => $range) {
                if ($lowestLevel > $range[0] && $lowestLevel <= $range[1]) {
                    $newContentType = $mediaType;

                    break;
                }
            }

            // Put any grandchildren in a subpart
            if (!empty($grandchildren)) {
                $subentity = $this->createChild();
                $subentity->setNestingLevel($lowestLevel);
                $subentity->setChildren($grandchildren, $compoundLevel);
                array_unshift($immediateChildren, $subentity);
            }
        }

        $this->immediateChildren = $immediateChildren;
        $this->children = $children;
        $this->setContentTypeInHeaders($newContentType);
        $this->fixHeaders();
        $this->sortChildren();

        return $this;
    }

    /**
     * Get the body of this entity as a string