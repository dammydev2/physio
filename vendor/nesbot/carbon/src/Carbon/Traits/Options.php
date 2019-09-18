_name_parts T_NS_SEPARATOR '{' unprefixed_use_declarations '}'
          { $$ = Stmt\GroupUse[new Name($4, stackAttributes(#4)), $7, $2]; }
    | T_USE namespace_name_parts T_NS_SEPARATOR '{' inline_use_declarations '}'
          { $$ = Stmt\GroupUse[new Name($2, stackAttributes(#2)), $5, Stmt\Use_::TYPE_UNKNOWN]; }
    | T_USE T_NS_SEPARATOR namespace_name_parts T_NS_SEPARATOR '{' inline_use_declarations '}'
          { $$ = Stmt\GroupUse[new Name($3, stackAttributes(#3)), $6, Stmt\Use_::TYPE_UNKNOWN]; }
;

unprefixed_use_declarations:
      unprefixed_use_declarations ',' unprefixed_use_declaration
          { push($1, $3); }
    | unprefixed_use_declaration                            { init($1); }
;

use_declarations:
      use_declarations ',' use_declaration                  { push($1, $3); }
    | use_declaration                                       { init($1); }
;

inline_use_declarations:
      inline_use_declarations ',' inline_use_declaration    { push($1, $3); }
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
      constant_declaration_list ',' constant_declaration    { push($1, $3); }
    | constant_declaration                                  { init($1); }
;

constant_declaration:
    identifier '=' static_scalar                            { $$ = Node\Const_[$1, $3]; }
;

class_const_list:
      class_const_list ',' class_const                      { push($1, $3); }
    | class_const                                           { init($1); }
;

class_const:
    identifier_ex '=' static_scalar                         { $$ = Node\Const_[$1, $3]; }
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
    | T_IF parentheses_expr statement elseif_list else_single
          { $$ = Stmt\If_[$2, ['stmts' => toArray($3), 'elseifs' => $4, 'else' => $5]]; }
    | T_IF parentheses_expr ':' inner_statement_list new_elseif_list new_else_single T_ENDIF ';'
          { $$ = Stmt\If_[$2, ['stmts' => $4, 'elseifs' => $5, 'else' => $6]]; }
    | T_WHILE parentheses_expr while_statement              { $$ = Stmt\While_[$2, $3]; }
    | T_DO statement T_WHILE parentheses_expr ';'           { $$ = Stmt\Do_   [$4, toArray($2)]; }
    | T_FOR '(' for_expr ';'  for_expr ';' for_expr ')' for_statement
          { $$ = Stmt\For_[['init' => $3, 'cond' => $5, 'loop' => $7, 'stmts' => $9]]; }
    | T_SWITCH parentheses_expr switch_case_list            { $$ = Stmt\Switch_[$2, $3]; }
    | T_BREAK ';'                                           { $$ = Stmt\Break_[null]; }
    | T_BREAK expr ';'                                      { $$ = Stmt\Break_[$2]; }
    | T_CONTINUE ';'                                        { $$ = Stmt\Continue_[null]; }
    | T_CONTINUE expr ';'                                   { $$ = Stmt\Continue_[$2]; }
    | T_RETURN ';'                                          { $$ = Stmt\Return_[null]; }
    | T_RETURN expr ';'                                     { $$ = Stmt\Return_[$2]; }
    | T_GLOBAL global_var_list ';'                          { $$ = Stmt\Global_[$2]; }
    | T_STATIC static_var_list ';'                          { $$ = Stmt\Static_[$2]; }
    | T_ECHO expr_list ';'                                  { $$ = Stmt\Echo_[$2]; }
    | T_INLINE_HTML                                         { $$ = Stmt\InlineHTML[$1]; }
    | yield_expr ';'                                        { $$ = Stmt\Expression[$1]; }
    | expr ';'                                              { $$ = Stmt\Expression[$1]; }
    | T_UNSET '(' variables_list ')' ';'                    { $$ = Stmt\Unset_[$3]; }
    | T_FOREACH '(' expr T_AS foreach_variable ')' foreach_statement
          { $$ = Stmt\Foreach_[$3, $5[0], ['keyVar' => null, 'byRef' => $5[1], 'stmts' => $7]]; }
    | T_FOREACH '(' expr T_AS variable T_DOUBLE_ARROW foreach_variable ')' foreach_statement
          { $$ = Stmt\Foreach_[$3, $7[0], ['keyVar' => $5, 'byRef' => $7[1], 'stmts' => $9]]; }
    | T_DECLARE '(' declare_list ')' declare_statement      { $$ = Stmt\Declare_[$3, $5]; }
    | T_TRY '{' inner_statement_list '}' catches optional_finally
          { $$ = Stmt\TryCatch[$3, $5, $6]; $this->checkTryCatch($$); }
    | T_THROW expr ';'                                      { $$ = Stmt\Throw_[$2]; }
    | T_GOTO identifier ';'                                 { $$ = Stmt\Goto_[$2]; }
    | identifier ':'                                        { $$ = Stmt\Label[$1]; }
    | expr error                                            { $$ = Stmt\Expression[$1]; }
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

catch:
    T_CATCH '(' name plain_variable ')' '{' inner_statement_list '}'
        { $$ = Stmt\Catch_[array($3), $4, $7]; }
;

optional_finally:
      /* empty */                                           { $$ = null; }
    | T_FINALLY '{' inner_statement_list '}'                { $$ = Stmt\Finally_[$3]; }
;

variables_list:
      variable                                              { init($1); }
    | variables_list ',' variable                           { push($1, $3); }
;

optional_ref:
      /* empty */                                           { $$ = false; }
    | '&'                                                   { $$ = true; }
;

optional_ellipsis:
      /* empty */                                           { $$ = false; }
    | T_ELLIPSIS                                            { $$ = true; }
;

function_declaration_statement:
    T_FUNCTION optional_ref identifier '(' parameter_list ')' optional_return_type '{' inner_statement_list '}'
        { $$ = Stmt\Function_[$3, ['byRef' => $2, 'params' => $5, 'returnType' => $7, 'stmts' => $9]]; }
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
      class_name                                            { init($1); }
    | class_name_list ',' class_name                        { push($1, $3); }
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
      declare_list_element                                  { init($1); }
    | declare_list ',' declare_list_element                 { push($1, $3); }
;

declare_list_element:
      identifier '=' static_scalar                          { $$ = Stmt\DeclareDeclare[$1, $3]; }
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
      T_ELSEIF parentheses_expr statement                   { $$ = Stmt\ElseIf_[$2, toArray($3)]; }
;

new_elseif_list:
      /* empty */                                           { init(); }
    | new_elseif_list new_elseif                            { push($1, $2); }
;

new_elseif:
     T_ELSEIF parentheses_expr ':' inner_statement_list     { $$ = Stmt\ElseIf_[$2, $4]; }
;

else_single:
      /* empty */                                           { $$ = null; }
    | T_ELSE statement                                  