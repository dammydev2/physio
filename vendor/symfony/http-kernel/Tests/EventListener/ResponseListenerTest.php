IsUnchangedWhenThereIsNoEmbeddedResponse()
    {
        $cacheStrategy = new ResponseCacheStrategy();

        $masterResponse = new Response();
        $masterResponse->setLastModified(new \DateTime());
        $cacheStrategy->update($masterResponse);

        $this->assertTrue($masterResponse->isValidateable());
    }

    public function testMasterResponseWithExpirationIsUnchangedWhenThereIsNoEmbeddedResponse()
    {
        $cacheStrategy = new ResponseCacheStrategy();

        $masterResponse = new Response();
        $masterResponse->setSharedMaxAge(3600);
        $cacheStrategy->update($masterResponse);

        $this->assertTrue($masterResponse->isFresh());
    }

    public function testMasterResponseIsNotCacheableWhenEmbeddedResponseIsNotCacheable()
    {
        $cacheStrategy = new ResponseCacheStrategy();

        $masterResponse = new Response();
        $masterResponse->setSharedMaxAge(3600); // Public, cacheable

        /* This response has no validation or expiration information.
           That makes it uncacheable, it is always stale.
           (It does *not* make this private, though.) */
        $embeddedResponse = new Response();
        $this->assertFalse($embeddedResponse->isFresh()); // not fresh, as no lifetime is provided

        $cacheStrategy->add($embeddedResponse);
        $cacheStrategy->update($masterResponse);

        $this->assertTrue($masterResponse->headers->hasCacheControlDirective('no-cache'));
        $this->assertTrue($masterResponse->headers->hasCacheControlDirective('must-revalidate'));
        $this->assertFalse($masterResponse->isFresh());
    }

    public function testEmbeddingPrivateResponseMakesMainResponsePrivate()
    {
        $cacheStrategy = new ResponseCacheStrategy();

        $masterResponse = new Response();
        $masterResponse->setSharedMaxAge(3600); // public, cacheable

        // The embedded response might for example contain per-user data that remains valid for 60 seconds
        $embeddedResponse = new Response();
        $embeddedResponse->setPrivate();
        $embeddedResponse->setMaxAge(60); // this would implicitly set "private" as well, but let's be explicit

        $cacheStrategy->add($embeddedResponse);
        $cacheStrategy->update($masterResponse);

        $this->assertTrue($masterResponse->headers->hasCacheControlDirective('private'));
        $this->assertFalse($masterResponse->headers->hasCacheControlDirective('public'));
    }

    public function testEmbeddingPublicResponseDoesNotMakeMainResponsePublic()
    {
        $cacheStrategy = new ResponseCacheStrategy();

        $masterResponse = new Response();
        $masterResponse->setPrivate(); // this is the default, but let's be explicit
        $masterResponse->setMaxAge(100);

        $embeddedResponse = new Response();
        $embeddedResponse->setPublic();
        $embeddedResponse->setSharedMaxAge(100);

        $cacheStrategy->add($embeddedResponse);
        $cacheStrategy->update($masterResponse);

        $this->assertTrue($masterResponse->headers->hasCacheControlDirective('private'));
        $this->assertFalse($masterResponse->headers->hasCacheControlDirective('public'));
    }

    public function testResponseIsExiprableWhenEmbeddedResponseCombinesExpiryAndValidation()
    {
        /* When "expiration wins over validation" (https://symfony.com/doc/current/http_cache/validation.html)
         * and both the main and embedded r