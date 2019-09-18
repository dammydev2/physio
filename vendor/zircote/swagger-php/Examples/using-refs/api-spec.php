    $item = array_pop($queue);
        $queue = array_merge($queue, $item[2]);

        /** @var Response $response */
        $response = $item[1];
        /** @var Response $parent_obj */
        $parent = $item[0];

        //Reset the ref
        $response->ref = null;

        if (is_null($parent)) {
            return;
        }

        $parent_obj = $parent[1];

        foreach ($parent_obj as $key => $value) {
            if ($key == 'schema') {
                if (!is_null($value)) {
                    $response->schema = $response->schema ?: new Schema([]);
                    $this->importSchema($value, $response->schema);
                }
            } elseif (!in_array($key, array_keys($this->import_in_order)) && property_exists($response, $key)) {
                if (is_array($value)) {
                    $response->$key = array_merge($value, $response->$key ?: []);
                } elseif (!isset($response->$key) && $key != $current_key || $key == 'ref') {
                    $response->$key = $value;
                }
            }
        }
    }

    /**
     * Imports the schema
     *
     * @param Schema $parent
     * @param Schema $child
     */
    private function importSchema(Schema $parent, Schema $child)
    {
        $temp = [];

        // add all in a temporary array
        if (!is_null($child->properties)) {
            foreach ($child->properties as $value) {
                $temp[$value->property] = $value;
            }
        }

        // reset the properties
        $child->properties = [];

        foreach ($parent as $key => $value) {
            if ($key == 'properties' && is_array($value)) {
                /** @var Property[] $value */
                foreach ($valu