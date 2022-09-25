<?php

use PhpCsFixer\Finder;
use PhpCsFixer\Config;

$directoriesToExclude = [
    'bootstrap',
    'storage',
    'node_modules',
];

$filesToExclude = [
    '*.blade.php',
    'index.php',
    'server.php',
];

$fixerRules = [
    '@PSR2' => true,

    'align_multiline_comment' => true,
    'array_indentation' => true,
    'array_syntax' => ['syntax' => 'short'],
    'binary_operator_spaces' => true,
    'blank_line_after_opening_tag' => true,
    'blank_line_before_statement' => true,
    'cast_spaces' => true,
    'class_attributes_separation' => ['elements' => ['method' => 'one']],
    'compact_nullable_typehint' => true,
    'concat_space' => true,
    'declare_equal_normalize' => true,
    'fully_qualified_strict_types' => true,
    'function_typehint_space' => true,
    'heredoc_to_nowdoc' => true,
    'include' => true,
    'linebreak_after_opening_tag' => true,
    'list_syntax' => ['syntax' => 'short'],
    'lowercase_cast' => true,
    'lowercase_static_reference' => true,
    'magic_constant_casing' => true,
    'magic_method_casing' => true,
    'method_argument_space' => ['on_multiline' => 'ensure_fully_multiline'],
    'method_chaining_indentation' => true,
    'modernize_types_casting' => true,
    'multiline_comment_opening_closing' => true,
    'multiline_whitespace_before_semicolons' => true,
    'native_function_casing' => true,
    'native_function_type_declaration_casing' => true,
    'new_with_braces' => true,
    'no_alias_functions' => true,
    'no_alternative_syntax' => true,
    'no_blank_lines_after_class_opening' => true,
    'no_blank_lines_after_phpdoc' => true,
    'no_empty_phpdoc' => true,
    'no_empty_statement' => true,
    'no_extra_blank_lines' => true,
    'no_leading_import_slash' => true,
    'no_leading_namespace_whitespace' => true,
    'no_mixed_echo_print' => true,
    'no_multiline_whitespace_around_double_arrow' => true,
    'no_null_property_initialization' => true,
    'no_short_bool_cast' => true,
    'no_spaces_around_offset' => true,
    'no_singleline_whitespace_before_semicolons' => true,
    'no_superfluous_elseif' => true,
    'no_trailing_comma_in_list_call' => true,
    'no_trailing_comma_in_singleline_array' => true,
    'no_unneeded_control_parentheses' => true,
    'no_unneeded_curly_braces' => true,
    'no_unreachable_default_argument_value' => true,
    'no_unused_imports' => true,
    'no_useless_else' => true,
    'no_useless_return' => true,
    'no_whitespace_before_comma_in_array' => true,
    'no_whitespace_in_blank_line' => true,
    'normalize_index_brace' => true,
    'not_operator_with_successor_space' => true,
    'object_operator_without_whitespace' => true,
    'ordered_imports' => ['sort_algorithm' => 'alpha'],
    'php_unit_method_casing' => true,
    'phpdoc_align' => ['align' => 'left', 'tags' => ['param', 'property', 'return', 'throws', 'type', 'var', 'method']],
    'phpdoc_indent' => true,
    'phpdoc_inline_tag_normalizer' => true,
    'phpdoc_no_access' => true,
    'phpdoc_no_package' => true,
    'phpdoc_no_useless_inheritdoc' => true,
    'phpdoc_scalar' => true,
    'phpdoc_separation' => true,
    'phpdoc_single_line_var_spacing' => true,
    'phpdoc_summary' => true,
    'phpdoc_to_comment' => true,
    'phpdoc_trim' => true,
    'phpdoc_types' => true,
    'phpdoc_types_order' => ['null_adjustment' => 'always_last'],
    'phpdoc_var_without_name' => true,
    'psr_autoloading' => true,
    'return_type_declaration' => true,
    'self_accessor' => true,
    'self_static_accessor' => true,
    'short_scalar_cast' => true,
    'simplified_null_return' => true,
    'single_blank_line_before_namespace' => true,
    'single_line_comment_style' => true,
    'single_quote' => true,
    'space_after_semicolon' => true,
    'standardize_not_equals' => true,
    'switch_case_semicolon_to_colon' => true,
    'ternary_operator_spaces' => true,
    'ternary_to_null_coalescing' => true,
    'trailing_comma_in_multiline' => ['elements' => ['arrays']],
    'trim_array_spaces' => true,
    'unary_operator_spaces' => true,
    'whitespace_after_comma_in_array' => true,
];

$finder = Finder::create()
    ->exclude($directoriesToExclude)
    ->notName($filesToExclude)
    ->in(__DIR__);

return (new Config())
    ->setRules($fixerRules)
    ->setRiskyAllowed(true)
    ->setFinder($finder);
