Introduction
============

Doctrine Lexer is a library that can be used in Top-Down, Recursive
Descent Parsers. This lexer is used in Doctrine Annotations and in
Doctrine ORM (DQL).

To write your own parser you just need to extend ``Doctrine\Common\Lexer\AbstractLexer``
and implement the following three abstract methods.

.. code-block:: php

    /**
     * Lexical catchable patterns.
     *
     * @return array
     */
    abstract protected function getCatchablePatterns();

    /**
     * Lexical non-catchable patterns.
     *
     * @return array
     */
    abstract protected function getNonCatchablePatterns();

    /**
     * Retrieve token type. Also processes the token value if necessary.
     *
     * @param string $value
     * @return integer
     */
    abstract protected function getType(&$value);

These methods define the `lexical <http://en.wikipedia.org/wiki/Lexical_analysis>`_
catchable and non-catchable patterns and a method for returning the
type of a token and filtering the value if necessary.

The Lexer is responsible for giving you an API to walk across a
string one character at a time and analyze the type of each character, value and position of
each token in the string. The low level API of the lexer is pretty simple:

- ``setInput($input)`` - Set