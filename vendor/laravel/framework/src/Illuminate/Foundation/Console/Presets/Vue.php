ted function getCookie($cookieName)
    {
        foreach ($this->headers->getCookies() as $cookie) {
            if ($cookie->getName() === $cookieName) {
                return $cookie;
            }
        }
    }

    /**
     * Assert that the given string is contained within the response.
     *
     * @param  string  $value
     * @return $this
     */
    public function assertSee($value)
    {
        PHPUnit::assertStringContainsString((string) $value, $this->getContent());

        return $this;
    }

    /**
     * Assert that the given strings are contained in order within the response.
     *
     * @param  array  $values
     * @return $this
     */
    public function assertSeeInOrder(array $values)
    {
        PHPUnit::assertThat($values, new SeeInOrder($this->getContent()));

        return $this;
    }

    /**
     * Assert that the given string is contained within the response text.
     *
     * @param  string  $value
     * @return $this
     */
    public function assertSeeText($value)
    {
        PHPUnit::assertStringContainsString((string) $value, strip_tags($this->getContent()));

        return $this;
    }

    /**
     * Assert that the given strings are contained in order within the response text.
     *
     * @param  array  $values
     * @return $this
     */
    public function assertSeeTextInOrder(array $values)
    {
        PHPUnit::assertThat($values, new SeeInOrder(strip_tags($this->getContent())));

        return $this;
    }

    /**
     * Assert that the given string is not contained within the response.
     *
     * @param  string  $value
     * @return $this
     */
    public function assertDontSee($value)
    {
       