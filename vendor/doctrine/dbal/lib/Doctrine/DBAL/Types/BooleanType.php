Simple Parser Example
=====================

Extend the ``Doctrine\Common\Lexer\AbstractLexer`` class and implement
the ``getCatchablePatterns``, ``getNonCatchablePatterns``, and ``getType``
methods. Here is a very simple example lexer implementation named ``CharacterTypeLexer``.
It tokenizes a string to ``T_UPPER``, ``T_LOWER`` and``T_NUMBER`` tokens:

.. code-block:: php
    <?php

    use Doctrine\Common\Lexer\AbstractLexer;

    class CharacterTypeLexer extends AbstractLexer
    {
        const T_UPPER =  1;
        const T_LOWER =  2;
        const T_NUMBER = 3;

        protected function getCatchablePatterns()
        {
            return array(
                '[a-bA-Z0-9]',
            );
        }

        protected function getNonCatchablePatterns()
        {
            return array();
        }

        protected function getType(&$value)
        {
            if (is_numeric($value)) {
                return self::T_NUMBER;
            }

            if (strtoupper($value) === $value) {
                return