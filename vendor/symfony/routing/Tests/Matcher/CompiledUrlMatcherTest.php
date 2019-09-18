doc}
     */
    public function getLocale()
    {
        return $this->locale;
    }

    /**
     * Sets the fallback locales.
     *
     * @param array $locales The fallback locales
     *
     * @throws InvalidArgumentException If a locale contains invalid characters
     */
    public function setFallbackLocales(array $locales)
    {
        // needed as the fallback locales are linked to the already loaded catalogues
        $this->catalogues = [];

        foreach ($locales as $locale) {
            $this->assertValidLocale($locale);
        }

        $this->fallbackLocales = $locales;
    }

    /**
     * Gets the fallback locales.
     *
     * @internal since Symfony 4.2
     *
     * @return array The fallback locales
     */
    public function getFallbackLocales()
    {
        return $this->fallbackLocales;