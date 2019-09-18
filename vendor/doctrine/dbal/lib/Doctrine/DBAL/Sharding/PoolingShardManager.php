'cantus', 'carp', 'chassis', 'clippers', 'cod', 'coitus', 'compensation', 'Congoese',
        'contretemps', 'coreopsis', 'corps', 'data', 'debris', 'deer', 'diabetes', 'djinn', 'education', 'eland',
        'elk', 'emoji', 'equipment', 'evidence', 'Faroese', 'feedback', 'fish', 'flounder', 'Foochowese',
        'Furniture', 'furniture', 'gallows', 'Genevese', 'Genoese', 'Gilbertese', 'gold', 
        'headquarters', 'herpes', 'hijinks', 'Hottentotese', 'information', 'innings', 'jackanapes', 'jedi',
        'Kiplingese', 'knowledge', 'Kongoese', 'love', 'Lucchese', 'Luggage', 'mackerel', 'Maltese', 'metadata',
        'mews', 'moose', 'mumps', 'Nankingese', 'news', 'nexus', 'Niasese', 'nutrition', 'offspring',
        'Pekingese', 'Piedmontese', 'pincers', 'Pistoiese', 'plankton', 'pliers', 'pokemon', 'police', 'Portuguese',
        'proceedings', 'rabies', 'rain', 'rhinoceros', 'rice', 'salmon', 'Sarawakese', 'scissors', 'sea[- ]bass',
        'series', 'Shavese', 'shears', 'sheep', 'siemens', 'species', 'staff', 'swine', 'traffic',
        'trousers', 'trout', 'tuna', 'us', 'Vermontese', 'Wenchowese', 'wheat', 'whiting', 'wildebeest', 'Yengeese'
    );

    /**
     * Method cache array.
     *
     * @var array
     */
    private static $cache = array();

    /**
     * The initial state of Inflector so reset() works.
     *
     * @var array
     */
    private static $initialState = array();

    /**
     * Converts a word into the format for a Doctrine table name. Converts 'ModelName' to 'model_name'.
     */
    public static function tableize(string $word) : string
    {
        return strtolower(preg_replace('~(?<=\\w)([A-Z])~', '_$1', $word));
    }

    /**
     * Converts a word into the format for a Doctrine class name. Converts 'table_name' to 'TableName'.
     */
    public static function classify(string $word) : string
    {
        return str_replace([' ', '_', '-'], '', ucwords($word, ' _-'));
    }

    /**
     * Camelizes a word. This uses the classify() method and turns the first character to lowercase.
     */
    public static function camelize(string $word) : string
    {
        return lcfirst(self::classify($word));
    }

    /**
     * Uppercases words with configurable delimeters between words.
     