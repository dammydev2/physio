<?php
$header = <<<'EOF'
This file is part of the php-code-coverage package.

(c) Sebastian Bergmann <sebastian@phpunit.de>

For the full copyright and license information, please view the LICENSE
file that was distributed with this source code.
EOF;

return PhpCsFixer\Config::create()
    ->setRiskyAllowed(true)
    ->setRules(
        [
            'align_multiline_comment' => true,
            'array_indentation' => true,
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
            'class_attributes_separation' => ['elements' => ['const', 'method', 'property']],
            'combine_consecutive_issets' => true,
            'combine_consecutive_unsets' => true,
            'compact_nullable_typehint' => true,
            'concat_space' => ['spacing' => 'one'],
            'declare_equal_normalize' => ['space' => 'none'],
            'dir_constant' => true,
            'elseif' => true,
            'encoding' => true,
            'full_opening_tag' => true,
            'function_declaration' => true,
            'header_comment' => ['header' => $header, 'separate' => 'none'],
            'indentation_type' => true,
            'is_null' => true,
            'line_ending' => true,
            'list_syntax' => ['syntax' => 'short'],
            'logical_operators' => true,
            'lowercase_cast' => true,
            'lowercase_constants' => true,
            'lowercase_keywords' => true,
            'lowercase_static_reference' => true,
            'magic_constant_casing' => true,
            'method_argument_space' => ['ensure_fully_multiline' => true],
            'modernize_types_casting' => true,
            'multiline_comment_opening_closing' => true,
            'multiline_whitespace_before_semicolons' => true,
            'native_constant_invocation' => true,
            'native_function_casing' => true,
            'native_function_invocation' => true,
            'new_with_braces' => false,
            'no_alias_functions' => true,
            'no_alternative_syntax' => true,
            'no_blank_lines_after_class_opening' => true,
            'no_blank_lines_after_phpdoc' => true,
            'no_blank_lines_before_namespace' =>