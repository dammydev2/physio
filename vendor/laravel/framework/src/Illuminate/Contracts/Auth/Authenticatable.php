     * Set the query grammar to the default implementation.
     *
     * @return void
     */
    public function useDefaultQueryGrammar()
    {
        $this->queryGrammar = $this->getDefaultQueryGrammar();
    }

    /**
     * Get the default query grammar instance.
     *
     * @return \Illuminate\Database\Query\Grammars\Grammar
     */
    protected function getDefaultQueryGrammar()
    {
        return new QueryGrammar;
    }

    /**
     * Set the schema grammar to the default implementation.
     *
     * @return void
     */
    public function useDefaultSchemaGrammar()
    {
        $this->schemaGrammar = $this->getDefaultSchemaGrammar();
    }

    /**
     * Get the default schema grammar instance.
     *
     * @return \Illuminate\Database\Schema\Grammars\Grammar
     */
    protected function getDefaultSchemaGrammar()
    {
        //
    }

    /**
     * Set the query post processor to the default implementation.
  