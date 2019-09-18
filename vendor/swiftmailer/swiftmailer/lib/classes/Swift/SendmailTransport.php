   {
        parent::fixHeaders();
        if (count($this->getChildren())) {
            $this->setHeaderParameter('Content-Type', 'charset', null);
            $this->setHeaderParameter('Content-Type', 'format', null);
            $this->setHeaderParameter('Content-Type', 'delsp', null);
        } else {
            $this->setCharset($this->userCharset);
            $this->setFormat($this->userFormat);
            $this->setDelSp($this->userDelSp);
        }
    }

    /** Set the nesting level of this entity */
    protected function setNestingLevel($level)
    {
        $this->nestingLevel = $level;
    }

    /** Encode charset when charset is not utf-8 */
    protected function convertString($string)
    {
        $charset = strtolower($this->getCharset());
        if (!in_array($charset, ['utf-8', 'iso-8859-1', 'iso-8859-15', ''])) {
            return mb_convert_en