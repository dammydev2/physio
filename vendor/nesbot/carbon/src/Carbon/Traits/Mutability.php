%pure_parser
%expect 6

%tokens

%%

start:
    top_statement_list                                      { $$ = $this->handleNamespaces($1); }
;

top_statement_list_ex:
      top_statement_list_ex top_statement                   { pushNormalizing($1, $2); }
    | /* empty */                                           { init(); }
;

top_statement_list:
      top_statement_list_ex
          { makeNop($nop, $this->lookaheadStartAttributes, $this->endAttributes);
            if ($nop !== null) { $1[] = $nop; } $$ = $1; }
;

reserved_non_modifiers:
      T_INCLUDE | T_INCLUDE_ONCE | T_EVAL | T_REQUIRE | T_REQUIRE_ONCE | T_LOGICAL_OR | T_LOGICAL_XOR | T_LOGICAL_AND
    | T_INSTANCEOF | T_NEW | T_CLONE | T_EXIT | T_IF | T_ELSEIF | T_ELSE | T_ENDIF | T_ECHO | T_DO | T_WHILE
    | T_ENDWHILE | T_FOR | T_ENDFOR | T_FOREACH | T_ENDFOREACH | T_DECLARE | T_ENDDECLARE | T_AS | T_TRY | T_CATCH
    | T_FINALLY | T_THROW | T_USE | T_INSTEADOF | T_GLOBAL | T_VAR | T_UNSET | T_ISSET | T_EMPTY | T_CONTINUE | T_GOTO
    | T_FUNCTION | T_CONST | T_RETURN | T_PRINT | T_YIELD | T_LIST | T_SWITCH | T_ENDSWITCH | T_CASE | T_DEFAULT
    | T_BREAK | T_ARRAY | T_CALLABLE | T_EXTENDS | T_IMPLEMENTS | T_NAMESPACE | T_TRAIT | T_INTERFACE | T_CLASS
    | T_CLASS_C | T_TRAIT_C | T_FUNC_C | T_METHOD_C | T_LINE | T_FILE | T_DIR | T_NS_C | T_HALT_COMPILER
;

semi_reserved:
      reserved_non_modifiers
    | T_STATIC | T_ABSTRACT | T_FINAL | T_PRIVATE | T_PROTECTED | T_PUBLIC
;

identifier_ex:
      T_STRING                                              { $$ = Node\Identifier[$1]; }
    | semi_reserved                                         { $$ = Node\Identifier[$1]; }
;

identifier:
      T_STRING                                              { $$ = Node\Identifier[$1]; }
;

reserved_non_modifiers_identifier:
      reserved_