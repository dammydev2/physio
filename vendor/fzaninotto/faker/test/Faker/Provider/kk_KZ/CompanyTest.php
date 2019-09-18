oIgnoringCase')) {    /**
     * Matches if value is a string equal to $string, regardless of the case.
     */
    function equalToIgnoringCase($string)
    {
        return \Hamcrest\Text\IsEqualIgnoringCase::equalToIgnoringCase($string);
    }
}

if (!function_exists('equalToIgnoringWhiteSpace')) {    /**
     * Matches if value is a string equal to $string, regardless of whitespace.
     */
    function equalToIgnoringWhiteSpace($string)
    {
        return \Hamcrest\Text\IsEqualIgnoringWhiteSpace::equalToIgnoringWhiteSpace($string);
    }
}

if (!function_exists('matchesPattern')) {    /**
     * Matches if value is a string that matches regular expression $pattern.
     */
    function matchesPattern($pattern)
    {
        return \Hamcrest\Text\MatchesPattern::matchesPattern($pattern);
    }
}

if (!function_exi