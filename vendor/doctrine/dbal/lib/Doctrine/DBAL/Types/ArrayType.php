DQL Lexer
=========

Here is a more complicated example from the Doctrine ORM project.
The ``Doctrine\ORM\Query\Lexer`` implementation for DQL looks something
like the following:

.. code-block:: php

    use Doctrine\Common\Lexer\AbstractLexer;

    class Lexer extends AbstractLexer
    {
        // All tokens that are not valid identifiers must be < 100
        public const T_NONE              = 1;
        public const T_INTEGER           = 2;
        public const T_STRING            = 3;
        public const T_INPUT_PARAMETER   = 4;
        public const T_FLOAT             = 5;
        public const T_CLOSE_PARENTHESIS = 6;
        public const T_OPEN_PARENTHESIS  = 7;
        public const T_COMMA             = 8;
        public const T_DIVIDE            = 9;
        public const T_DOT               = 10;
        public const T_EQUALS            = 11;
        public const T_GREATER_THAN      = 12;
        public const T_LOWER_THAN        = 13;
        public const T_MINUS             = 14;
        public const T_MULTIPLY          = 15;
        public const T_NEGATE            = 16;
        public const T_PLUS              = 17;
        public const T_OPEN_CURLY_BRACE  = 18;
        public const T_CLOSE_CURLY_BRACE = 19;

        // All tokens that are identifiers or keywords that could be considered as identifiers should be >= 100
        public const T_ALIASED_NAME         = 100;
        public const T_FULLY_QUALIFIED_NAME = 101;
        public const T_IDENTIFIER           = 102;

        // All keyword tokens should be >= 200
        public const T_ALL      = 200;
        public const T_AND      = 201