                 ->with('multipart/related');
            $cType->shouldReceive('setFieldBodyModel')
                  ->zeroOrMoreTimes();

            $entity = $this->createEntity($this->createHeaderSet([
                'Content-Type' => $cType, ]),
                $this->createEncoder(), $this->createCache()
                );
            $entity->setChildren([$child]);
        }
    }

    public function testHighestLevelChildDeterminesContentType()
    {
        $combinations = [
            ['levels' => [Swift_Mime_SimpleMimeEntity::LEVEL_MIXED,
                Swift_Mime_SimpleMimeEntity::LEVEL_ALTERNATIVE,
                Swift_Mime_SimpleMimeEntity::LEVEL_RELATED,
                ],
                'type' => 'multipart/mixed',
                ],
            ['levels' => [Swift_Mime_SimpleMimeEntity::LEVEL_MIXED,
                Swift_Mime_SimpleMimeEntity::LEVEL_RELATED,
                ],
                'type' => 'multipart/mixed',
                ],
            ['levels' => [Swift_Mime_SimpleMimeEntity::LEVEL_MIXED,
                Swift_Mime_SimpleMimeEntity::LEVEL_ALTERNATIVE,
                ],
                'type' => 'multipart/mixed',
                ],
            ['levels' => [Swift_M