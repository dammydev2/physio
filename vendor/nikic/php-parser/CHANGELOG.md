   { $$ = Stmt\Use_::TYPE_CONSTANT; }
;

/* Using namespace_name_parts here to avoid s/r conflict on T_NS_SEPARATOR */
group_use_declaration:
      T_USE use_type namespace_name_parts T_NS_SEPARATOR '{' unprefixed_use_declarations '}'
          { $$ = Stmt\GroupUse[new Name($3, stackAttributes(#3)), $6, $2]; }
    | T_USE use_type T_NS_SEPARATOR namespace_name_parts T_NS_SEPARATOR '{' unprefixed_use_declarations '}'
          { $$ = Stmt\GroupUse[new Name($4, stackAttributes(#4)), $7, $2]; }
    | T_USE namespace_name_parts T_NS_SEPARATOR '{' inline_use_declarations '}'
          { $$ = Stmt\GroupUse[new Name($2, stackAttributes(#2)), $5, Stmt\Use_::TYPE_UNKNOWN]; }
    | T_USE T_NS_SEPARATOR namespace_name_parts T_NS_SEPARATOR '{' inline_use_declarations '}'
          { $$ = Stmt\GroupUse[new Name($3, stackAttributes(#3)), $6, Stmt\Use_::TYPE_UNKNOWN]; }
;

unprefixed_use_declarations:
      non_empty_unprefixed_use_declarations optional_comma  { $$ = $1; }
;

non_empty_unprefixed_use_declarations:
      non_empty_unprefixed_use_declarations ',' unprefixed_use_declaration
          { push($1, $3); }
    | unprefixed_use_declaration                            { init($1); }
;

use_declarations:
      non_empty_use_declarations no_comma                   { $$ = $1; }
;

non_empty_use_declarations:
      non_empty_use_declarations ',' use_declaration        { push($1, $3); }
    | use_declaration                                       { init($1); }
;

inline_use_declarations:
      non_empty_inline_use_declarations optional_comma      { $$ = $1; }
;

non_empty_inline_use_declarations:
      non_empty_inline_use_declarations ',' inline_use_declaration
          { push($1, $3); }
    | inline_use_declaration                                { init($1); }
;

unprefixed_use_declaration:
      namespace_name
          { $$ = Stmt\UseUse[$1, null, Stmt\Use_::TYPE_UNKNOWN]; $this->checkUseUse($$, #1); }
    | namespace_name T_AS identifier
          { $$ = Stmt\UseUse[$1, $3, Stmt\Use_::TYPE_UNKNOWN]; $this->checkUseUse($$, #3); }
;

use_declaration:
      unprefixed_use_declaration                            { $$ = $1; }
    | T_NS_SEPARATOR unprefixed_use_declaration             { $$ = $2; }
;

inline_use_declaration:
      unprefixed_use_declaration                            { $$ = $1; $$->type = Stmt\Use_::TYPE_NORMAL; }
    | use_type unprefixed_use_declaration                   { $$ = $2; $$->type = $1; }
;

constant_declaration_list:
      non_empty_constant_declaration_list no_comma          { $$ = $1; }
;

non_empty_constant_declaration_list:
      non_empty_constant_declaration_list ',' constant_declaration
          { push($1, $3); }
    | constant_declaration                                  { init($1); }
;

constant_declaration:
    identifier '=' expr                                     { $$ = Node\Const_[$1, $3]; }
;

class_const_list:
      non_empty_class_const_list no_comma                   { $$ = $1; }
;

non_empty_class_const_list:
      non_empty_class_const_list ',' class_const            { push($1, $3); }
    | class_const                                           { init($1); }
;

class_const:
    identifier_ex '=' expr                                  { $$ = Node\Const_[$1, $3]; }
;

inner_statement_list_ex:
      inner_statement_list_ex inner_statement               { pushNormalizing($1, $2); }
    | /* empty */                                           { init(); }
;

inner_statement_list:
      inner_statement_list_ex
          { makeNop($nop, $this->lookaheadStartAttributes, $this->endAttributes);
            if ($nop !== null) { $1[] = $nop; } $$ = $1; }
;

inner_statement:
      statement                                             { $$ = $1; }
    | function_declaration_statement                        { $$ = $1; }
    | class_declaration_statement                           { $$ = $1; }
    | T_HALT_COMPILER
          { throw new Error('__HALT_COMPILER() can only be used from the outermost scope', attributes()); }
;

non_empty_statement:
      '{' inner_statement_list '}'
    {
        if ($2) {
            $$ = $2; prependLeadingComments($$);
        } else {
            makeNop($$, $this->startAttributeStack[#1], $this->endAttributes);
            if (null === $$) { $$ = array(); }
        }
    }
    | T_IF '(' expr ')' statement elseif_list else_single
          { $$ = Stmt\If_[$3, ['stmts' => toArray($5), 'elseifs' => $6, 'else' => $7]]; }
    | T_IF '(' expr ')' ':' inner_statement_list new_elseif_list new_else_single T_ENDIF ';'
          { $$ = Stmt\If_[$3, ['stmts' => $6, 'elseifs' => $7, 'else' => $8]]; }
    | T_WHILE '(' expr ')' while_statement                  { $$ = Stmt\While_[$3, $5]; }
    | T_DO statement T_WHILE '(' expr ')' ';'               { $$ = Stmt\Do_   [$5, toArray($2)]; }
    | T_FOR '(' for_expr ';'  for_expr ';' for_expr ')' for_statement
          { $$ = Stmt\For_[['init' => $3, 'cond' => $5, 'loop' => $7, 'stmts' => $9]]; }
    | T_SWITCH '(' expr ')' switch_case_list                { $$ = Stmt\Switch_[$3, $5]; }
    | T_BREAK optional_expr semi                            { $$ = Stmt\Break_[$2]; }
    | T_CONTINUE optional_expr semi                         { $$ = Stmt\Continue_[$2]; }
    | T_RETURN optional_expr semi                           { $$ = Stmt\Return_[$2]; }
    | T_GLOBAL global_var_list semi                         { $$ = Stmt\Global_[$2]; }
    | T_STATIC static_var_list semi                         { $$ = Stmt\Static_[$2]; }
    | T_ECHO expr_list semi                                 { $$ = Stmt\Echo_[$2]; }
    | T_INLINE_HTML                                         { $$ = Stmt\InlineHTML[$1]; }
    | expr semi                                             { $$ = Stmt\Expression[$1]; }
    | T_UNSET '(' variables_list ')' semi                   { $$ = Stmt\Unset_[$3]; }
    | T_FOREACH '(' expr T_AS foreach_variable ')' foreach_statement
          { $$ = Stmt\Foreach_[$3, $5[0], ['keyVar' => null, 'byRef' => $5[1], 'stmts' => $7]]; }
    | T_FOREACH '(' expr T_AS variable T_DOUBLE_ARROW foreach_variable ')' foreach_statement
          { $$ = Stmt\Foreach_[$3, $7[0], ['keyVar' => $5, 'byRef' => $7[1], 'stmts' => $9]]; }
    | T_FOREACH '(' expr error ')' foreach_statement
          { $$ = Stmt\Foreach_[$3, new Expr\Error(stackAttributes(#4)), ['stmts' => $6]]; }
    | T_DECLARE '(' declare_list ')' declare_statement      { $$ = Stmt\Declare_[$3, $5]; }
    | T_TRY '{' inner_statement_list '}' catches optional_finally
          { $$ = Stmt\TryCatch[$3, $5, $6]; $this->checkTryCatch($$); }
    | T_THROW expr semi                                     { $$ = Stmt\Throw_[$2]; }
    | T_GOTO identifier semi                                { $$ = Stmt\Goto_[$2]; }
    | identifier ':'                                        { $$ = Stmt\Label[$1]; }
    | error                                                 { $$ = array(); /* means: no statement */ }
;

statement:
      non_empty_statement                                   { $$ = $1; }
    | ';'
          { makeNop($$, $this->startAttributeStack[#1], $this->endAttributes);
            if ($$ === null) $$ = array(); /* means: no statement */ }
;

catches:
      /* empty */                                           { init(); }
    | catches catch                                         { push($1, $2); }
;

name_union:
      name                                                  { init($1); }
    | name_union '|' name                                   { push($1, $3); }
;

catch:
    T_CATCH '(' name_union plain_variable ')' '{' inner_statement_list '}'
        { $$ = Stmt\Catch_[$3, $4, $7]; }
;

optional_finally:
      /* empty */                                           { $$ = null; }
    | T_FINALLY '{' inner_statement_list '}'                { $$ = Stmt\Finally_[$3]; }
;

variables_list:
      non_empty_variables_list optional_comma               { $$ = $1; }
;

non_empty_variables_list:
      variable                                              { init($1); }
    | non_empty_variables_list ',' variable                 { push($1, $3); }
;

optional_ref:
      /* empty */                                           { $$ = false; }
    | '&'                                                   { $$ = true; }
;

optional_ellipsis:
      /* empty */                                           { $$ = false; }
    | T_ELLIPSIS                                            { $$ = true; }
;

block_or_error:
      '{' inner_statement_list '}'                          { $$ = $2; }
    | error                                                 { $$ = []; }
;

function_declaration_statement:
    T_FUNCTION optional_ref identifier '(' parameter_list ')' optional_return_type block_or_error
        { $$ = Stmt\Function_[$3, ['byRef' => $2, 'params' => $5, 'returnType' => $7, 'stmts' => $8]]; }
;

class_declaration_statement:
      class_entry_type identifier extends_from implements_list '{' class_statement_list '}'
          { $$ = Stmt\Class_[$2, ['type' => $1, 'extends' => $3, 'implements' => $4, 'stmts' => $6]];
            $this->checkClass($$, #2); }
    | T_INTERFACE identifier interface_extends_list '{' class_statement_list '}'
          { $$ = Stmt\Interface_[$2, ['extends' => $3, 'stmts' => $5]];
            $this->checkInterface($$, #2); }
    | T_TRAIT identifier '{' class_statement_list '}'
          { $$ = Stmt\Trait_[$2, ['stmts' => $4]]; }
;

class_entry_type:
      T_CLASS                                               { $$ = 0; }
    | T_ABSTRACT T_CLASS                                    { $$ = Stmt\Class_::MODIFIER_ABSTRACT; }
    | T_FINAL T_CLASS                                       { $$ = Stmt\Class_::MODIFIER_FINAL; }
;

extends_from:
      /* empty */                                           { $$ = null; }
    | T_EXTENDS class_name                                  { $$ = $2; }
;

interface_extends_list:
      /* empty */                                           { $$ = array(); }
    | T_EXTENDS class_name_list                             { $$ = $2; }
;

implements_list:
      /* empty */                                           { $$ = array(); }
    | T_IMPLEMENTS class_name_list                          { $$ = $2; }
;

class_name_list:
      non_empty_class_name_list no_comma                    { $$ = $1; }
;

non_empty_class_name_list:
      class_name                                            { init($1); }
    | non_empty_class_name_list ',' class_name              { push($1, $3); }
;

for_statement:
      statement                                             { $$ = toArray($1); }
    | ':' inner_statement_list T_ENDFOR ';'                 { $$ = $2; }
;

foreach_statement:
      statement                                             { $$ = toArray($1); }
    | ':' inner_statement_list T_ENDFOREACH ';'             { $$ = $2; }
;

declare_statement:
      non_empty_statement                                   { $$ = toArray($1); }
    | ';'                                                   { $$ = null; }
    | ':' inner_statement_list T_ENDDECLARE ';'             { $$ = $2; }
;

declare_list:
      non_empty_declare_list no_comma                       { $$ = $1; }
;

non_empty_declare_list:
      declare_list_element                                  { init($1); }
    | non_empty_declare_list ',' declare_list_element       { push($1, $3); }
;

declare_list_element:
      identifier '=' expr                                   { $$ = Stmt\DeclareDeclare[$1, $3]; }
;

switch_case_list:
      '{' case_list '}'                                     { $$ = $2; }
    | '{' ';' case_list '}'                                 { $$ = $3; }
    | ':' case_list T_ENDSWITCH ';'                         { $$ = $2; }
    | ':' ';' case_list T_ENDSWITCH ';'                     { $$ = $3; }
;

case_list:
      /* empty */                                           { init(); }
    | case_list case                                        { push($1, $2); }
;

case:
      T_CASE expr case_separator inner_statement_list_ex    { $$ = Stmt\Case_[$2, $4]; }
    | T_DEFAULT case_separator inner_statement_list_ex      { $$ = Stmt\Case_[null, $3]; }
;

case_separator:
      ':'
    | ';'
;

while_statement:
      statement                                             { $$ = toArray($1); }
    | ':' inner_statement_list T_ENDWHILE ';'               { $$ = $2; }
;

elseif_list:
      /* empty */                                           { init(); }
    | elseif_list elseif                                    { push($1, $2); }
;

elseif:
      T_ELSEIF '(' expr ')' statement                       { $$ = Stmt\ElseIf_[$3, toArray($5)]; }
;

new_elseif_list:
      /* empty */                                           { init(); }
    | new_elseif_list new_elseif                            { push($1, $2); }
;

new_elseif:
     T_ELSEIF '(' expr ')' ':' inner_statement_list         { $$ = Stmt\ElseIf_[$3, $6]; }
;

else_single:
      /* empty */                                           { $$ = null; }
    | T_ELSE statement                                      { $$ = Stmt\Else_[toArray($2)]; }
;

new_else_single:
      /* empty */                                           { $$ = null; }
    | T_ELSE ':' inner_statement_list                       { $$ = Stmt\Else_[$3]; }
;

foreach_variable:
      variable                                              { $$ = array($1, false); }
    | '&' variable                                          { $$ = array($2, true); }
    | list_expr                                             { $$ = array($1, false); }
    | array_short_syntax                                    { $$ = array($1, false); }
;

parameter_list:
      non_empty_parameter_list no_comma                     { $$ = $1; }
    | /* empty */                                           { $$ = array(); }
;

non_empty_parameter_list:
      parameter                                             { init($1); }
    | non_empty_parameter_list ',' parameter                { push($1, $3); }
;

parameter:
      optional_type optional_ref optional_ellipsis plain_variable
          { $$ = Node\Param[$4, null, $1, $2, $3]; $this->checkParam($$); }
    | optional_type optional_ref optional_ellipsis plain_variable '=' expr
          { $$ = Node\Param[$4, $6, $1, $2, $3]; $this->checkParam($$); }
    | optional_type optional_ref optional_ellipsis error
          { $$ = Node\Param[Expr\Error[], null, $1, $2, $3]; }
;

type_expr:
      type                                                  { $$ = $1; }
    | '?' type                                              { $$ = Node\NullableType[$2]; }
;

type:
      name                                                  { $$ = $this->handleBuiltinTypes($1); }
    | T_ARRAY                                               { $$ = Node\Identifier['array']; }
    | T_CALLABLE                                            { $$ = Node\Identifier['callable']; }
;

optional_type:
      /* empty */                                           { $$ = null; }
    | type_expr                                             { $$ = $1; }
;

optional_return_type:
      /* empty */                                           { $$ = null; }
    | ':' type_expr                                         { $$ = $2; }
    | ':' error                                             { $$ = null; }
;

argument_list:
      '(' ')'                                               { $$ = array(); }
    | '(' non_empty_argument_list optional_comma ')'        { $$ = $2; }
;

non_empty_argument_list:
      argument                                              { init($1); }
    | non_empty_argument_list ',' argument                  { push($1, $3); }
;

argument:
      expr                                                  { $$ = Node\Arg[$1, false, false]; }
    | '&' variable                                          { $$ = Node\Arg[$2, true, false]; }
    | T_ELLIPSIS expr                                       { $$ = Node\Arg[$2, false, true]; }
;

global_var_list:
      non_empty_global_var_list no_comma                    { $$ = $1; }
;

non_empty_global_var_list:
      non_empty_global_var_list ',' global_var              { push($1, $3); }
    | global_var                                            { init($1); }
;

global_var:
      simple_variable                                       { $$ = Expr\Variable[$1]; }
;

static_var_list:
      non_empty_static_var_list no_comma                    { $$ = $1; }
;

non_empty_static_var_list:
      non_empty_static_var_list ',' static_var              { push($1, $3); }
    | static_var                                            { init($1); }
;

static_var:
      plain_variable                                        { $$ = Stmt\StaticVar[$1, null]; }
    | plain_variable '=' expr                               { $$ = Stmt\StaticVar[$1, $3]; }
;

class_statement_list_ex:
      class_statement_list_ex class_statement               { if ($2 !== null) { push($1, $2); } }
    | /* empty */                                           { init(); }
;

class_statement_list:
      class_statement_list_ex
          { makeNop($nop, $this->lookaheadStartAttributes, $this->endAttributes);
            if ($nop !== null) { $1[] = $nop; } $$ = $1; }
;

class_statement:
      variable_modifiers optional_type property_declaration_list ';'
          { $attrs = attributes();
            $$ = new Stmt\Property($1, $3, $attrs, $2); $this->checkProperty($$, #1); }
    | method_modifiers T_CONST class_const_list ';'
          { $$ = Stmt\ClassConst[$3, $1]; $this->checkClassConst($$, #1); }
    | method_modifiers T_FUNCTION optional_ref identifier_ex '(' parameter_list ')' optional_return_type method_body
          { $$ = Stmt\ClassMethod[$4, ['type' => $1, 'byRef' => $3, 'params' => $6, 'returnType' => $8, 'stmts' => $9]];
            $this->checkClassMethod($$, #1); }
    | T_USE class_name_list trait_adaptations               { $$ = Stmt\TraitUse[$2, $3]; }
    | error                                                 { $$ = null; /* will be skipped */ }
;

trait_adaptations:
      ';'                                                   { $$ = array(); }
    | '{' trait_adaptation_list '}'                         { $$ = $2; }
;

trait_adaptation_list:
      /* empty */                                           { init(); }
    | trait_adaptation_list trait_adaptation                { push($1, $2); }
;

trait_adaptation:
      trait_method_reference_fully_qualified T_INSTEADOF class_name_list ';'
          { $$ = Stmt\TraitUseAdaptation\Precedence[$1[0], $1[1], $3]; }
    | trait_method_reference T_AS member_modifier identifier_ex ';'
          { $$ = Stmt\TraitUseAdaptation\Alias[$1[0], $1[1], $3, $4]; }
    | trait_method_reference T_AS member_modifier ';'
          { $$ = Stmt\TraitUseAdaptation\Alias[$1[0], $1[1], $3, null]; }
    | trait_method_reference T_AS identifier ';'
          { $$ = Stmt\TraitUseAdaptation\Alias[$1[0], $1[1], null, $3]; }
    | trait_method_reference T_AS reserved_non_modifiers_identifier ';'
          { $$ = Stmt\TraitUseAdaptation\Alias[$1[0], $1[1], null, $3]; }
;

trait_method_reference_fully_qualified:
      name T_PAAMAYIM_NEKUDOTAYIM identifier_ex             { $$ = array($1, $3); }
;
trait_method_reference:
      trait_method_reference_fully_qualified                { $$ = $1; }
    | identifier_ex                                         { $$ = array(null, $1); }
;

method_body:
      ';' /* abstract method */                             { $$ = null; }
    | block_or_error                                        { $$ = $1; }
;

variable_modifiers:
      non_empty_member_modifiers                            { $$ = $1; }
    | T_VAR                                                 { $$ = 0; }
;

method_modifiers:
      /* empty */                                           { $$ = 0; }
    | non_empty_member_modifiers                            { $$ = $1; }
;

non_empty_member_modifiers:
      member_modifier                                       { $$ = $1; }
    | non_empty_member_modifiers member_modifier            { $this->checkModifier($1, $2, #2); $$ = $1 | $2; }
;

member_modifier:
      T_PUBLIC                                              { $$ = Stmt\Class_::MODIFIER_PUBLIC; }
    | T_PROTECTED                                           { $$ = Stmt\Class_::MODIFIER_PROTECTED; }
    | T_PRIVATE                                             { $$ = Stmt\Class_::MODIFIER_PRIVATE; }
    | T_STATIC                                              { $$ = Stmt\Class_::MODIFIER_STATIC; }
    | T_ABSTRACT                                            { $$ = Stmt\Class_::MODIFIER_ABSTRACT; }
    | T_FINAL                                               { $$ = Stmt\Class_::MODIFIER_FINAL; }
;

property_declaration_list:
      non_empty_property_declaration_list no_comma          { $$ = $1; }
;

non_empty_property_declaration_list:
      property_declaration                                  { init($1); }
    | non_empty_property_declaration_list ',' property_declaration
          { push($1, $3); }
;

property_decl_name:
      T_VARIABLE                                            { $$ = Node\VarLikeIdentifier[parseVar($1)]; }
;

property_declaration:
      property_decl_name                                    { $$ = Stmt\PropertyProperty[$1, null]; }
    | property_decl_name '=' expr                           { $$ = Stmt\PropertyProperty[$1, $3]; }
;

expr_list:
      non_empty_expr_list no_comma                          { $$ = $1; }
;

non_empty_expr_list:
      non_empty_expr_list ',' expr                          { push($1, $3); }
    | expr                                                  { init($1); }
;

for_expr:
      /* empty */                                           { $$ = array(); }
    | expr_list                                             { $$ = $1; }
;

expr:
      variable                                              { $$ = $1; }
    | list_expr '=' expr                                    { $$ = Expr\Assign[$1, $3]; }
    | array_short_syntax '=' expr                           { $$ = Expr\Assign[$1, $3]; }
    | variable '=' expr                                     { $$ = Expr\Assign[$1, $3]; }
    | variable '=' '&' variable                             { $$ = Expr\AssignRef[$1, $4]; }
    | new_expr                                              { $$ = $1; }
    | T_CLONE expr                                          { $$ = Expr\Clone_[$2]; }
    | variable T_PLUS_EQUAL expr                            { $$ = Expr\AssignOp\Plus      [$1, $3]; }
    | variable T_MINUS_EQUAL expr                           { $$ = Expr\AssignOp\Minus     [$1, $3]; }
    | variable T_MUL_EQUAL expr                             { $$ = Expr\AssignOp\Mul       [$1, $3]; }
    | variable T_DIV_EQUAL expr                             { $$ = Expr\AssignOp\Div       [$1, $3]; }
    | variable T_CONCAT_EQUAL expr                          { $$ = Expr\AssignOp\Concat    [$1, $3]; }
    | variable T_MOD_EQUAL expr                             { $$ = Expr\AssignOp\Mod       [$1, $3]; }
    | variable T_AND_EQUAL expr                             { $$ = Expr\AssignOp\BitwiseAnd[$1, $3]; }
    | variable T_OR_EQUAL expr                              { $$ = Expr\AssignOp\BitwiseOr [$1, $3]; }
    | variable T_XOR_EQUAL expr                             { $$ = Expr\AssignOp\BitwiseXor[$1, $3]; }
    | variable T_SL_EQUAL expr                              { $$ = Expr\AssignOp\ShiftLeft [$1, $3]; }
    | variable T_SR_EQUAL expr                              { $$ = Expr\AssignOp\ShiftRight[$1, $3]; }
    | variable T_POW_EQUAL expr                             { $$ = Expr\AssignOp\Pow       [$1, $3]; }
    | variable T_COALESCE_EQUAL expr                        { $$ = Expr\AssignOp\Coalesce  [$1, $3]; }
    | variable T_INC                                        { $$ = Expr\PostInc[$1]; }
    | T_INC variable                                        { $$ = Expr\PreInc [$2]; }
    | variable T_DEC                                        { $$ = Expr\PostDec[$1]; }
    | T_DEC variable                                        { $$ = Expr\PreDec [$2]; }
    | expr T_BOOLEAN_OR expr                                { $$ = Expr\BinaryOp\BooleanOr [$1, $3]; }
    | expr T_BOOLEAN_AND expr                               { $$ = Expr\BinaryOp\BooleanAnd[$1, $3]; }
    | expr T_LOGICAL_OR expr                                { $$ = Expr\BinaryOp\LogicalOr [$1, $3]; }
    | expr T_LOGICAL_AND expr                               { $$ = Expr\BinaryOp\LogicalAnd[$1, $3]; }
    | expr T_LOGICAL_XOR expr                               { $$ = Expr\BinaryOp\LogicalXor[$1, $3]; }
    | expr '|' expr                                         { $$ = Expr\BinaryOp\BitwiseOr [$1, $3]; }
    | expr '&' expr                                         { $$ = Expr\BinaryOp\BitwiseAnd[$1, $3]; }
    | expr '^' expr                                         { $$ = Expr\BinaryOp\BitwiseXor[$1, $3]; }
    | expr '.' expr                                         { $$ = Expr\BinaryOp\Concat    [$1, $3]; }
    | expr '+' expr                                         { $$ = Expr\BinaryOp\Plus      [$1, $3]; }
    | expr '-' expr                                         { $$ = Expr\BinaryOp\Minus     [$1, $3]; }
    | expr '*' expr                                         { $$ = Expr\BinaryOp\Mul       [$1, $3]; }
    | expr '/' expr                                         { $$ = Expr\BinaryOp\Div       [$1, $3]; }
    | expr '%' expr                                         { $$ = Expr\BinaryOp\Mod       [$1, $3]; }
    | expr T_SL expr                                        { $$ = Expr\BinaryOp\ShiftLeft [$1, $3]; }
    | expr T_SR expr                                        { $$ = Expr\BinaryOp\ShiftRight[$1, $3]; }
    | expr T_POW expr                                       { $$ = Expr\BinaryOp\Pow       [$1, $3]; }
    | '+' expr %prec T_INC                                  { $$ = Expr\UnaryPlus [$2]; }
    | '-' expr %prec T_INC                                  { $$ = Expr\UnaryMinus[$2]; }
    | '!' expr                                              { $$ = Expr\BooleanNot[$2]; }
    | '~' expr                                              { $$ = Expr\BitwiseNot[$2]; }
    | expr T_IS_IDENTICAL expr                              { $$ = Expr\BinaryOp\Identical     [$1, $3]; }
    | expr T_IS_NOT_IDENTICAL expr                          { $$ = Expr\BinaryOp\NotIden