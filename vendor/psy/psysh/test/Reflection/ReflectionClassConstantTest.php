verter = $converter;
    }

    /**
     * Returns the UUID builder this factory uses when creating `Uuid` instances
     *
     * @return UuidBuilderInterface $builder
     */
    public function getUuidBuilder()
    {
        return $this->uuidBuilder;
    }

    /**
     * Sets the UUID builder this factory will use when creating `Uuid` instances
     *
     * @param UuidBuilderInterface $builder
     */
    public function setUuidBuilder(UuidBuilderInterface $builder)
    {
        $this->uuidBuilder = $builder;
    }

    /**
     * @inheritdoc
     */
    public function fromBytes($bytes)
    {
        return $this->codec->decodeBytes($bytes);
    }

    /**
     * @inheritdoc
     */
    public function fromString($uuid)
    {
        $uuid = strtolower($uuid);
        return $this->codec->decode($uuid);
    }

    /**
     * @inheritdoc
     */
    public function fromInteger($integer)
    {
        $hex = $this->numberConverter->toHex($integer);
        $hex = str_pad($hex, 32, '0', STR_PAD_LEFT);

        return $this->fromString($hex);
    }

    /**
     * @inheritdoc
     */
    public function uuid1($node = null, $clockSeq = null)
    {
        $bytes = $this->timeGenerator->generate($node, $clockSeq);
        $hex = bin2hex($bytes);

        return $this->uuidFromHashedName($hex, 1);
    }

    /**
     * @inheritdoc
     */
    public function uuid3($ns, $name)
    {
        return $this->uuidFromNsAndName($ns, $name, 3, 'md5');
    }

    /**
     * @inheritdoc
     */
    public function uuid4()
    {
        $bytes = $this->randomGenerator->generate(16);

        // When converting the bytes to hex, it turns into a 32-character
        // hexadecimal string that looks a lot like an MD5 hash, so at this
        // point, we can just pass it to uuidFromHashedName.
        $hex = bin2hex($bytes);

        return $this->uuidFromHashedName($hex, 4);
    }

    /**
     * @inheritdoc
     */
    public function uuid5($ns, $name)
    {
        return $this->uuidFromNsAndName($ns, $name, 5, 'sha1');
    }

    /**
     * Returns a `Uuid`
     *
     * Uses the configured builder and codec and the provided array of hexadecimal
     * value UUID fields to construct a `Uuid` object.
     *
     * @param array $fields An array of fields from which to construct a UUID;
     *     see {@see \Ramsey\Uuid\UuidInte