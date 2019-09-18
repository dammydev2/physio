ablePatterns()
        {
            return [
                '[a-z_][a-z0-9_]*\:[a-z_][a-z0-9_]*(?:\\\[a-z_][a-z0-9_]*)*', // aliased name
                '[a-z_\\\][a-z0-9_]*(?:\\\[a-z_][a-z0-9_]*)*', // identifier or qualified name
                '(?:[0-9]+(?:[\.][0-9]+)*)(?:e[+-]?[0-9]+)?', // numbers
                "'(?:[^']|'')*'", // quoted strings
                '\?[0-9]*|:[a-z_][a-z0-9_]*', // parameters
            ];
        }

        /**
         * {@inheritdoc}
         */
        protected function getNonCatchablePatterns()
        {
            return ['\s+', '(.)'];
        }

        /**
         * {@inheritdoc}
         */
        protected function getType(&$value)
        {
            $type = self::T_NONE;

            switch (true) {
                // Recognize numeric values
                case (is_numeric($value)):
                   