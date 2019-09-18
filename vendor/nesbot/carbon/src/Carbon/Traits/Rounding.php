array($1, false); }
;

parameter_list:
      non_empty_parameter_list                              { $$ = $1; }
    | /* empty */                                           { $$ = array(); }
;

non_empty_parameter_list:
      parameter                                             { init($1); }
    | non_empty_parameter_list ',' parameter                { push($1, $3); }
;

parameter:
      optional_param_type optional_ref optional_ellipsis plain_variable
          { $$ = Node\Param[$4, null, $1, $2, $3]; $this->checkParam($$); }
    | optional_param_type optional_ref optional_ellipsis plain_variable '=' static_scalar
          { $$ = Node\Param[$4, $6, $1, $2, $3]; $this->checkParam($$); }
;

type:
      name                                                  { $$ = $1; }
    | T_ARRAY                                               { $$ = Node\Identifier['array']; }
    | T_CALLABLE                                            { $$ = Node\Identifier['callable']; }
;

optional_param_type:
      /* empty */                                           { $$ = null; }
    | type                                                  { $$ = $1; }
;

optional_return_type:
      /* empty */                                           { $$ = null; }
    | ':' type                                              { $$ = $2; }
;

argument_list:
      '(' ')'                                               { $$ = array(); }
    | '(' non_empty_argument_list ')'                       { $$ = $2; }
    | '(' yield_expr ')'                                    { $$ = array(Node\Arg[$2, false, false]); }
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
      global_var_list ',' global_var                        { push($1, $3); }
    | global_var                                            { init($1); }
;

global_var:
      plain_variable                                        { $$ = $1; }
    | '$' variable                                          { $$ = Expr\Variable[$2]; }
    | '$' '{' expr '}'                                      { $$ = Expr\Variable[$3]; }
;

static_var_list:
      static_var_list ',' static_var                        { push($1, $3); }
    | static_var                                            { init($1); }
;

static_var:
      plain_variable                                        { $$ = Stmt\StaticVar[$1, null]; }
    | plain_variable '=' static_scalar                      { $$ = Stmt\StaticVar[$1, $3]; }
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
      variable_modifiers property_declaration_list ';'
          { $$ = Stmt\Property[$1, $2]; $this->checkProperty($$, #1); }
    | T_CONST class_const_list ';'                          { $$ = Stmt\ClassConst[$2, 0]; }
    | method_modifiers T_FUNCTION optional_ref identifier_ex '(' parameter_list ')' optional_return_type method_body
          { $$ = Stmt\ClassMethod[$4, ['type' => $1, 'byRef' => $3, 'params' => $6, 'returnType' => $8, 'stmts' => $9]];
            $this->checkClassMethod($$, #1); }
    | T_USE class_name_list trait_adaptations               { $$ = Stmt\TraitUse[$2, $3]; }
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
    | '{' inner_statement_list '}'                          { $$ = $2; }
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
    | T_PRIVATE                                             { $$ = Stmt\Class_::M