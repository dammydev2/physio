<?php

$grammarFileToName = [
    __DIR__ . '/php5.y' => 'Php5',
    __DIR__ . '/php7.y' => 'Php7',
];

$tokensFile     = __DIR__ . '/tokens.y';
$tokensTemplate = __DIR__ . '/tokens.template';
$skeletonFile   = __DIR__ . '/parser.template';
$tmpGrammarFile = __DIR__ . '/tmp_parser.phpy';
$tmpResultFile  = __DIR__ . '/tmp_parser.php';
$resultDir = __DIR__ . '/../lib/PhpParser/Parser';
$tokensResultsFile = $resultDir . '/Tokens.php';

// check for kmyacc.exe binary in this directory, otherwise fall back to global name
$kmyacc = __DIR__ . '/kmyacc.exe';
if (!file_exists($kmyacc)) {
    $kmyacc = 'kmyacc';
}

$options = array_flip($argv);
$optionDebug = isset($options['--debug']);
$optionKeepTmpGrammar = isset($options['--keep-tmp-grammar']);

///////////////////////////////
/// Utility regex constants ///
///////////////////////////////

const LIB = '(?(DEFINE)
    (?<singleQuotedString>\'[^\\\\\']*+(?:\\\\.[^\\\\\']*+)*+\')
    (?<doubleQuotedString>"[^\\\\"]*+(?:\\\\.[^\\\\"]*+)*+")
    (?<string>(?&singleQuotedString)|(?&doubleQuotedString))
    (?<comment>/\*[^*]*+(?:\*(?!/)[^*]*+)*+\*/)
    (?<code>\{[^\'"/{}]*+(?:(?:(?&string)|(?&comment)|(?&code)|/)[^\'"/{}]*+)*+})
)';

const PARAMS = '\[(?<params>[^[\]]*+(?:\[(?&params)\][^[\]]*+)*+)\]';
const ARGS   = '\((?<args>[^()]*+(?:\((?&args)\)[^()]*+)*+)\)';

///////////////////
/// Main script ///
///////////////////

$tokens = file_get_contents($tokensFile);

foreach ($grammarFileToName as $grammarFile => $name) {
    echo "Building temporary $name grammar file.\n";

    $grammarCode = file_get_contents($grammarFile);
    $grammarCode = str_replace('%tokens', $tokens, $grammarCode);

    $grammarCode = resolveNodes($grammarCode);
    $grammarCode = resolveMacros($grammarCode);
    $grammarCode = resolveStackAccess($grammarCode);

    file_put_contents($tmpGrammarFile, $grammarCode);

    $additionalArgs = $optionDebug ? '-t -v' : '';

    echo "Building $name parser.\n";
    $output = trim(shell_exec("$kmyacc $additionalArgs -l -m $skeletonFile -p $name $tmpGrammarFile 2>&1"));
    echo "Output: \"$output\"\n";

    $resultCode = file_get_contents($tmpResultFile);
    $resultCode = removeTrailingWhitespace($resultCode);

    ensureDirExists($resultDir);
    file_put_contents("$resultDir/$name.php", $resultCode);
    unlink($tmpResultFile);

    echo "Building token definition.\n";
    $output = trim(shell_exec("$kmyacc -l -m $tokensTemplate $tmpGrammarFile 2>&1"));
    assert($output === '');
    rename($tmpResultFile, $tokensResultsFile);

    if (!$optionKeepTmpGrammar) {
        unlink($tmpGrammarFile);
    }
}

///////////////////////////////
/// Preprocessing functions ///
///////////////////////////////

function resolveNodes($code) {
    return preg_replace_callback(
        '~\b(?<name>[A-Z][a-zA-Z_\\\\]++)\s*' . PARAMS . '~',
        function($matches) {
            // recurse
            $matches['params'] = resolveNodes($matches['params']);

            $params = magicSplit(
                '(?:' . PARAMS . '|' . ARGS . ')(*SKIP)(*FAIL)|,',
                $matches['params']
            );

            $paramCode = '';
            foreach ($params as $param) {
                $paramCode .= $param . ', ';
            }

            return 'new ' . $matches['name'] . '(' . $paramCode . 'attributes())';
        },
        $code
    );
}

function resolveMacros($code) {
    return preg_replace_callback(
        '~\b(?<!::|->)(?!array\()(?<name>[a-z][A-Za-z]++)' . ARGS . '~',
        function($matches) {
            // recurse
            $matches['args'] = resolveMacros($matches['args']);

            $name = $matches['name'];
            $args = magicSplit(
                '(?:' . PARAMS . '|' . ARGS . ')(*SKIP)(*FAIL)|,',
                $matches['args']
            );

            if ('attributes' == $name) {
                assertArgs(0, $args, $name);
                return '$this->startAttributeStack[#1] + $this->endAttributes';
            }

            if ('stackAttributes' == $name) {
                assertArgs(1, $args, $name);
                return '$this->startAttributeStack[' . $args[0] . ']'
                     . ' + $this->endAttributeStack[' . $args[0] . ']';
            }

            if ('init' == $name) {
                return '$$ = array(' . implode(', ', $args) . ')';
            }

            if ('push' == $name) {
                assertArgs(2, $args, $name);

                return $args[0] . '[] = ' . $args[1] . '; $$ = ' . $args[0];
            }

            if ('pushNormalizing' == $name) {
                assertArgs(2, $args, $name);

                return 'if (is_array(' . $args[1] . ')) { $$ = array_merge(' . $args[0] . ', ' . $args[1] . '); }'
                     . ' else { ' . $