<?php declare(strict_types=1);

namespace PhpParser;

use PhpParser\Parser\Tokens;

class Lexer
{
    protected $code;
    protected $tokens;
    protected $pos;
    protected $line;
    protected $filePos;
    protected $prevCloseTagHasNewline;

    protected $tokenMap;
    protected $dropTokens;

    protected $usedAttributes;

    /**
     * Creates a Lexer.
     *
     * @param array $options Options array. Currently only the 'usedAttributes' option is supported,
     *                       which is an array of attributes to add to the AST nodes. Possible
     *                       attributes are: 'comments', 'startLine', 'endLine', 'startTokenPos',
     *                       'endTokenPos', 'startFilePos', 'endFilePos'. The option defaults to the
     *                       first three. For more info see getNextToken() docs.
     */
    public function __construct(array $options = []) {
        // map from internal tokens to PhpParser tokens
        $this->tokenMap = $this->createTokenMap();

        // map of tokens to drop while lexing (the map is only used for isset lookup,
        // that's why the value is simply set to 1; the value is never actually used.)
        $this->dropTokens = array_fill_keys(
            [\T_WHITESPACE, \T_OPEN_TAG, \T_COMMENT, \T_DOC_COMMENT], 1
        );

        // the usedAttributes member is a map of the used attribute names to a dummy
        // value (here "true")
        $options += [
            'usedAttributes' => ['comments', 'startLine', 'endLine'],
        ];
        $this->usedAttributes = array_fill_keys($options['usedAttributes'], true);
    }

    /**
     * Initializes the lexer for lexing the provided source code.
     *
     * This function does not throw if lexing errors occur. Instead, errors may be retrieved using
     * the getErrors() method.
     *
     * @param string $code The source code to lex
     * @param ErrorHandler|null $errorHandler Error handler to use for lexing errors. Defaults to
     *                                        ErrorHandler\Throwing
     */
    public function startLexing(string $