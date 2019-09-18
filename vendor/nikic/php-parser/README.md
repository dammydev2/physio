 '(' expr ')'                                          { $$ = $2; }
    | dereferencable_scalar                                 { $$ = $1; }
;

callable_variable:
      simple_variable                                       { $$ = Expr\Variable[$1]; }
    | dereferencable '[' optional_expr ']'                  { $$ = Expr\ArrayDimFetch[$1, $3]; }
    | constant '[' optional_expr ']'                        { $$ = Expr\ArrayDimFetch[$1, $3]; }
    | dereferencable '{' expr '}'                           { $$ = Expr\ArrayDimFetch[$1, $3]; }
    | function_call                                         { $$ = $1; }
    | dereferencable T_OBJECT_OPERATOR property_name argument_list
          { $$ = Expr\MethodCall[$1, $3, $4]; }
;

variable:
      callable_variable                                     { $$ = $1; }
    | static_member                                         { $$ = $1; }
    | dereferencable T_OBJECT_OPERATOR property_name        { $$ = Expr\PropertyFetch[$1, $3]; }
;

simple_variable:
      T_VARIABLE                                            { $$ = parseVar($1); }
    | '$' '{' expr '}'                                      { $$ = $3; }
    | '$' simple_variable                                   { $$ = Expr\Variable[$2]; }
    | '$' error                                             { $$ = Expr\Error[]; $this->errorState = 2; }
;

static_member_prop_name:
      simple_variable
          { $var = $1; $$ = \is_string($var) ? Node\VarLikeIdentifier[$var] : $var; }
;

static_member:
      class_name_or_var T_PAAMAYIM_NEKUDOTAYIM static_member_prop_name
          { $$ = Expr\StaticPropertyFetch[$1, $3]; }
;

new_variable:
      simple_variable                                       { $$ = Expr\Variable[$1]; }
    | new_variable '[' optional_expr ']'                    { $$ = Expr\ArrayDimFetch[$1, $3]; }
    | new_variable '{' expr '}'                             { $$ = Expr\ArrayDimFetch[$1, $3]; }
    | new_variable T_OBJECT_OPERATOR property_name          { $$ = Expr\PropertyFetch[$1, $3]; }
    | class_name T_PAAMAYIM_NEKUDOTAYIM static_member_prop_name
          { $$ = Expr\StaticPropertyFetch[$1, $3]; }
    | new_variable T_PAAMAYIM_NEKUDOTAYIM static_member_prop_name
          { $$ = Expr\StaticPropertyFetch[$1, $3]; }
;

member_name:
      identifier_ex                                         { $$ = $1; }
    | '{' expr '}'	                                        { $$ = $2; }
    | simple_variable	                                    { $$ = Expr\Variable[$1]; }
;

property_name:
      identifier                                            { $$ = $1; }
    | '{' expr '}'	                                        { $$ = $2; }
    | simple_variable	                                    { $$ = Expr\Variable[$1]; }
    | error                                                 { $$ = Expr\Error[]; $this->errorState = 2; }
;

list_expr:
      T_LIST '(' list_expr_elements ')'                     { $$ = Expr\List_[$3]; }
;

list_expr_elements:
      list_expr_elements ',' list_expr_element              { push($1, $3); }
    | list_expr_element                                     { init($1); }
;

list_expr_element:
      variable                                              { $$ = Expr\ArrayItem[$1, null, false]; }
    | '&' variable                                          { $$ = Expr\ArrayItem[$2, null, true]; }
    | list_expr                                             { $$ = Expr\ArrayItem[$1, null, false]; }
    | expr T_DOUBLE_ARROW variable                          { $$ = Expr\ArrayItem[$3, $1, false]; }
    | expr T_DOUBLE_ARROW '&' variable                      { $$ = Expr\ArrayItem[$4, $1, true]; }
    | expr T_DOUBLE_ARROW list_expr                         { $$ = Expr\ArrayItem[$3, $1, false]; }
    | /* empty */                                           { $$ = null; }
;

array_pair_list:
      inner_array_pair_list
          { $$ = $1; $end = count($$)-1; if ($$[$end] === null) array_pop($$); }
;

comma_or_error:
      ','
    | error
          { /* do nothing -- prevent default action of $$=$1. See #551. */ }
;

inner_array_pair_list:
      inner_array_pair_list comma_or_error array_pair       { push($1, $3); }
    | array_pair                                            { init($1); }
;

array_pair:
      expr T_DOUBLE_ARROW expr                              { $$ = Expr\ArrayItem[$3, $1,   false]; }
    | expr                                                  { $$ = Expr\ArrayItem[$1, null, false]; }
    | expr T_DOUBLE_ARROW '&' variable                      { $$ = Expr\ArrayItem[$4, $1,   true]; }
    | '&' variable                                          { $$ = Expr\ArrayItem[$2, null, true]; }
    | /* empty */                                           { $$ = null; }
;

encaps_list:
      encaps_list encaps_var                                { push($1, $2); }
    | encaps_list encaps_string_part                        { push($1, $2); }
    | encaps_var                                            { init($1); }
    | encaps_string_part encaps_var                         { init($1, $2); }
;

encaps_string_part:
      T_ENCAPSED_AND_WHITESPACE                             { $$ = Scalar\EncapsedStringPart[$1]; }
;

encaps_str_varname:
      T_STRING_VARNAME                                      { $$ = Expr\Variable[$1]; }
;

encaps_var:
      plain_variable                                        { $$ = $1; }
    | plain_variable '[' encaps_var_offset ']'              { $$ = Expr\ArrayDimFetch[$1, $3]; }
    | plain_variable T_OBJECT_OPERATOR identifier           { $$ = Expr\PropertyFetch[$1, $3]; }
    | T_DOLLAR_OPEN_CURLY_BRACES expr '}'                   { $$ = Expr\Variable[$2]; }
    | T_DOLLAR_OPEN_CURLY_BRACES T_STRING_VARNAME '}'       { $$ = Expr\Variable[$2]; }
    | T_DOLLAR_OPEN_CURLY_BRACES encaps_str_varname '[' expr ']' '}'
          { $$ = Expr\ArrayDimFetch[$2, $4]; }
    | T_CURLY_OPEN variable '}'                             { $$ = $2; }
;

encaps_var_offset:
      T_STRING                                              { $$ = Scalar\String_[$1]; }
    | T_NUM_STRING                                          { $$ = $this->parseNumString($1, attributes()); }
    |