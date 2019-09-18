tring|null
     */
    public function phpdocSummary()
    {
        $content = $this->phpdocContent();
        if (!$content) {
            return null;
        }
        $lines = preg_split('/(\n|\r\n)/', $content);
        $summary = '';
        foreach ($lines as $line) {
            $summary .= $line."\n";
            if ($line === '' || substr($line, -1) === '.') {
                return trim($summary);
            }
        }
        $summary = trim($summary);
        if ($summary === '') {
            return null;
        }
        return $summary;
    }
    
    /**
     * An optional longer piece of text providing more details on the associated element’s function. This is very useful when working with a complex element.
     * @return string|null
     */
    public function phpdocDescription()
    {
        $summary = $this->phpdocSummary();
        if (!$summary) {
            return null;
        }
        $description = trim(substr($this->phpdocContent(), strlen($summary)));
        if ($des