             { $$ = Expr\ArrayDimFetch[$1, $3]; }
    | object_access_for_dcnr '{' expr '}'                   { $$ = Expr\ArrayDimFetch[$1, $3]; }
;

exit_expr:
      /* empty */                                           { $$ = null; }
    | '(' ')'                                               { $$ = null; }
    | parentheses_expr                                      { $$ = $1; }
;

backticks_expr:
      /* empty */                                           { $$ = array(); }
    | T_ENCAPSED_AND_WHITESPACE
          { $$ = array(Scalar\EncapsedStringPart[Scalar\String_::parseEscapeSequences($1, '`', false)]); }
    | encaps_list                                           { parseEncapsed($1, '`', false); $$ = $1; }
;

ctor_arguments:
      /* empty */                                           { $$ = array(); }
    | argument_list                                         { $$ = $1; }
;

common_scalar:
      T_LNUMBER                                             { $$ = $this->parseLNumber($1, attributes(), true); }
    | T_DNUMBER                                             { $$ = Scalar\DNumber[Scalar\DNumber::parse($1)]; }
    | T_CONSTANT_ENCAPSED_STRING
          { $attrs = attributes(); $attrs['kind'] = strKind($1);
            $$ = new Scalar\String_(Scalar\String_::parse($1, false), $attrs); }
    | T_LINE                                                { $$ = Scalar\MagicConst\Line[]; }
    | T_FILE                                                { $$ = Scalar\MagicConst\File[]; }
    | T_DIR                                                 { $$ = Scalar\MagicConst\Dir[]; }
    | T_CLASS_C                                             { $$ = Scalar\MagicConst\Class_[]; }
    | T_TRAIT_C                                             { $$ = Scalar\MagicConst\Trait_[]; }
    | T_METHOD_C                                            { $$ = Scalar\MagicConst\Method[]; }
    | T_FUNC_C                                              { $$ = Scalar\MagicConst\Function_[]; }
    | T_NS_C                                                { $$ = Scalar\MagicConst\Namespace_[]; }
    | T_START_HEREDOC T_ENCAPSED_AND_WHITESPACE T_END_HEREDOC
          { $$ = $this->parseDocString($1, $2, $3, attributes(), stackAttributes(#3), false); }
    | T_START_HEREDOC T_END_HEREDOC
          { $$ = $this->parseDocString($1, '', $2, attributes(), stackAttributes(#2), false); }
;

static_scalar:
      common_scalar                                         { $$ = $1; }
    | class_name T_PAAMAYIM_NEKUDOTAYIM identifier_ex       { $$ = Expr\ClassConstFetch[$1, $3]; }
    | name                                                  { $$ = Expr\ConstFetch[$1]; }
    | T_ARRAY '(' static_array_pair_list ')'                { $$ = Expr\Array_[$3]; }
    | '[' static_array_pair_list ']'                        { $$ = Expr\Array_[$2]; }
    | static_operation                                      { $$ = $1; }
;

static_operation:
      static_scalar T_BOOLEAN_OR static_scalar              { $$ = Expr\BinaryOp\BooleanOr [$1, $3]; }
    | static_scalar T_BOOLEAN_AND static_scalar             { $$ = Expr\BinaryOp\BooleanAnd[$1, $3]; }
    | static_scalar T_LOGICAL_OR static_scalar              { $$ = Expr\BinaryOp\LogicalOr [$1, $3]; }
    | static_scalar T_LOGICAL_AND static_scalar             { $$ = Expr\BinaryOp\LogicalAnd[$1, $3]; }
    | static_scalar T_LOGICAL_XOR static_scalar             { $$ = Expr\BinaryOp\LogicalXor[$1, $3]; }
    | static_scalar '|' static_scalar                       { $$ = Expr\BinaryOp\BitwiseOr [$1, $3]; }
    | static_scalar '&' static_scalar                       { $$ = Expr\BinaryOp\BitwiseAnd[$1, $3]; }
    | static_scalar '^' static_scalar                       { $$ = Expr\BinaryOp\BitwiseXor[$1, $3]; }
    | static_scalar '.' static_scalar                       { $$ = Expr\BinaryOp\Concat    [$1, $3]; }
    | static_scalar '+' static_scalar                       { $$ = Expr\BinaryOp\Plus      [$1, $3]; }
    | static_scalar '-' static_scalar                       { $$ = Expr\BinaryOp\Minus     [$1, $3]; }
    | static_scalar '*' static_scalar                       { $$ = Expr\BinaryOp\Mul       [$1, $3]; }
    | static_scalar '/' static_scalar                       { $$ = Expr\BinaryOp\Div       [$1, $3]; }
    | static_scalar '%' static_scalar                       { $$ = Expr\BinaryOp\Mod       [$1, $3]; }
    | static_scalar T_SL static_scalar                      { $$ = Expr\BinaryOp\ShiftLeft [$1, $3]; }
    | static_scalar T_SR static_scalar                      { $$ = Expr\BinaryOp\ShiftRight[$1, $3]; }
    | static_scalar T_POW static_scalar                     { $$ = Expr\BinaryOp\Pow       [$1, $3]; }
    | '+' static_scalar %prec T_INC                         { $$ = Expr\UnaryPlus [$2]; }
    | '-' static_scalar %prec T_INC                         { $$ = Expr\UnaryMinus[$2]; }
    | '!' static_scalar                                     { $$ = Expr\BooleanNot[$2]; }
    | '~' static_scalar                                     { $$ = Expr\BitwiseNot[$2]; }
    | static_scalar T_IS_IDENTICAL static_scalar            { $$ = Expr\BinaryOp\Identical     [$1, $3]; }
    | static_scalar T_IS_NOT_IDENTICAL static_scalar        { $$ = Expr\BinaryOp\NotIdentical  [$1, $3]; }
    | static_scalar T_IS_EQUAL static_scalar                { $$ = Expr\BinaryOp\Equal         [$1, $3]; }
    | static_scalar T_IS_NOT_EQUAL static_scalar            { $$ = Expr\BinaryOp\NotEqual      [$1, $3]; }
    | static_scalar '<' static_scalar                       { $$ = Expr\BinaryOp\Smaller       [$1, $3]; }
    | static_scalar T_IS_SMALLER_OR_EQUAL static_scalar     { $$ = Expr\BinaryOp\SmallerOrEqual[$1, $3]; }
    | static_scalar '>' static_scalar                       { $$ = Expr\BinaryOp\Greater       [$1, $3]; }
    | static_scalar T_IS_GREATER_OR_EQUAL static_scalar     { $$ = Expr\BinaryOp\GreaterOrEqual[$1, $3]; }
    | static_scalar '?' static_scalar ':' static_scalar     { $$ = Expr\Ternary[$1, $3,   $5]; }
    | static_scalar '?' ':' static_scalar                   { $$ = Expr\Ternary[$1, null, $4]; }
    | static_scalar '[' static_scalar ']'                   { $$ = Expr\ArrayDimFetch[$1, $3]; }
    | '(' static_scalar ')'                                 { $$ = $2; }
;

constant:
      name                                                  { $$ = Expr\ConstFetch[$1]; }
    | class_name_or_var T_PAAMAYIM_NEKUDOTAYIM identifier_ex
          { $$ = Expr\ClassConstFetch[$1, $3]; }
;

scalar:
      common_scalar                                         { $$ = $1; }
    | constant                                              { $$ = $1; }
    | '"' encaps_list '"'
          { $attrs = attributes(); $attrs['kind'] = Scalar\String_::KIND_DOUBLE_QUOTED;
            parseEncapsed($2, '"', true); $$ = new Scalar\Encapsed($2, $attrs); }
    | T_START_HEREDOC encaps_list T_END_HEREDOC
          { $$ = $this->parseDocString($1, $2, $3, attributes(), stackAttributes(#3), true); }
;

static_array_pair_list:
      /* empty */                                           { $$ = array(); }
    | non_empty_static_array_pair_list optional_comma       { $$ = $1; }
;

optional_comma:
      /* empty */
    | ','
;

non_empty_static_array_pair_list:
      non_empty_static_array_pair_list ',' static_array_pair { push($1, $3); }
    | static_array_pair                                      { init($1); }
;

static_array_pair:
      static_scalar T_DOUBLE_ARROW static_scalar            { $$ = Expr\ArrayItem[$3, $1,   false]; }
    | static_scalar                                         { $$ = Expr\ArrayItem[$1, null, false]; }
;

variable:
      object_access                                         { $$ = $1; }
    | base_variable                                         { $$ = $1; }
    | function_call                                         { $$ = $1; }
    | new_expr_array_deref                                  { $$ = $1; }
;

new_expr_array_deref:
      '(' new_expr ')' '[' dim_offset ']'                   { $$ = Expr\ArrayDimFetch[$2, $5]; }
    | new_expr_array_deref '[' dim_offset ']'               { $$ = Expr\ArrayDimFetch[$1, $3]; }
      /* alternative array syntax missing intentionally */
;

object_access:
      variable_or_new_expr T_OBJECT_OPERATOR object_property
          { $$ = Expr\PropertyFetch[$1, $3]; }
    | variable_or_new_expr T_OBJECT_OPERATOR object_property argument_list
          { $$ = Expr\MethodCall[$1, $3, $4]; }
    | object_access argument_list                           { $$ = Expr\FuncCall[$1, $2]; }
    | object_access '[' dim_offset ']'                      { $$ = Expr\ArrayDimFetch[$1, $3]; }
    | object_access '{' expr '}'                            { $$ = Expr\ArrayDimFetch[$1, $3]; }
;

variable_or_new_expr:
      variable                                              { $$ = $1; }
    | '(' new_expr ')'                                      { $$ = $2; }
;

variable_without_objects:
      reference_variable                                    { $$ = $1; }
    | '$' variable_without_objects                          { $$ = Expr\Variable[$2]; }
;

base_variable:
      variable_without_objects                              { $$ = $1; }
    | static_property                                       { $$ = $1; }
;

static_property:
      class_name_or_var T_PAAMAYIM_NEKUDOTAYIM '$' reference_variable
          { $$ = Expr\StaticPropertyFetch[$1, $4]; }
    | static_property_with_arrays                           { $$ = $1; }
;

static_property_simple_name:
      T_VARIABLE
          { $var = parseVar($1); $$ = \is_string($var) ? Node\VarLikeIdentifier[$var] : $var; }
;

static_property_with_arrays:
      class_name_or_var T_PAAMAYIM_NEKUDOTAYIM static_property_simple_name
          { $$ = Expr\StaticPropertyFetch[$1, $3]; }
    | class_name_or_var T_PAAMAYIM_NEKUDOTAYIM '$' '{' expr '}'
          { $$ = Expr\StaticPropertyFetch[$1, $5]; }
    | static_property_with_arrays '[' dim_offset ']'        { $$ = Expr\ArrayDimFetch[$1, $3]; }
    | static_property_with_arrays '{' expr '}'              { $$ = Expr\ArrayDimFetch[$1, $3]; }
;

reference_variable:
      reference_variable '[' dim_offset ']'                 { $$ = Expr\ArrayDimFetch[$1, $3]; }
    | reference_variable '{' expr '}'                       { $$ = Expr\ArrayDimFetch[$1, $3]; }
    | plain_variable                                        { $$ = $1; }
    | '$' '{' expr '}'                                      { $$ = Expr\Variable[$3]; }
;

dim_offset:
      /* empty */                                           { $$ = null; }
    | expr                                                  { $$ = $1; }
;

object_property:
      identifier                                            { $$ = 