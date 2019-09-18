$1, $3]; }
    | expr T_SPACESHIP expr                                 { $$ = Expr\BinaryOp\Spaceship     [$1, $3]; }
    | expr '<' expr                                         { $$ = Expr\BinaryOp\Smaller       [$1, $3]; }
    | expr T_IS_SMALLER_OR_EQUAL expr                       { $$ = Expr\BinaryOp\SmallerOrEqual[$1, $3]; }
    | expr '>' expr                                         { $$ = Expr\BinaryOp\Greater       [$1, $3]; }
    | expr T_IS_GREATER_OR_EQUAL expr                       { $$ = Expr\BinaryOp\GreaterOrEqual[$1, $3]; }
    | expr T_INSTANCEOF class_name_reference                { $$ = Expr\Instanceof_[$1, $3]; }
    | parentheses_expr                                      { $$ = $1; }
    /* we need a separate '(' new_expr ')' rule to avoid problems caused by a s/r conflict */
    | '(' new_expr ')'                                      { $$ = $2; }
    | expr '?' expr ':' expr                                { $$ = Expr\Ternary[$1, $3,   $5]; }
    | expr '?' ':' expr                                     { $$ = Expr\Ternary[$1, null, $4]; }
    | expr T_COALESCE expr                                  { $$ = Expr\BinaryOp\Coalesce[$1, $3]; }
    | T_ISSET '(' variables_list ')'                        { $$ = Expr\Isset_[$3]; }
    | T_EMPTY '(' expr ')'                                  { $$ = Expr\Empty_[$3]; }
    | T_INCLUDE expr                                        { $$ = Expr\Include_[$2, Expr\Include_::TYPE_INCLUDE]; }
    | T_INCLUDE_ONCE expr                                   { $$ = Expr\Include_[$2, Expr\Include_::TYPE_INCLUDE_ONCE]; }
    | T_EVAL parentheses_expr                               { $$ = Expr\Eval_[$2]; }
    | T_REQUIRE expr                                        { $$ = Expr\Include_[$2, Expr\Include_::TYPE_REQUIRE]; }
    | T_REQUIRE_ONCE expr                                   { $$ = Expr\Include_[$2, Expr\Include_::TYPE_REQUIRE_ONCE]; }
    | T_INT_CAST expr                                       { $$ = Expr\Cast\Int_    [$2]; }
    | T_DOUBLE_CAST expr
          { $attrs = attributes();
            $attrs['kind'] = $this->getFloatCastKind($1);
            $$ = new Expr\Cast\Double($2, $attrs); }
    | T_STRING_CAST expr                                    { $$ = Expr\Cast\String_ [$2]; }
    | T_ARRAY_CAST expr                                     { $$ = Expr\Cast\Array_  [$2]; }
    | T_OBJECT_CAST expr                                    { $$ = Expr\Cast\Object_ [$2]; }
    | T_BOOL_CAST expr                                      { $$ = Expr\Cast\Bool_   [$2]; }
    | T_UNSET_CAST expr                                     { $$ = Expr\Cast\Unset_  [$2]; }
    | T_EXIT exit_expr
          { $attrs = attributes();
            $attrs['kind'] = strtolower($1) === 'exit' ? Expr\Exit_::KIND_EXIT : Expr\Exit_::KIND_DIE;
            $$ = new Expr\Exit_($2, $attrs); }
    | '@' expr                                              { $$ = Expr\ErrorSuppress[$2]; }
    | scalar                                                { $$ = $1; }
    | array_expr                                            { $$ = $1; }
    | scalar_dereference                                    {