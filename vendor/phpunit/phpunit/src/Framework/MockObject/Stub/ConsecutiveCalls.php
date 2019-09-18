

    /**
     * Returns the configuration for SUT filtering.
     */
    public function getFilterConfiguration(): array
    {
        $addUncoveredFilesFromWhitelist     = true;
        $processUncoveredFilesFromWhitelist = false;
        $includeDirectory                   = [];
        $includeFile                        = [];
        $excludeDirectory                   = [];
        $excludeFile                        = [];

        $tmp = $this->xpath->query('filter/whitelist');

        if ($tmp->length === 1) {
            if ($tmp->item(0)->hasAttribute('addUncoveredFilesFromWhitelist')) {
                $addUncoveredFilesFromWhitelist = $this->getBoolean(
                    (string) $tmp->item(0)->getAttribute(
                        'addUncoveredFilesFromWhitelist'
                    ),
                    true
                );
            }

            if ($tmp->item(0)->hasAttribute('processUncoveredFilesFromWhitelist')) {
                $processUncoveredFilesFromWhitelist = $this->getBoolean(
                    (string) $tmp->item(0)->getAttribute(
                        'processUncoveredFilesFromWhitelist'
                    ),
                