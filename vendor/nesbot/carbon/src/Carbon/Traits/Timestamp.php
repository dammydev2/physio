                        { $$ = $2; }
    | '(' yield_expr ')'                                    { $$ = $2; }
;

yield_expr:
      T_YIELD expr                                          { $$ = Expr\Yield_[$2, null]; }
    | T_YIELD expr T_DOUBLE_ARROW expr                      { $$ = Expr\Yield_[$4, $2]; }
;

array_expr:
      T_ARRAY '(' array_pair_list ')'
          { $attrs = attributes(); $attrs['kind'] = Expr\Array_::KIND_LONG;
            $$ = new Expr\Array_($3, $attrs); }
    | '[' array_pair_list ']'
          { $attrs = attributes(); $attrs['kind'] = Expr\Array_::KIND_SHORT;
            $$ = new Expr\Array_($2, $attrs); }
;

scalar_dereference:
      array_expr '[' dim_offset ']'                         { $$ = Expr\ArrayDimFetch[$1, $3]; }
    | T_CONSTANT_ENCAPSED_STRING '[' dim_offset ']'
          { $attrs = attributes(); $attrs['kind'] = strKind($1);
            $$ = Expr\ArrayDimFetch[new Scalar\String_(Scalar\String_::parse($1), $attrs), $3]; }
    | constant '[' dim_offset ']'                           { $$ = Expr\ArrayDimFetch[$1, $3]; }
    | scalar_dereference '[' dim_offset ']'                 { $$ = Expr\ArrayDimFetch[$1, $3]; }
    /* alternative array syntax missing intentionally */
;

anonymous_class:
      T_CLASS ctor_arguments extends_from implements_list '{' class_statement_list '}'
          { $$ = array(Stmt\Class_[null, ['type' => 0, 'extends' => $3, 'implements' => $4, 'stmts' => $6]], $2);
            $this->checkClass($$[0], -1); }
;

new_expr:
      T_NEW class_name_reference ctor_arguments             { $$ = Expr\New_[$2, $3]; }
    | T_NEW anonymous_class
          { list($class, $ctorArgs) = $2; $$ = Expr\New_[$class, $ctorArgs]; }
;

lexical_vars:
      /* empty */                                           { $$ = array(); }
    | T_USE '(' lexical_var_list ')'                        { $$ = $3; }
;

lexical_var_list:
      lexical_var                                           { init($1); }
    | lexical_var_list ',' lexical_var                      { push($1, $3); }
;

lexical_var:
      optional_ref plain_variable                           { $$ = Expr\ClosureUse[$2, $1]; }
;

function_call:
      name argument_list                                    { $$ = Expr\FuncCall[$1, $2]; }
    | class_name_or_var T_PAAMAYIM_NEKUDOTAYIM identifier_ex argument_list
          { $$ = Expr\StaticCall[$1, $3, $4]; }
    | class_name_or_var T_PAAMAYIM_NEKUDOTAYIM '{' expr '}' argument_list
          { $$ = Expr\StaticCall[$1, $4, $6]; }
    | static_property argument_list
          { $$ = $this->fixupPhp5StaticPropCall($1, $2, attributes()); }
    | variable_without_objects argument_list
          { $$ = Expr\FuncCall[$1, $2]; }
    | function_call '[' dim_offset ']'                      { $$ = Expr\ArrayDimFetch[$1, $3]; }
      /* alternative array syntax missing intentionally */
;

class_name:
      T_STATIC                                              { $$ = Name[$1]; }
    | name                                                  { $$ = $1; }
;

name:
      namespace_name_parts                                  { $$ = Name[$1]; }
    | T_NS_SEPARATOR namespace_name_pa