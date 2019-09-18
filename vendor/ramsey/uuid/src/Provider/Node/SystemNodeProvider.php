<?php declare(strict_types=1);
$header = <<<'EOF'
This file is part of sebastian/diff.

(c) Sebastian Bergmann <sebastian@phpunit.de>

For the full copyright and license information, please view the LICENSE
file that was distributed with this source code.
EOF;

return PhpCsFixer\Config::create()
    ->setRiskyAllowed(true)
    ->setRules(
        [
            'array_syntax' => ['syntax' => 'short'],
            'binary_operator_spaces' => [
                'operators' => [
                    '=' => 'align',
                    '=>' => 'align',
                ],
            ],
            'blank_line_after_namespace' => true,
            'blank_line_before_statement' => [
                'statements' => [
                    'break',
                    'continue',
                    'declare',
                    'do',
                    'for',
                    'foreach',
                    'if',
                    'include',
                    'include_once',
                    'require',
                    'require_once',
                    'return',
                    'switch',
                    'throw',
                    'try',
                    'while',
                    'yield',
                ],
            ],
            'braces' => true,
            'cast_spaces' => true,
            'class_attributes_separation' => ['elements' => ['method']],
            'compact_nullable_typehint' => true,
            'concat_space' => ['spacing' => 'one'],
            'declare_equal_normalize' => ['space' => 'none'],
            'declare_strict_types' => true,
            'dir_constant' => true,
            'elseif' => true,
            'encoding' => true,
            'full_opening_tag' => true,
            'function_declaration' => true,
            'header_comment' => ['header' => $header, 'separate' => 'none'],
            'indentation_type' => true,
            'line_ending' => true,
            'list_syntax' => ['syntax' => 'short'],
            'lowercase_cast' => true,
            'lowercase_constants' => true,
            'lowercase_keywords' => true,
            'magic_constant_casing' => true,
            'method_argument_space' => ['ensure_fully_multiline' => true],
            'modernize_types_casting' => true,
            'native_function_casing' => true,
            'native_function_invocation' => true,
            'no_alias_functions' => true,
            'no_blank_lines_after_class_opening' => true,
            'no_blank_lines_after_phpdoc' => true,
            'no_closing_tag' => true,
            'no_empty_comment' => true,
            'no_empty_phpdoc' => true,
            'no_empty_statement' => true,
            'no_extra_blank_lines' => true,
            'no_homoglyph_names' => true,
            'no_leading_import_slash' => true,
            'no_leading_namespace_whitespace' => true,
            'no_mixed_echo_print' => ['use' => 'print'],
            'no_null_property_initialization' => true,
            'no_short_bool_cast' => true,
            'no_short_echo_tag' => true,
            'no_singleline_whitespace_before_semicolons' => true,
            'no_spaces_after_function_name' => true,
            'no_spaces_inside_parenthesis' => true,
            'no_superfluous_elseif' => true,
            'no_trailing_comma_in_list_call' => true,
            'no_trailing_comma_in_singleline_array' => true,
            'no_trailing_whitespace' => true,
            'no_trailing_whitespace_in_comment' => true,
            'n