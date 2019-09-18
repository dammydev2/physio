SEPARATOR . $file;
    }

    /**
     * Returns a path to the example file in the given directory..
     *
     * @param string $directory
     * @param string $file
     *
     * @return string
     */
    private function constructExamplePath($directory, $file)
    {
        return rtrim($directory, '\\/') . DIRECTORY_SEPARATOR . $file;
    }

    /**
     * Get example filepath based on sourcecode.
     *
     * @param string $file
     *
     * @return string
     */
    private function getExamplePathFromSource($file)
    {
        return sprintf(
            '%s%s%s',
            trim($this->getSourceDirectory(), '\\/'),
            DIRECTORY_SEPARATOR,
            trim($file, '"')
        );
