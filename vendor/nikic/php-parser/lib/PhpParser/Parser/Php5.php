 new Stmt\Expression($this->semStack[$stackPos-(2-1)], $this->startAttributeStack[$stackPos-(2-1)] + $this->endAttributes);
            },
            156 => function ($stackPos) {
                 $this->semValue = new Stmt\Unset_($this->semStack[$stackPos-(5-3)], $this->startAttributeStack[$stackPos-(5-1)] + $this->endAttributes);
            },
            157 => function ($stackPos) {
                 $this->semValue = new Stmt\Foreach_($this->semStack[$stackPos-(7-3)], $this->semStack[$stackPos-(7-5)][0], ['keyVar' => null, 'byRef' => $this->semStack[$stackPos-(7-5)][1], 'stmts' => $this->semStack[$stackPos-(7-7)]], $this->startAttributeStack[$stackPos-(7-1)] + $this->endAttributes);
            },
            158 => function ($stackPos) {
                 $this->semValue = new Stmt\Foreach_($this->semStack[$stackPos-(9-3)], $this->semStack[$stackPos-(9-7)][0], ['keyVar' => $this->semStack[$stackPos-(9-5)], 'byRef' => $this->semStack[$stackPos-(9-7)][1], 'stmts' => $this->semStack[$stackPos-(9-9)]], $this->startAttributeStack[$stackPos-(9-1)] + $this->endAttributes);
            },
            159 => function ($stackPos) {
                 $this->semValue = new Stmt\Foreach_($this->semStack[$stackPos-(6-3)], new Expr\Error($this->startAttributeStack[$stackPos-(6-4)] + $this->endAttributeStack[$stackPos-(6-4)]), ['stmts' => $this->semStack[$stackPos-(6-6)]], $this->startAttributeStack[$stackPos-(6-1)] + $this->endAttributes);
            },
            160 => function ($stackPos) {
                 $this->semValue = new Stmt\Declare_($this->semStack[$stackPos-(5-3)], $this->semStack[$stackPos-(5-5)], $this->startAttributeStack[$stackPos-(5-1)] + $this->endAttributes);
            },
            161 => function ($stackPos) {
                 $this->semValue = new Stmt\TryCatch($this->semStack[$stackPos-(6-3)], $this->semStack[$stackPos-(6-5)], $this->semStack[$stackPos-(6-6)], $this->startAttributeStack[$stackPos-(6-1)] + $this->endAttributes); $this->checkTryCatch($this->semValue);
            },
            162 => function ($stackPos) {
                 $this->semValue = new Stmt\Throw_($this->semStack[$stackPos-(3-2)], $this->startAttributeStack[$stackPos-(3-1)] + $this->endAttributes);
            },
            163 => function ($stackPos) {
                 $this->semValue = new Stmt\Goto_($this->semStack[$stackPos-(3-2)], $this->startAttributeStack[$stackPos-(3-1)] + $this->endAttributes);
            },
            164 => function ($stackPos) {
                 $this->semValue = new Stmt\Label($this->semStack[$stackPos-(2-1)], $this->startAttributeStack[$stackPos-(2-1)] + $this->endAttributes);
            },
            165 => function ($stackPos) {
                 $this->semValue = array(); /* means: no statement */
            },
            166 => function ($stackPos) {
                 $this->semValue = $this->semStack[$stackPos-(1-1)];
            },
            167 => function ($stackPos) {
                 $startAttributes = $this->startAttributeStack[$stackPos-(1-1)]; if (isset($startAttributes['comments'])) { $this->semValue = new Stmt\Nop($startAttributes + $this->endAttributes); } else { $this->semValue = null; };
            if ($this->semValue === null) $this->semValue = array(); /* means: no statement */
            },
            168 => function ($stackPos) {
                 $this->semValue = array();
            },
            169 => function ($stackPos) {
                 $this->semStack[$stackPos-(2-1)][] = $this->semStack[$stackPos-(2-2)]; $this->semValue = $this->semStack[$stackPos-(2-1)];
            },
            170 => function ($stackPos) {
                 $this->semValue = array($this->semStack[$stackPos-(1-1)]);
            },
            171 => function ($stackPos) {
                 $this->semStack[$stackPos-(3-1)][] = $this->semStack[$stackPos-(3-3)]; $this->semValue = $this->semStack[$stackPos-(3-1)];
            },
            172 => function ($stackPos) {
                 $this->semValue = new Stmt\Catch_($this->semStack[$stackPos-(8-3)], $this->semStack[$stackPos-(8-4)], $this->semStack[$stackPos-(8-7)], $this->startAttributeStack[$stackPos-(8-1)] + $this->endAttributes);
            },
            173 => function ($stackPos) {
                 $this->semValue = null;
            },
            174 => function ($stackPos) {
                 $this->semValue = new Stmt\Finally_($this->semStack[$stackPos-(4-3)], $this->startAttributeStack[$stackPos-(4-1)] + $this->endAttributes);
            },
            175 => function ($stackPos) {
                 $this->semValue = $this->semStack[$stackPos-(2-1)];
            },
            176 => function ($stackPos) {
                 $this->semValue = array($this->semStack[$stackPos-(1-1)]);
            },
            177 => function ($stackPos) {
                 $this->semStack[$stackPos-(3-1)][] = $this->semStack[$stackPos-(3-3)]; $this->semValue = $this->semStack[$stackPos-(3-1)];
            },
            178 => function ($stackPos) {
                 $this->semValue = false;
            },
            179 => function ($stackPos) {
                 $this->semValue = true;
            },
            180 => function ($stackPos) {
                 $this->semValue = false;
            },
            181 => function ($stackPos) {
                 $this->semValue = true;
            },
            182 => function ($stackPos) {
                 $this->semValue = $this->semStack[$stackPos-(3-2)];
            },
            183 => function ($stackPos) {
                 $this->semValue = [];
            },
            184 => function ($stackPos) {
                 $this->semValue = new Stmt\Function_($this->semStack[$stackPos-(8-3)], ['byRef' => $this->semStack[$stackPos-(8-2)], 'params' => $this->semStack[$stackPos-(8-5)], 'returnType' => $this->semStack[$stackPos-(8-7)], 'stmts' => $this->semStack[$stackPos-(8-8)]], $this->startAttributeStack[$stackPos-(8-1)] + $this->endAttributes);
            },
            185 => function ($stackPos) {
                 $this->semValue = new Stmt\Class_($this->semStack[$stackPos-(7-2)], ['type' => $this->semStack[$stackPos-(7-1)], 'extends' => $this->semStack[$stackPos-(7-3)], 'implements' => $this->semStack[$stackPos-(7-4)], 'stmts' => $this->semStack[$stackPos-(7-6)]], $this->startAttributeStack[$stackPos-(7-1)] + $this->endAttributes);
            $this->checkClass($this->semValue, $stackPos-(7-2));
            },
            186 => function ($stackPos) {
                 $this->semValue = new Stmt\Interface_($this->semStack[$stackPos-(6-2)], ['extends' => $this->semStack[$stackPos-(6-3)], 'stmts' => $this->semStack[$stackPos-(6-5)]], $this->startAttributeStack[$stackPos-(6-1)] + $this->endAttributes);
            $this->checkInterface($this->semValue, $stackPos-(6-2));
            },
            187 => function ($stackPos) {
                 $this->semValue = new Stmt\Trait_($this->semStack[$stackPos-(5-2)], ['stmts' => $this->semStack[$stackPos-(5-4)]], $this->startAttributeStack[$stackPos-(5-1)] + $this->endAttributes);
            },
            188 => function ($stackPos) {
                 $this->semValue = 0;
            },
            189 => function ($stackPos) {
                 $this->semValue = Stmt\Class_::MODIFIER_ABSTRACT;
            },
            190 => function ($stackPos) {
                 $this->semValue = Stmt\Class_::MODIFIER_FINAL;
            },
            191 => function ($stackPos) {
                 $this->semValue = null;
            },
            192 => function ($stackPos) {
                 $this->semValue = $this->semStack[$stackPos-(2-2)];
            },
            193 => function ($stackPos) {
                 $this->semValue = array();
            },
            194 => function ($stackPos) {
                 $this->semValue = $this->semStack[$stackPos-(2-2)];
            },
            195 => function ($stackPos) {
                 $this->semValue = array();
            },
            196 => function ($stackPos) {
                 $this->semValue = $this->semStack[$stackPos-(2-2)];
            },
            197 => function ($stackPos) {
                 $this->semValue = $this->semStack[$stackPos-(2-1)];
            },
            198 => function ($stackPos) {
                 $this->semValue = array($this->semStack[$stackPos-(1-1)]);
            },
            199 => function ($stackPos) {
                 $this->semStack[$stackPos-(3-1)][] = $this->semStack[$stackPos-(3-3)]; $this->semValue = $this->semStack[$stackPos-(3-1)];
            },
            200 => function ($stackPos) {
                 $this->semValue = is_array($this->semStack[$stackPos-(1-1)]) ? $this->semStack[$stackPos-(1-1)] : array($this->semStack[$stackPos-(1-1)]);
            },
            201 => function ($stackPos) {
                 $this->semValue = $this->semStack[$stackPos-(4-2)];
            },
            202 => function ($stackPos) {
                 $this->semValue = is_array($this->semStack[$stackPos-(1-1)]) ? $this->semStack[$stackPos-(1-1)] : array($this->semStack[$stackPos-(1-1)]);
            },
            203 => function ($stackPos) {
                 $this->semValue = $this->semStack[$stackPos-(4-2)];
            },
            204 => function ($stackPos) {
                 $this->semValue = is_array($this->semStack[$stackPos-(1-1)]) ? $this->semStack[$stackPos-(1-1)] : array($this->semStack[$stackPos-(1-1)]);
            },
            205 => function ($stackPos) {
                 $this->semValue = null;
            },
            206 => function ($stackPos) {
                 $this->semValue = $this->semStack[$stackPos-(4-2)];
            },
            207 => function ($stackPos) {
                 $this->semValue = $this->semStack[$stackPos-(2-1)];
            },
            208 => function ($stackPos) {
                 $this->semValue = array($this->semStack[$stackPos-(1-1)]);
            },
            209 => function ($stackPos) {
                 $this->semStack[$stackPos-(3-1)][] = $this->semStack[$stackPos-(3-3)]; $this->semValue = $this->semStack[$stackPos-(3-1)];
            },
            210 => function ($stackPos) {
                 $this->semValue = new Stmt\DeclareDeclare($this->semStack[$stackPos-(3-1)], $this->semStack[$stackPos-(3-3)], $this->startAttributeStack[$stackPos-(3-1)] + $this->endAttributes);
            },
            211 => function ($stackPos) {
                 $this->semValue = $this->semStack[$stackPos-(3-2)];
            },
            212 => function ($stackPos) {
                 $this->semValue = $this->semStack[$stackPos-(4-3)];
            },
            213 => function ($stackPos) {
                 $this->semValue = $this->semStack[$stackPos-(4-2)];
            },
            214 => function ($stackPos) {
                 $this->semValue = $this->semStack[$stackPos-(5-3)];
            },
            215 => function ($stackPos) {
                 $this->semValue = array();
            },
            216 => function ($stackPos) {
                 $this->semStack[$stackPos-(2-1)][] = $this->semStack[$stackPos-(2-2)]; $this->semValue = $this->semStack[$stackPos-(2-1)];
            },
            217 => function ($stackPos) {
                 $this->semValue = new Stmt\Case_($this->semStack[$stackPos-(4-2)], $this->semStack[$stackPos-(4-4)], $this->startAttributeStack[$stackPos-(4-1)] + $this->endAttributes);
            },
            218 => function ($stackPos) {
                 $this->semValue = new Stmt\Case_(null, $this->semStack[$stackPos-(3-3)], $this->startAttributeStack[$stackPos-(3-1)] + $this->endAttributes);
            },
            219 => function ($stackPos) {
                $this->semValue = $this->semStack[$stackPos];
            },
            220 => function ($stackPos) {
                $this->semValue = $this->semStack[$stackPos];
            },
            221 => function ($stackPos) {
                 $this->semValue = is_array($this->semStack[$stackPos-(1-1)]) ? $this->semStack[$stackPos-(1-1)] : array($this->semStack[$stackPos-(1-1)]);
            },
            222 => function ($stackPos) {
                 $this->semValue = $this->semStack[$stackPos-(4-2)];
            },
            223 => function ($stackPos) {
                 $this->semValue = array();
            },
            224 => function ($stackPos) {
                 $this->semStack[$stackPos-(2-1)][] = $this->semStack[$stackPos-(2-2)]; $this->semValue = $this->semStack[$stackPos-(2-1)];
            },
            225 => function ($stackPos) {
                 $this->semValue = new Stmt\ElseIf_($this->semStack[$stackPos-(5-3)], is_array($this->semStack[$stackPos-(5-5)]) ? $this->semStack[$stackPos-(5-5)] : array($this->semStack[$stackPos-(5-5)]), $this->startAttributeStack[$stackPos-(5-1)] + $this->endAttributes);
            },
            226 => function ($stackPos) {
                 $this->semValue = array();
            },
            227 => function ($stackPos) {
                 $this->semStack[$stackPos-(2-1)][] = $this->semStack[$stackPos-(2-2)]; $this->semValue = $this->semStack[$stackPos-(2-1)];
            },
            228 => function ($stackPos) {
                 $this->semValue = new Stmt\ElseIf_($this->semStack[$stackPos-(6-3)], $this->semStack[$stackPos-(6-6)], $this->startAttributeStack[$stackPos-(6-1)] + $this->endAttributes);
            },
            229 => function ($stackPos) {
                 $this->semValue = null;
            },
            230 => function ($stackPos) {
                 $this->semValue = new Stmt\Else_(is_array($this->semStack[$stackPos-(2-2)]) ? $this->semStack[$stackPos-(2-2)] : array($this->semStack[$stackPos-(2-2)]), $this->startAttributeStack[$stackPos-(2-1)] + $this->endAttributes);
            },
            231 => function ($stackPos) {
                 $this->semValue = null;
            },
            232 => function ($stackPos) {
                 $this->semValue = new Stmt\Else_($this->semStack[$stackPos-(3-3)], $this->startAttributeStack[$stackPos-(3-1)] + $this->endAttributes);
            },
            233 => function ($stackPos) {
                 $this->semValue = array($this->semStack[$stackPos-(1-1)], false);
            },
            234 => function ($stackPos) {
                 $this->semValue = array($this->semStack[$stackPos-(2-2)], true);
            },
            235 => function ($stackPos) {
                 $this->semValue = array($this->semStack[$stackPos-(1-1)], false);
            },
            236 => function ($stackPos) {
                 $this->semValue = array($this->semStack[$stackPos-(1-1)], false);
            },
            237 => function ($stackPos) {
                 $this->semValue = $this->semStack[$stackPos-(2-1)];
            },
            238 => function ($stackPos) {
                 $this->semValue = array();
            },
            239 => function ($stackPos) {
                 $this->semValue = array($this->semStack[$stackPos-(1-1)]);
            },
            240 => function ($stackPos) {
                 $this->semStack[$stackPos-(3-1)][] = $this->semStack[$stackPos-(3-3)]; $this->semValue = $this->semStack[$stackPos-(3-1)];
            },
            241 => function ($stackPos) {
                 $this->semValue = new Node\Param($this->semStack[$stackPos-(4-4)], null, $this->semStack[$stackPos-(4-1)], $this->semStack[$stackPos-(4-2)], $this->semStack[$stackPos-(4-3)], $this->startAttributeStack[$stackPos-(4-1)] + $this->endAttributes); $this->checkParam($this->semValue);
            },
            242 => function ($stackPos) {
                 $this->semValue = new Node\Param($this->semStack[$stackPos-(6-4)], $this->semStack[$stackPos-(6-6)], $this->semStack[$stackPos-(6-1)], $this->semStack[$stackPos-(6-2)], $this->semStack[$stackPos-(6-3)], $this->startAttributeStack[$stackPos-(6-1)] + $this->endAttributes); $this->checkParam($this->semValue);
            },
            243 => function ($stackPos) {
                 $this->semValue = new Node\Param(new Expr\Error($this->startAttributeStack[$stackPos-(4-1)] + $this->endAttributes), null, $this->semStack[$stackPos-(4-1)], $this->semStack[$stackPos-(4-2)], $this->semStack[$stackPos-(4-3)], $this->startAttributeStack[$stackPos-(4-1)] + $this->endAttributes);
            },
            244 => function ($stackPos) {
                 $this->semValue = $this->semStack[$stackPos-(1-1)];
            },
            245 => function ($stackPos) {
                 $this->semValue = new Node\NullableType($this->semStack[$stackPos-(2-2)], $this->startAttributeStack[$stackPos-(2-1)] + $this->endAttributes);
            },
            246 => function ($stackPos) {
                 $this->semValue = $this->handleBuiltinTypes($this->semStack[$stackPos-(1-1)]);
            },
            247 => function ($stackPos) {
                 $this->semValue = new Node\Identifier('array', $this->startAttributeStack[$stackPos-(1-1)] + $this->endAttributes);
            },
            248 => function ($stackPos) {
                 $this->semValue = new Node\Identifier('callable', $this->startAttributeStack[$stackPos-(1-1)] + $this->endAttributes);
            },
            249 => function ($stackPos) {
                 $this->semValue = null;
            },
            250 => function ($stackPos) {
                 $this->semValue = $this->semStack[$stackPos-(1-1)];
            },
            251 => function ($stackPos) {
                 $this->semValue = null;
            },
            252 => function ($stackPos) {
                 $this->semValue = $this->semStack[$stackPos-(2-2)];
            },
            253 => function ($stackPos) {
                 $this->semValue = null;
            },
            254 => function ($stackPos) {
                 $this->semValue = array();
            },
            255 => function ($stackPos) {
                 $this->semValue = $this->semStack[$stackPos-(4-2)];
            },
            256 => function ($stackPos) {
                 $this->semValue = array($this->semStack[$stackPos-(1-1)]);
            },
            257 => function ($stackPos) {
                 $this->semStack[$stackPos-(3-1)][] = $this->semStack[$stackPos-(3-3)]; $this->semValue = $this->semStack[$stackPos-(3-1)];
            },
            258 => function ($stackPos) {
                 $this->semValue = new Node\Arg($this->semStack[$stackPos-(1-1)], false, false, $this->startAttributeStack[$stackPos-(1-1)] + $this->endAttributes);
            },
            259 => function ($stackPos) {
                 $this->semValue = new Node\Arg($this->semStack[$stackPos-(2-2)], true, false, $this->startAttributeStack[$stackPos-(2-1)] + $this->endAttributes);
            },
            260 => function ($stackPos) {
                 $this->semValue = new Node\Arg($this->semStack[$stackPos-(2-2)], false, true, $this->startAttributeStack[$stackPos-(2-1)] + $this->endAttributes);
            },
            261 => function ($stackPos) {
                 $this->semValue = $this->semStack[$stackPos-(2-1)];
            },
            262 => function ($stackPos) {
                 $this->semStack[$stackPos-(3-1)][] = $this->semStack[$stackPos-(3-3)]; $this->semValue = $this->semStack[$stackPos-(3-1)];
            },
            263 => function ($stackPos) {
                 $this->semValue = array($this->semStack[$stackPos-(1-1)]);
            },
            264 => function ($stackPos) {
                 $this->semValue = new Expr\Variable($this->semStack[$stackPos-(1-1)], $this->startAttributeStack[$stackPos-(1-1)] + $this->endAttributes);
            },
            265 => function ($stackPos) {
                 $this->semValue = $this->semStack[$stackPos-(2-1)];
            },
            266 => function ($stackPos) {
                 $this->semStack[$stackPos-(3-1)][] = $this->semStack[$stackPos-(3-3)]; $this->semValue = $this->semStack[$stackPos-(3-1)];
            },
            267 => function ($stackPos) {
                 $this->semValue = array($this->semStack[$stackPos-(1-1)]);
            },
            268 => function ($stackPos) {
                 $this->semValue = new Stmt\StaticVar($this->semStack[$stackPos-(1-1)], null, $this->startAttributeStack[$stackPos-(1-1)] + $this->endAttributes);
            },
            269 => function ($stackPos) {
                 $this->semValue = new Stmt\StaticVar($this->semStack[$stackPos-(3-1)], $this->semStack[$stackPos-(3-3)], $this->startAttributeStack[$stackPos-(3-1)] + $this->endAttributes);
            },
            270 => function ($stackPos) {
                 if ($this->semStack[$stackPos-(2-2)] !== null) { $this->semStack[$stackPos-(2-1)][] = $this->semStack[$stackPos-(2-2)]; $this->semValue = $this->semStack[$stackPos-(2-1)]; }
            },
            271 => function ($stackPos) {
                 $this->semValue = array();
            },
            272 => function ($stackPos) {
                 $startAttributes = $this->lookaheadStartAttributes; if (isset($startAttributes['comments'])) { $nop = new Stmt\Nop($startAttributes + $this->endAttributes); } else { $nop = null; };
            if ($nop !== null) { $this->semStack[$stackPos-(1-1)][] = $nop; } $this->semValue = $this->semStack[$stackPos-(1-1)];
            },
            273 => function ($stackPos) {
                 $attrs = $this->startAttributeStack[$stackPos-(4-1)] + $this->endAttributes;
            $this->semValue = new Stmt\Property($this->semStack[$stackPos-(4-1)], $this->semStack[$stackPos-(4-3)], $attrs, $this->semStack[$stackPos-(4-2)]); $this->checkProperty($this->semValue, $stackPos-(4-1));
            },
            274 => function ($stackPos) {
                 $this->semValue = new Stmt\ClassConst($this->semStack[$stackPos-(4-3)], $this->semStack[$stackPos-(4-1)], $this->startAttributeStack[$stackPos-(4-1)] + $this->endAttributes); $this->checkClassConst($this->semValue, $stackPos-(4-1));
            },
            275 => function ($stackPos) {
                 $this->semValue = new Stmt\ClassMethod($this->semStack[$stackPos-(9-4)], ['type' => $this->semStack[$stackPos-(9-1)], 'byRef' => $this->semStack[$stackPos-(9-3)], 'params' => $this->semStack[$stackPos-(9-6)], 'returnType' => $this->semStack[$stackPos-(9-8)], 'stmts' => $this->semStack[$stackPos-(9-9)]], $this->startAttributeStack[$stackPos-(9-1)] + $this->endAttributes);
            $this->checkClassMethod($this->semValue, $stackPos-(9-1));
            },
            276 => function ($stackPos) {
                 $this->semValue = new Stmt\TraitUse($this->semStack[$stackPos-(3-2)], $this->semStack[$stackPos-(3-3)], $this->startAttributeStack[$stackPos-(3-1)] + $this->endAttributes);
            },
            277 => function ($stackPos) {
                 $this->semValue = null; /* will be skipped */
            },
            278 => function ($stackPos) {
                 $this->semValue = array();
            },
            279 => function ($stackPos) {
                 $this->semValue = $this->semStack[$stackPos-(3-2)];
            },
            280 => function ($stackPos) {
                 $this->semValue = array();
            },
            281 => function ($stackPos) {
                 $this->semStack[$stackPos-(2-1)][] = $this->semStack[$stackPos-(2-2)]; $this->semValue = $this->semStack[$stackPos-(2-1)];
            },
            282 => function ($stackPos) {
                 $this->semValue = new Stmt\TraitUseAdaptation\Precedence($this->semStack[$stackPos-(4-1)][0], $this->semStack[$stackPos-(4-1)][1], $this->semStack[$stackPos-(4-3)], $this->startAttributeStack[$stackPos-(4-1)] + $this->endAttributes);
            },
            283 => function ($stackPos) {
                 $this->semValue = new Stmt\TraitUseAdaptation\Alias($this->semStack[$stackPos-(5-1)][0], $this->semStack[$stackPos-(5-1)][1], $this->semStack[$stackPos-(5-3)], $this->semStack[$stackPos-(5-4)], $this->startAttributeStack[$stackPos-(5-1)] + $this->endAttributes);
            },
            284 => function ($stackPos) {
                 $this->semValue = new Stmt\TraitUseAdaptation\Alias($this->semStack[$stackPos-(4-1)][0], $this->semStack[$stackPos-(4-1)][1], $this->semStack[$stackPos-(4-3)], null, $this->startAttributeStack[$stackPos-(4-1)] + $this->endAttributes);
            },
            285 => function ($stackPos) {
                 $this->semValue = new Stmt\TraitUseAdaptation\Alias($this->semStack[$stackPos-(4-1)][0], $this->semStack[$stackPos-(4-1)][1], null, $this->semStack[$stackPos-(4-3)], $this->startAttributeStack[$stackPos-(4-1)] + $this->endAttributes);
            },
            286 => function ($stackPos) {
                 $this->semValue = new Stmt\TraitUseAdaptation\Alias($this->semStack[$stackPos-(4-1)][0], $this->semStack[$stackPos-(4-1)][1], null, $this->semStack[$stackPos-(4-3)], $this->startAttributeStack[$stackPos-(4-1)] + $this->endAttributes);
            },
            287 => function ($stackPos) {
                 $this->semValue = array($this->semStack[$stackPos-(3-1)], $this->semStack[$stackPos-(3-3)]);
            },
            288 => function ($stackPos) {
                 $this->semValue = $this->semStack[$stackPos-(1-1)];
            },
            289 => function ($stackPos) {
                 $this->semValue = array(null, $this->semStack[$stackPos-(1-1)]);
            },
            290 => function ($stackPos) {
                 $this->semValue = null;
            },
            291 => function ($stackPos) {
                 $this->semValue = $this->semStack[$stackPos-(1-1)];
            },
            292 => function ($stackPos) {
                 $this->semValue = $this->semStack[$stackPos-(1-1)];
            },
            293 => function ($stackPos) {
                 $this->semValue = 0;
            },
            294 => function ($stackPos) {
                 $this->semValue = 0;
            },
            295 => function ($stackPos) {
                 $this->semValue = $this->semStack[$stackPos-(1-1)];
            },
            296 => function ($stackPos) {
                 $this->semValue = $this->semStack[$stackPos-(1-1)];
            },
            297 => function ($stackPos) {
                 $this->checkModifier($this->semStack[$stackPos-(2-1)], $this->semStack[$stackPos-(2-2)], $stackPos-(2-2)); $this->semValue = $this->semStack[$stackPos-(2-1)] | $this->semStack[$stackPos-(2-2)];
            },
            298 => function ($stackPos) {
                 $this->semValue = Stmt\Class_::MODIFIER_PUBLIC;
            },
            299 => function ($stackPos) {
                 $this->semValue = Stmt\Class_::MODIFIER_PROTECTED;
            },
            300 => function ($stackPos) {
                 $this->semValue = Stmt\Class_::MODIFIER_PRIVATE;
            },
            301 => function ($stackPos) {
                 $this->semValue = Stmt\Class_::MODIFIER_STATIC;
            },
            302 => function ($stackPos) {
                 $this->semValue = Stmt\Class_::MODIFIER_ABSTRACT;
            },
            303 => function ($stackPos) {
                 $this->semValue = Stmt\Class_::MODIFIER_FINAL;
            },
            304 => function ($stackPos) {
                 $this->semValue = $this->semStack[$stackPos-(2-1)];
            },
            305 => function ($stackPos) {
                 $this->semValue = array($this->semStack[$stackPos-(1-1)]);
            },
            306 => function ($stackPos) {
                 $this->semStack[$stackPos-(3-1)][] = $this->semStack[$stackPos-(3-3)]; $this->semValue = $this->semStack[$stackPos-(3-1)];
            },
            307 => function ($stackPos) {
                 $this->semValue = new Node\VarLikeIdentifier(substr($this->semStack[$stackPos-(1-1)], 1), $this->startAttributeStack[$stackPos-(1-1)] + $this->endAttributes);
            },
            308 => function ($stackPos) {
                 $this->semValue = new Stmt\PropertyProperty($this->semStack[$stackPos-(1-1)], null, $this->startAttributeStack[$stackPos-(1-1)] + $this->endAttributes);
            },
            309 => function ($stackPos) {
                 $this->semValue = new Stmt\PropertyProperty($this->semStack[$stackPos-(3-1)], $this->semStack[$stackPos-(3-3)], $this->startAttributeStack[$stackPos-(3-1)] + $this->endAttributes);
            },
            310 => function ($stackPos) {
                 $this->semValue = $this->semStack[$stackPos-(2-1)];
            },
            311 => function ($stackPos) {
                 $this->semStack[$stackPos-(3-1)][] = $this->semStack[$stackPos-(3-3)]; $this->semValue = $this->semStack[$stackPos-(3-1)];
            },
            312 => function ($stackPos) {
                 $this->semValue = array($this->semStack[$stackPos-(1-1)]);
            },
            313 => function ($stackPos) {
                 $this->semValue = array();
            },
            314 => function ($stackPos) {
                 $this->semValue = $this->semStack[$stackPos-(1-1)];
            },
            315 => function ($stackPos) {
                 $this->semValue = $this->semStack[$stackPos-(1-1)];
            },
            316 => function ($stackPos) {
                 $this->semValue = new Expr\Assign($this->semStack[$stackPos-(3-1)], $this->semStack[$stackPos-(3-3)], $this->startAttributeStack[$stackPos-(3-1)] + $this->endAttributes);
            },
            317 => function ($stackPos) {
                 $this->semValue = new Expr\Assign($this->semStack[$stackPos-(3-1)], $this->semStack[$stackPos-(3-3)], $this->startAttributeStack[$stackPos-(3-1)] + $this->endAttributes);
            },
            318 => function ($stackPos) {
                 $this->semValue = new Expr\Assign($this->semStack[$stackPos-(3-1)], $this->semStack[$stackPos-(3-3)], $this->startAttributeStack[$stackPos-(3-1)] + $this->endAttributes);
            },
            319 => function ($stackPos) {
                 $this->semValue = new Expr\AssignRef($this->semStack[$stackPos-(4-1)], $this->semStack[$stackPos-(4-4)], $this->startAttributeStack[$stackPos-(4-1)] + $this->endAttributes);
            },
            320 => function ($stackPos) {
                 $this->semValue = $this->semStack[$stackPos-(1-1)];
            },
            321 => function ($stackPos) {
                 $this->semValue = new Expr\Clone_($this->semStack[$stackPos-(2-2)], $this->startAttributeStack[$stackPos-(2-1)] + $this->endAttributes);
            },
            322 => function ($stackPos) {
                 $this->semValue = new Expr\AssignOp\Plus($this->semStack[$stackPos-(3-1)], $this->semStack[$stackPos-(3-3)], $this->startAttributeStack[$stackPos-(3-1)] + $this->endAttributes);
            },
            323 => function ($stackPos) {
                 $this->semValue = new Expr\AssignOp\Minus($this->semStack[$stackPos-(3-1)], $this->semStack[$stackPos-(3-3)], $this->startAttributeStack[$stackPos-(3-1)] + $this->endAttributes);
            },
            324 => function ($stackPos) {
                 $this->semValue = new Expr\AssignOp\Mul($this->semStack[$stackPos-(3-1)], $this->semStack[$stackPos-(3-3)], $this->startAttributeStack[$stackPos-(3-1)] + $this->endAttributes);
            },
            325 => function ($stackPos) {
                 $this->semValue = new Expr\AssignOp\Div($this->semStack[$stackPos-(3-1)], $this->semStack[$stackPos-(3-3)], $this->startAttributeStack[$stackPos-(3-1)] + $this->endAttributes);
            },
            326 => function ($stackPos) {
                 $this->semValue = new Expr\AssignOp\Concat($this->semStack[$stackPos-(3-1)], $this->semStack[$stackPos-(3-3)], $this->startAttributeStack[$stackPos-(3-1)] + $this->endAttributes);
            },
            327 => function ($stackPos) {
                 $this->semValue = new Expr\AssignOp\Mod($this->semStack[$stackPos-(3-1)], $this->semStack[$stackPos-(3-3)], $this->startAttributeStack[$stackPos-(3-1)] + $this->endAttributes);
            },
            328 => function ($stackPos) {
                 $this->semValue = new Expr\AssignOp\BitwiseAnd($this->semStack[$stackPos-(3-1)], $this->semStack[$stackPos-(3-3)], $this->startAttributeStack[$stackPos-(3-1)] + $this->endAttributes);
            },
            329 => function ($stackPos) {
                 $this->semValue = new Expr\AssignOp\BitwiseOr($this->semStack[$stackPos-(3-1)], $this->semStack[$stackPos-(3-3)], $this->startAttributeStack[$stackPos-(3-1)] + $this->endAttributes);
            },
            330 => function ($stackPos) {
                 $this->semValue = new Expr\AssignOp\BitwiseXor($this->semStack[$stackPos-(3-1)], $this->semStack[$stackPos-(3-3)], $this->startAttributeStack[$stackPos-(3-1)] + $this->endAttributes);
            },
            331 => function ($stackPos) {
                 $this->semValue = new Expr\AssignOp\ShiftLeft($this->semStack[$stackPos-(3-1)], $this->semStack[$stackPos-(3-3)], $this->startAttributeStack[$stackPos-(3-1)] + $this->endAttributes);
            },
            332 => function ($stackPos) {
                 $this->semValue = new Expr\AssignOp\ShiftRight($this->semStack[$stackPos-(3-1)], $this->semStack[$stackPos-(3-3)], $this->startAttributeStack[$stackPos-(3-1)] + $this->endAttributes);
            },
            333 => function ($stackPos) {
                 $this->semValue = new Expr\AssignOp\Pow($this->semStack[$stackPos-(3-1)], $this->semStack[$stackPos-(3-3)], $this->startAttributeStack[$stackPos-(3-1)] + $this->endAttributes);
            },
            334 => function ($stackPos) {
                 $this->semValue = new Expr\AssignOp\Coalesce($this->semStack[$stackPos-(3-1)], $this->semStack[$stackPos-(3-3)], $this->startAttributeStack[$stackPos-(3-1)] + $this->endAttributes);
            },
            335 => function ($stackPos) {
                 $this->semValue = new Expr\PostInc($this->semStack[$stackPos-(2-1)], $this->startAttributeStack[$stackPos-(2-1)] + $this->endAttributes);
            },
            336 => function ($stackPos) {
                 $this->semValue = new Expr\PreInc($this->semStack[$stackPos-(2-2)], $this->startAttributeStack[$stackPos-(2-1)] + $this->endAttributes);
            },
            337 => function ($stackPos) {
                 $this->semValue = new Expr\PostDec($this->semStack[$stackPos-(2-1)], $this->startAttributeStack[$stackPos-(2-1)] + $this->endAttributes);
            },
            338 => function ($stackPos) {
                 $this->semValue = new Expr\PreDec($this->semStack[$stackPos-(2-2)], $this->startAttributeStack[$stackPos-(2-1)] + $this->endAttributes);
            },
            339 => function ($stackPos) {
                 $this->semValue = new Expr\BinaryOp\BooleanOr($this->semStack[$stackPos-(3-1)], $this->semStack[$stackPos-(3-3)], $this->startAttributeStack[$stackPos-(3-1)] + $this->endAttributes);
            },
            340 => function ($stackPos) {
                 $this->semValue = new Expr\BinaryOp\BooleanAnd($this->semStack[$stackPos-(3-1)], $this->semStack[$stackPos-(3-3)], $this->startAttributeStack[$stackPos-(3-1)] + $this->endAttributes);
            },
            341 => function ($stackPos) {
                 $this->semValue = new Expr\BinaryOp\LogicalOr($this->semStack[$stackPos-(3-1)], $this->semStack[$stackPos-(3-3)], $this->startAttributeStack[$stackPos-(3-1)] + $this->endAttributes);
            },
            342 => function ($stackPos) {
                 $this->semValue = new Expr\BinaryOp\LogicalAnd($this->semStack[$stackPos-(3-1)], $this->semStack[$stackPos-(3-3)], $this->startAttributeStack[$stackPos-(3-1)] + $this->endAttributes);
            },
            343 => function ($stackPos) {
                 $this->semValue = new Expr\BinaryOp\LogicalXor($this->semStack[$stackPos-(3-1)], $this->semStack[$stackPos-(3-3)], $this->startAttributeStack[$stackPos-(3-1)] + $this->endAttributes);
            },
            344 => function ($stackPos) {
                 $this->semValue = new Expr\BinaryOp\BitwiseOr($this->semStack[$stackPos-(3-1)], $this->semStack[$stackPos-(3-3)], $this->startAttributeStack[$stackPos-(3-1)] + $this->endAttributes);
            },
            345 => function ($stackPos) {
                 $this->semValue = new Expr\BinaryOp\BitwiseAnd($this->semStack[$stackPos-(3-1)], $this->semStack[$stackPos-(3-3)], $this->startAttributeStack[$stackPos-(3-1)] + $this->endAttributes);
            },
            346 => function ($stackPos) {
                 $this->semValue = new Expr\BinaryOp\BitwiseXor($this->semStack[$stackPos-(3-1)], $this->semStack[$stackPos-(3-3)], $this->startAttributeStack[$stackPos-(3-1)] + $this->endAttributes);
            },
            347 => function ($stackPos) {
                 $this->semValue = new Expr\BinaryOp\Concat($this->semStack[$stackPos-(3-1)], $this->semStack[$stackPos-(3-3)], $this->startAttributeStack[$stackPos-(3-1)] + $this->endAttributes);
            },
            348 => function ($stackPos) {
                 $this->semValue = new Expr\BinaryOp\Plus($this->semStack[$stackPos-(3-1)], $this->semStack[$stackPos-(3-3)], $this->startAttributeStack[$stackPos-(3-1)] + $this->endAttributes);
            },
            349 => function ($stackPos) {
                 $this->semValue = new Expr\BinaryOp\Minus($this->semStack[$stackPos-(3-1)], $this->semStack[$stackPos-(3-3)], $this->startAttributeStack[$stackPos-(3-1)] + $this->endAttributes);
            },
            350 => function ($stackPos) {
                 $this->semValue = new Expr\BinaryOp\Mul($this->semStack[$stackPos-(3-1)], $this->semStack[$stackPos-(3-3)], $this->startAttributeStack[$stackPos-(3-1)] + $this->endAttributes);
            },
            351 => function ($stackPos) {
                 $this->semValue = new Expr\BinaryOp\Div($this->semStack[$stackPos-(3-1)], $this->semStack[$stackPos-(3-3)], $this->startAttributeStack[$stackPos-(3-1)] + $this->endAttributes);
            },
            352 => function ($stackPos) {
                 $this->semValue = new Expr\BinaryOp\Mod($this->semStack[$stackPos-(3-1)], $this->semStack[$stackPos-(3-3)], $this->startAttributeStack[$stackPos-(3-1)] + $this->endAttributes);
            },
            353 => function ($stackPos) {
                 $this->semValue = new Expr\BinaryOp\ShiftLeft($this->semStack[$stackPos-(3-1)], $this->semStack[$stackPos-(3-3)], $this->startAttributeStack[$stackPos-(3-1)] + $this->endAttributes);
            },
            354 => function ($stackPos) {
                 $this->semValue = new Expr\BinaryOp\ShiftRight($this->semStack[$stackPos-(3-1)], $this->semStack[$stackPos-(3-3)], $this->startAttributeStack[$stackPos-(3-1)] + $this->endAttributes);
            },
            355 => function ($stackPos) {
                 $this->semValue = new Expr\BinaryOp\Pow($this->semStack[$stackPos-(3-1)], $this->semStack[$stackPos-(3-3)], $this->startAttributeStack[$stackPos-(3-1)] + $this->endAttributes);
            },
            356 => function ($stackPos) {
                 $this->semValue = new Expr\UnaryPlus($this->semStack[$stackPos-(2-2)], $this->startAttributeStack[$stackPos-(2-1)] + $this->endAttributes);
            },
            357 => function ($stackPos) {
                 $this->semValue = new Expr\UnaryMinus($this->semStack[$stackPos-(2-2)], $this->startAttributeStack[$stackPos-(2-1)] + $this->endAttributes);
            },
            358 => function ($stackPos) {
                 $this->semValue = new Expr\BooleanNot($this->semStack[$stackPos-(2-2)], $this->startAttributeStack[$stackPos-(2-1)] + $this->endAttributes);
            },
            359 => function ($stackPos) {
                 $this->semValue = new Expr\BitwiseNot($this->semStack[$stackPos-(2-2)], $this->startAttributeStack[$stackPos-(2-1)] + $this->endAttributes);
            },
            360 => function ($stackPos) {
                 $this->semValue = new Expr\BinaryOp\Identical($this->semStack[$stackPos-(3-1)], $this->semStack[$stackPos-(3-3)], $this->startAttributeStack[$stackPos-(3-1)] + $this->endAttributes);
            },
            361 => function ($stackPos) {
                 $this->semValue = new Expr\BinaryOp\NotIdentical($this->semStack[$stackPos-(3-1)], $this->semStack[$stackPos-(3-3)], $this->startAttributeStack[$stackPos-(3-1)] + $this->endAttributes);
            },
            362 => function ($stackPos) {
                 $this->semValue = new Expr\BinaryOp\Equal($this->semStack[$stackPos-(3-1)], $this->semStack[$stackPos-(3-3)], $this->startAttributeStack[$stackPos-(3-1)] + $this->endAttributes);
            },
            363 => function ($stackPos) {
                 $this->semValue = new Expr\BinaryOp\NotEqual($this->semStack[$stackPos-(3-1)], $this->semStack[$stackPos-(3-3)], $this->startAttributeStack[$stackPos-(3-1)] + $this->endAttributes);
            },
            364 => function ($stackPos) {
                 $this->semValue = new Expr\BinaryOp\Spaceship($this->semStack[$stackPos-(3-1)], $this->semStack[$stackPos-(3-3)], $this->startAttributeStack[$stackPos-(3-1)] + $this->endAttributes);
            },
            365 => function ($stackPos) {
                 $this->semValue = new Expr\BinaryOp\Smaller($this->semStack[$stackPos-(3-1)], $this->semStack[$stackPos-(3-3)], $this->startAttributeStack[$stackPos-(3-1)] + $this->endAttributes);
            },
            366 => function ($stackPos) {
                 $this->semValue = new Expr\BinaryOp\SmallerOrEqual($this->semStack[$stackPos-(3-1)], $this->semStack[$stackPos-(3-3)], $this->startAttributeStack[$stackPos-(3-1)] + $this->endAttributes);
            },
            367 => function ($stackPos) {
                 $this->semValue = new Expr\BinaryOp\Greater($this->semStack[$stackPos-(3-1)], $this->semStack[$stackPos-(3-3)], $this->startAttributeStack[$stackPos-(3-1)] + $this->endAttributes);
            },
            368 => function ($stackPos) {
                 $this->semValue = new Expr\BinaryOp\GreaterOrEqual($this->semStack[$stackPos-(3-1)], $this->semStack[$stackPos-(3-3)], $this->startAttributeStack[$stackPos-(3-1)] + $this->endAttributes);
            },
            369 => function ($stackPos) {
                 $this->semValue = new Expr\Instanceof_($this->semStack[$stackPos-(3-1)], $this->semStack[$stackPos-(3-3)], $this->startAttributeStack[$stackPos-(3-1)] + $this->endAttributes);
            },
            370 => function ($stackPos) {
                 $this->semValue = $this->semStack[$stackPos-(3-2)];
            },
            371 => function ($stackPos) {
                 $this->semValue = new Expr\Ternary($this->semStack[$stackPos-(5-1)], $this->semStack[$stackPos-(5-3)], $this->semStack[$stackPos-(5-5)], $this->startAttributeStack[$stackPos-(5-1)] + $this->endAttributes);
            },
            372 => function ($stackPos) {
                 $this->semValue = new Expr\Ternary($this->semStack[$stackPos-(4-1)], null, $this->semStack[$stackPos-(4-4)], $this->startAttributeStack[$stackPos-(4-1)] + $this->endAttributes);
            },
            373 => function ($stackPos) {
                 $this->semValue = new Expr\BinaryOp\Coalesce($this->semStack[$stackPos-(3-1)], $this->semStack[$stackPos-(3-3)], $this->startAttributeStack[$stackPos-(3-1)] + $this->endAttributes);
            },
            374 => function ($stackPos) {
                 $this->semValue = new Expr\Isset_($this->semStack[$stackPos-(4-3)], $this->startAttributeStack[$stackPos-(4-1)] + $this->endAttributes);
            },
            375 => function ($stackPos) {
                 $this->semValue = new Expr\Empty_($this->semStack[$stackPos-(4-3)], $this->startAttributeStack[$stackPos-(4-1)] + $this->endAttributes);
            },
            376 => function ($stackPos) {
                 $this->semValue = new Expr\Include_($this->semStack[$stackPos-(2-2)], Expr\Include_::TYPE_INCLUDE, $this->startAttributeStack[$stackPos-(2-1)] + $this->endAttributes);
            },
            377 => function ($stackPos) {
                 $this->semValue = new Expr\Include_($this->semStack[$stackPos-(2-2)], Expr\Include_::TYPE_INCLUDE_ONCE, $this->startAttributeStack[$stackPos-(2-1)] + $this->endAttributes);
            },
            378 => function ($stackPos) {
                 $this->semValue = new Expr\Eval_($this->semStack[$stackPos-(4-3)], $this->startAttributeStack[$stackPos-(4-1)] + $this->endAttributes);
            },
            379 => function ($stackPos) {
                 $this->semValue = new Expr\Include_($this->semStack[$stackPos-(2-2)], Expr\Include_::TYPE_REQUIRE, $this->startAttributeStack[$stackPos-(2-1)] + $this->endAttributes);
            },
            380 => function ($stackPos) {
                 $this->semValue = new Expr\Include_($this->semStack[$stackPos-(2-2)], Expr\Include_::TYPE_REQUIRE_ONCE, $this->startAttributeStack[$stackPos-(2-1)] + $this->endAttributes);
            },
            381 => function ($stackPos) {
                 $this->semValue = new Expr\Cast\Int_($this->semStack[$stackPos-(2-2)], $this->startAttributeStack[$stackPos-(2-1)] + $this->endAttributes);
            },
            382 => function ($stackPos) {
                 $attrs = $this->startAttributeStack[$stackPos-(2-1)] + $this->endAttributes;
            $attrs['kind'] = $this->getFloatCastKind($this->semStack[$stackPos-(2-1)]);
            $this->semValue = new Expr\Cast\Double($this->semStack[$stackPos-(2-2)], $attrs);
            },
            383 => function ($stackPos) {
                 $this->semValue = new Expr\Cast\String_($this->semStack[$stackPos-(2-2)], $this->startAttributeStack[$stackPos-(2-1)] + $this->endAttributes);
            },
            384 => function ($stackPos) {
                 $this->semValue = new Expr\Cast\Array_($this->semStack[$stackPos-(2-2)], $this->startAttributeStack[$stackPos-(2-1)] + $this->endAttributes);
            },
            385 => function ($stackPos) {
                 $this->semValue = new Expr\Cast\Object_($this->semStack[$stackPos-(2-2)], $this->startAttributeStack[$stackPos-(2-1)] + $this->endAttributes);
            },
            386 => function ($stackPos) {
                 $this->semValue = new Expr\Cast\Bool_($this->semStack[$stackPos-(2-2)], $this->startAttributeStack[$stackPos-(2-1)] + $this->endAttributes);
            },
            387 => function ($stackPos) {
                 $this->semValue = new Expr\Cast\Unset_($this->semStack[$stackPos-(2-2)], $this->startAttributeStack[$stackPos-(2-1)] + $this->endAttributes);
            },
            388 => function ($stackPos) {
                 $attrs = $this->startAttributeStack[$stackPos-(2-1)] + $this->endAttributes;
            $attrs['kind'] = strtolower($this->semStack[$stackPos-(2-1)]) === 'exit' ? Expr\Exit_::KIND_EXIT : Expr\Exit_::KIND_DIE;
            $this->semValue = new Expr\Exit_($this->semStack[$stackPos-(2-2)], $attrs);
            },
            389 => function ($stackPos) {
                 $this->semValue = new Expr\ErrorSuppress($this->semStack[$stackPos-(2-2)], $this->startAttributeStack[$stackPos-(2-1)] + $this->endAttributes);
            },
            390 => function ($stackPos) {
                 $this->semValue = $this->semStack[$stackPos-(1-1)];
            },
            391 => function ($stackPos) {
                 $this->semValue = new Expr\ShellExec($this->semStack[$stackPos-(3-2)], $this->startAttributeStack[$stackPos-(3-1)] + $this->endAttributes);
            },
            392 => function ($stackPos) {
                 $this->semValue = new Expr\Print_($this->semStack[$stackPos-(2-2)], $this->startAttributeStack[$stackPos-(2-1)] + $this->endAttributes);
            },
            393 => function ($stackPos) {
                 $this->semValue = new Expr\Yield_(null, null, $this->startAttributeStack[$stackPos-(1-1)] + $this->endAttributes);
            },
            394 => function ($stackPos) {
                 $this->semValue = new Expr\Yield_($this->semStack[$stackPos-(2-2)], null, $this->startAttributeStack[$stackPos-(2-1)] + $this->endAttributes);
            },
            395 => function ($stackPos) {
                 $this->semValue = new Expr\Yield_($this->semStack[$stackPos-(4-4)], $this->semStack[$stackPos-(4-2)], $this->startAttributeStack[$stackPos-(4-1)] + $this->endAttributes);
            },
            396 => function ($stackPos) {
                 $this->semValue = new Expr\YieldFrom($this->semStack[$stackPos-(2-2)], $this->startAttributeStack[$stackPos-(2-1)] + $this->endAttributes);
            },
            397 => function ($stackPos) {
                 $this->semValue = new Expr\Closure(['static' => false, 'byRef' => $this->semStack[$stackPos-(8-2)], 'params' => $this->semStack[$stackPos-(8-4)], 'uses' => $this->semStack[$stackPos-(8-6)], 'returnType' => $this->semStack[$stackPos-(8-7)], 'stmts' => $this->semStack[$stackPos-(8-8)]], $this->startAttributeStack[$stackPos-(8-1)] + $this->endAttributes);
            },
            398 => function ($stackPos) {
                 $this->semValue = new Expr\Closure(['static' => true, 'byRef' => $this->semStack[$stackPos-(9-3)], 'params' => $this->semStack[$stackPos-(9-5)], 'uses' => $this->semStack[$stackPos-(9-7)], 'returnType' => $this->semStack[$stackPos-(9-8)], 'stmts' => $this->semStack[$stackPos-(9-9)]], $this->startAttributeStack[$stackPos-(9-1)] + $this->endAttributes);
            },
            399 => function ($stackPos) {
                 $this->semValue = array(new Stmt\Class_(null, ['type' => 0, 'extends' => $this->semStack[$stackPos-(7-3)], 'implements' => $this->semStack[$stackPos-(7-4)], 'stmts' => $this->semStack[$stackPos-(7-6)]], $this->startAttributeStack[$stackPos-(7-1)] + $this->endAttributes), $this->semStack[$stackPos-(7-2)]);
            $this->checkClass($this->semValue[0], -1);
            },
            400 => function ($stackPos) {
                 $this->semValue = new Expr\New_($this->semStack[$stackPos-(3-2)], $this->semStack[$stackPos-(3-3)], $this->startAttributeStack[$stackPos-(3-1)] + $this->endAttributes);
            },
            401 => function ($stackPos) {
                 list($class, $ctorArgs) = $this->semStack[$stackPos-(2-2)]; $this->semValue = new Expr\New_($class, $ctorArgs, $this->startAttributeStack[$stackPos-(2-1)] + $this->endAttributes);
            },
            402 => function ($stackPos) {
                 $this->semValue = array();
            },
            403 => function ($stackPos) {
                 $this->semValue = $this->semStack[$stackPos-(4-3)];
            },
            404 => function ($stackPos) {
                 $this->semValue = $this->semStack[$stackPos-(2-1)];
            },
            405 => function ($stackPos) {
                 $this->semValue = array($this->semStack[$stackPos-(1-1)]);
            },
            406 => function ($stackPos) {
                 $this->semStack[$stackPos-(3-1)][] = $this->semStack[$stackPos-(3-3)]; $this->semValue = $this->semStack[$stackPos-(3-1)];
            },
            407 => function ($stackPos) {
                 $this->semValue = new Expr\ClosureUse($this->semStack[$stackPos-(2-2)], $this->semStack[$stackPos-(2-1)], $this->startAttributeStack[$stackPos-(2-1)] + $this->endAttributes);
            },
            408 => function ($stackPos) {
                 $this->semValue = new Expr\FuncCall($this->semStack[$stackPos-(2-1)], $this->semStack[$stackPos-(2-2)], $this->startAttributeStack[$stackPos-(2-1)] + $this->endAttributes);
            },
            409 => function ($stackPos) {
                 $this->semValue = new Expr\FuncCall($this->semStack[$stackPos-(2-1)], $this->semStack[$stackPos-(2-2)], $this->startAttributeStack[$stackPos-(2-1)] + $this->endAttributes);
            },
            410 => function ($stackPos) {
                 $this->semValue = new Expr\StaticCall($this->semStack[$stackPos-(4-1)], $this->semStack[$stackPos-(4-3)], $this->semStack[$stackPos-(4-4)], $this->startAttributeStack[$stackPos-(4-1)] + $this->endAttributes);
            },
            411 => function ($stackPos) {
                 $this->semValue = new Name($this->semStack[$stackPos-(1-1)], $this->startAttributeStack[$stackPos-(1-1)] + $this->endAttributes);
            },
            412 => function ($stackPos) {
                 $this->semValue = $this->semStack[$stackPos-(1-1)];
            },
            413 => function ($stackPos) {
                 $this->semValue = new Name($this->semStack[$stackPos-(1-1)], $this->startAttributeStack[$stackPos-(1-1)] + $this->endAttributes);
            },
            414 => function ($stackPos) {
                 $this->semValue = new Name\FullyQualified($this->semStack[$stackPos-(2-2)], $this->startAttributeStack[$stackPos-(2-1)] + $this->endAttributes);
            },
            415 => function ($stackPos) {
                 $this->semValue = new Name\Relative($this->semStack[$stackPos-(3-3)], $this->startAttributeStack[$stackPos-(3-1)] + $this->endAttributes);
            },
            416 => function ($stackPos) {
                 $this->semValue = $this->semStack[$stackPos-(1-1)];
            },
            417 => function ($stackPos) {
                 $this->semValue = $this->semStack[$stackPos-(1-1)];
            },
            418 => function ($stackPos) {
                 $this->semValue = new Expr\Error($this->startAttributeStack[$stackPos-(1-1)] + $this->endAttributes); $this->errorState = 2;
            },
            419 => function ($stackPos) {
                 $this->semValue = $this->semStack[$stackPos-(1-1)];
            },
            420 => function ($stackPos) {
                 $this->semValue = $this->semStack[$stackPos-(1-1)];
            },
            421 => function ($stackPos) {
                 $this->semValue = null;
            },
            422 => function ($stackPos) {
                 $this->semValue = $this->semStack[$stackPos-(3-2)];
            },
            423 => function ($stackPos) {
                 $this->semValue = array();
            },
            424 => function ($stackPos) {
                 $this->semValue = array(new Scalar\EncapsedStringPart(Scalar\String_::parseEscapeSequences($this->semStack[$stackPos-(1-1)], '`'), $this->startAttributeStack[$stackPos-(1-1)] + $this->endAttributes));
            },
            425 => function ($stackPos) {
                 foreach ($this->semStack[$stackPos-(1-1)] as $s) { if ($s instanceof Node\Scalar\EncapsedStringPart) { $s->value = Node\Scalar\String_::parseEscapeSequences($s->value, '`', true); } }; $this->semValue = $this->semStack[$stackPos-(1-1)];
            },
            426 => function ($stackPos) {
                 $this->semValue = array();
            },
            427 => function ($stackPos) {
                 $this->semValue = $this->semStack[$stackPos-(1-1)];
            },
            428 => function ($stackPos) {
                 $this->semValue = new Expr\ConstFetch($this->semStack[$stackPos-(1-1)], $this->startAttributeStack[$stackPos-(1-1)] + $this->endAttributes);
            },
            429 => function ($stackPos) {
                 $this->semValue = new Expr\ClassConstFetch($this->semStack[$stackPos-(3-1)], $this->semStack[$stackPos-(3-3)], $this->startAttributeStack[$stackPos-(3-1)] + $this->endAttributes);
            },
            430 => function ($stackPos) {
                 $this->semValue = new Expr\ClassConstFetch($this->semStack[$stackPos-(3-1)], new Expr\Error($this->startAttributeStack[$stackPos-(3-3)] + $this->endAttributeStack[$stackPos-(3-3)]), $this->startAttributeStack[$stackPos-(3-1)] + $this->endAttributes); $this->errorState = 2;
            },
            431 => function ($stackPos) {
                 $attrs = $this->startAttributeStack[$stackPos-(3-1)] + $this->endAttributes; $attrs['kind'] = Expr\Array_::KIND_SHORT;
            $this->semValue = new Expr\Array_($this->semStack[$stackPos-(3-2)], $attrs);
            },
            432 => function ($stackPos) {
                 $attrs = $this->startAttributeStack[$stackPos-(4-1)] + $this->endAttributes; $attrs['kind'] = Expr\Array_::KIND_LONG;
            $this->semValue = new Expr\Array_($this->semStack[$stackPos-(4-3)], $attrs);
            },
            433 => function ($stackPos) {
                 $this->semValue = $this->semStack[$stackPos-(1-1)];
            },
            434 => function ($stackPos) {
                 $attrs = $this->startAttributeStack[$stackPos-(1-1)] + $this->endAttributes; $attrs['kind'] = ($this->semStack[$stackPos-(1-1)][0] === "'" || ($this->semStack[$stackPos-(1-1)][1] === "'" && ($this->semStack[$stackPos-(1-1)][0] === 'b' || $this->semStack[$stackPos-(1-1)][0] === 'B')) ? Scalar\String_::KIND_SINGLE_QUOTED : Scalar\String_::KIND_DOUBLE_QUOTED);
            $this->semValue = new Scalar\String_(Scalar\String_::parse($this->semStack[$stackPos-(1-1)]), $attrs);
            },
            435 => function ($stackPos) {
                 $this->semValue = $this->parseLNumber($this->semStack[$stackPos-(1-1)], $this->startAttributeStack[$stackPos-(1-1)] + $this->endAttributes);
            },
            436 => function ($stackPos) {
                 $this->semValue = new Scalar\DNumber(Scalar\DNumber::parse($this->semStack[$stackPos-(1-1)]), $this->startAttributeStack[$stackPos-(1-1)] + $this->endAttributes);
            },
            437 => function ($stackPos) {
                 $this->semValue = new Scalar\MagicConst\Line($this->startAttributeStack[$stackPos-(1-1)] + $this->endAttributes);
            },
            438 => function ($stackPos) {
                 $this->semValue = new Scalar\MagicConst\File($this->startAttributeStack[$stackPos-(1-1)] + $this->endAttributes);
            },
            439 => function ($stackPos) {
                 $this->semValue = new Scalar\MagicConst\Dir($this->startAttributeStack[$stackPos-(1-1)] + $this->endAttributes);
            },
            440 => function ($stackPos) {
                 $this->semValue = new Scalar\MagicConst\Class_($this->startAttributeStack[$stackPos-(1-1)] + $this->endAttributes);
            },
            441 => function ($stackPos) {
                 $this->semValue = new Scalar\MagicConst\Trait_($this->startAttributeStack[$stackPos-(1-1)] + $this->endAttributes);
            },
            442 => function ($stackPos) {
                 $this->semValue = new Scalar\MagicConst\Method($this->startAttributeStack[$stackPos-(1-1)] + $this->endAttributes);
            },
            443 => function ($stackPos) {
                 $this->semValue = new Scalar\MagicConst\Function_($this->startAttributeStack[$stackPos-(1-1)] + $this->endAttributes);
            },
            444 => function ($stackPos) {
                 $this->semValue = new Scalar\MagicConst\Namespace_($this->startAttributeStack[$stackPos-(1-1)] + $this->endAttributes);
            },
            445 => function ($stackPos) {
                 $this->semValue = $this->semStack[$stackPos-(1-1)];
            },
            446 => function ($stackPos) {
                 $this->semValue = $this->semStack[$stackPos-(1-1)];
            },
            447 => function ($stackPos) {
                 $this->semValue = $this->parseDocString($this->semStack[$stackPos-(3-1)], $this->semStack[$stackPos-(3-2)], $this->semStack[$stackPos-(3-3)], $this->startAttributeStack[$stackPos-(3-1)] + $this->endAttributes, $this->startAttributeStack[$stackPos-(3-3)] + $this->endAttributeStack[$stackPos-(3-3)], true);
            },
            448 => function ($stackPos) {
                 $this->semValue = $this->parseDocString($this->semStack[$stackPos-(2-1)], '', $this->semStack[$stackPos-(2-2)], $this->startAttributeStack[$stackPos-(2-1)] + $this->endAttributes, $this->startAttributeStack[$stackPos-(2-2)] + $this->endAttributeStack[$stackPos-(2-2)], true);
            },
            449 => function ($stackPos) {
                 $attrs = $this->startAttributeStack[$stackPos-(3-1)] + $this->endAttributes; $attrs['kind'] = Scalar\String_::KIND_DOUBLE_QUOTED;
            foreach ($this->semStack[$stackPos-(3-2)] as $s) { if ($s instanceof Node\Scalar\EncapsedStringPart) { $s->value = Node\Scalar\String_::parseEscapeSequences($s->value, '"', true); } }; $this->semValue = new Scalar\Encapsed($this->semStack[$stackPos-(3-2)], $attrs);
            },
            450 => function ($stackPos) {
                 $this->semValue = $this->parseDocString($this->semStack[$stackPos-(3-1)], $this->semStack[$stackPos-(3-2)], $this->semStack[$stackPos-(3-3)], $this->startAttributeStack[$stackPos-(3-1)] + $this->endAttributes, $this->startAttributeStack[$stackPos-(3-3)] + $this->endAttributeStack[$stackPos-(3-3)], true);
            },
            451 => function ($stackPos) {
                 $this->semValue = null;
            },
            452 => function ($stackPos) {
                 $this->semValue = $this->semStack[$stackPos-(1-1)];
            },
            453 => function ($stackPos) {
                 $this->semValue = $this->semStack[$stackPos-(1-1)];
            },
            454 => function ($stackPos) {
                 $this->semValue = $this->semStack[$stackPos-(3-2)];
            },
            455 => function ($stackPos) {
                 $this->semValue = $this->semStack[$stackPos-(1-1)];
            },
            456 => function ($stackPos) {
                 $this->semValue = $this->semStack[$stackPos-(1-1)];
            },
            457 => function ($stackPos) {
                 $this->semValue = $this->semStack[$stackPos-(3-2)];
            },
            458 => function ($stackPos) {
                 $this->semValue = $this->semStack[$stackPos-(1-1)];
            },
            459 => function ($stackPos) {
                 $this->semValue = new Expr\Variable($this->semStack[$stackPos-(1-1)], $this->startAttributeStack[$stackPos-(1-1)] + $this->endAttributes);
            },
            460 => function ($stackPos) {
                 $this->semValue = new Expr\ArrayDimFetch($this->semStack[$stackPos-(4-1)], $this->semStack[$stackPos-(4-3)], $this->startAttributeStack[$stackPos-(4-1)] + $this->endAttributes);
            },
            461 => function ($stackPos) {
                 $this->semValue = new Expr\ArrayDimFetch($this->semStack[$stackPos-(4-1)], $this->semStack[$stackPos-(4-3)], $this->startAttributeStack[$stackPos-(4-1)] + $this->endAttributes);
            },
            462 => function ($stackPos) {
                 $this->semValue = new Expr\ArrayDimFetch($this->semStack[$stackPos-(4-1)], $this->semStack[$stackPos-(4-3)], $this->startAttributeStack[$stackPos-(4-1)] + $this->endAttributes);
            },
            463 => function ($stackPos) {
                 $this->semValue = $this->semStack[$stackPos-(1-1)];
            },
            464 => function ($stackPos) {
                 $this->semValue = new Expr\MethodCall($this->semStack[$stackPos-(4-1)], $this->semStack[$stackPos-(4-3)], $this->semStack[$stackPos-(4-4)], $this->startAttributeStack[$stackPos-(4-1)] + $this->endAttributes);
            },
            465 => function ($stackPos) {
                 $this->semValue = $this->semStack[$stackPos-(1-1)];
            },
            466 => function ($stackPos) {
                 $this->semValue = $this->semStack[$stackPos-(1-1)];
            },
            467 => function ($stackPos) {
                 $this->semValue = new Expr\PropertyFetch($this->semStack[$stackPos-(3-1)], $this->semStack[$stackPos-(3-3)], $this->startAttributeStack[$stackPos-(3-1)] + $this->endAttributes);
            },
            468 => function ($stackPos) {
                 $this->semValue = substr($this->semStack[$stackPos-(1-1)], 1);
            },
            469 => function ($stackPos) {
                 $this->semValue = $this->semStack[$stackPos-(4-3)];
            },
            470 => function ($stackPos) {
                 $this->semValue = new Expr\Variable($this->semStack[$stackPos-(2-2)], $this->startAttributeStack[$stackPos-(2-1)] + $this->endAttributes);
            },
            471 => function ($stackPos) {
                 $this->semValue = new Expr\Error($this->startAttributeStack[$stackPos-(2-1)] + $this->endAttributes); $this->errorState = 2;
            },
            472 => function ($stackPos) {
                 $var = $this->semStack[$stackPos-(1-1)]; $this->semValue = \is_string($var) ? new Node\VarLikeIdentifier($var, $this->startAttributeStack[$stackPos-(1-1)] + $this->endAttributes) : $var;
            },
            473 => function ($stackPos) {
                 $this->semValue = new Expr\StaticPropertyFetch($this->semStack[$stackPos-(3-1)], $this->semStack[$stackPos-(3-3)], $this->startAttributeStack[$stackPos-(3-1)] + $this->endAttributes);
            },
            474 => function ($stackPos) {
                 $this->semValue = new Expr\Variable($this->semStack[$stackPos-(1-1)], $this->startAttributeStack[$stackPos-(1-1)] + $this->endAttributes);
            },
            475 => function ($stackPos) {
                 $this->semValue = new Expr\ArrayDimFetch($this->semStack[$stackPos-(4-1)], $this->semStack[$stackPos-(4-3)], $this->startAttributeStack[$stackPos-(4-1)] + $this->endAttributes);
            },
            476 => function ($stackPos) {
                 $this->semValue = new Expr\ArrayDimFetch($this->semStack[$stackPos-(4-1)], $this->semStack[$stackPos-(4-3)], $this->startAttributeStack[$stackPos-(4-1)] + $this->endAttributes);
            },
            477 => function ($stackPos) {
                 $this->semValue = new Expr\PropertyFetch($this->semStack[$stackPos-(3-1)], $this->semStack[$stackPos-(3-3)], $this->startAttributeStack[$stackPos-(3-1)] + $this->endAttributes);
            },
            478 => function ($stackPos) {
                 $this->semValue = new Expr\StaticPropertyFetch($this->semStack[$stackPos-(3-1)], $this->semStack[$stackPos-(3-3)], $this->startAttributeStack[$stackPos-(3-1)] + $this->endAttributes);
            },
            479 => function ($stackPos) {
                 $this->semValue = new Expr\StaticPropertyFetch($this->semStack[$stackPos-(3-1)], $this->semStack[$stackPos-(3-3)], $this->startAttributeStack[$stackPos-(3-1)] + $this->endAttributes);
            },
            480 => function ($stackPos) {
                 $this->semValue = $this->semStack[$stackPos-(1-1)];
            },
            481 => function ($stackPos) {
                 $this->semValue = $this->semStack[$stackPos-(3-2)];
            },
            482 => function ($stackPos) {
                 $this->semValue = new Expr\Variable($this->semStack[$stackPos-(1-1)], $this->startAttributeStack[$stackPos-(1-1)] + $this->endAttributes);
            },
            483 => function ($stackPos) {
                 $this->semValue = $this->semStack[$stackPos-(1-1)];
            },
            484 => function ($stackPos) {
                 $this->semValue = $this->semStack[$stackPos-(3-2)];
            },
            485 => function ($stackPos) {
                 $this->semValue = new Expr\Variable($this->semStack[$stackPos-(1-1)], $this->startAttributeStack[$stackPos-(1-1)] + $this->endAttributes);
            },
            486 => function ($stackPos) {
                 $this->semValue = new Expr\Error($this->startAttributeStack[$stackPos-(1-1)] + $this->endAttributes); $this->errorState = 2;
            },
            487 => function ($stackPos) {
                 $this->semValue = new Expr\List_($this->semStack[$stackPos-(4-3)], $this->startAttributeStack[$stackPos-(4-1)] + $this->endAttributes);
            },
            488 => function ($stackPos) {
                 $this->semStack[$stackPos-(3-1)][] = $this->semStack[$stackPos-(3-3)]; $this->semValue = $this->semStack[$stackPos-(3-1)];
            },
            489 => function ($stackPos) {
                 $this->semValue = array($this->semStack[$stackPos-(1-1)]);
            },
            490 => function ($stackPos) {
                 $this->semValue = new Expr\ArrayItem($this->semStack[$stackPos-(1-1)], null, false, $this->startAttributeStack[$stackPos-(1-1)] + $this->endAttributes);
            },
            491 => function ($stackPos) {
                 $this->semValue = new Expr\ArrayItem($this->semStack[$stackPos-(2-2)], null, true, $this->startAttributeStack[$stackPos-(2-1)] + $this->endAttributes);
            },
            492 => function ($stackPos) {
                 $this->semValue = new Expr\ArrayItem($this->semStack[$stackPos-(1-1)], null, false, $this->startAttributeStack[$stackPos-(1-1)] + $this->endAttributes);
            },
            493 => function ($stackPos) {
                 $this->semValue = new Expr\ArrayItem($this->semStack[$stackPos-(3-3)], $this->semStack[$stackPos-(3-1)], false, $this->startAttributeStack[$stackPos-(3-1)] + $this->endAttributes);
            },
            494 => function ($stackPos) {
                 $this->semValue = new Expr\ArrayItem($this->semStack[$stackPos-(4-4)], $this->semStack[$stackPos-(4-1)], true, $this->startAttributeStack[$stackPos-(4-1)] + $this->endAttributes);
            },
            495 => function ($stackPos) {
                 $this->semValue = new Expr\ArrayItem($this->semStack[$stackPos-(3-3)], $this->semStack[$stackPos-(3-1)], false, $this->startAttributeStack[$stackPos-(3-1)] + $this->endAttributes);
            },
            496 => function ($stackPos) {
                 $this->semValue = null;
            },
            497 => function ($stackPos) {
                 $this->semValue = $this->semStack[$stackPos-(1-1)]; $end = count($this->semValue)-1; if ($this->semValue[$end] === null) array_pop($this->semValue);
            },
            498 => function ($stackPos) {
                $this->semValue = $this->semStack[$stackPos];
            },
            499 => function ($stackPos) {
                 /* do nothing -- prevent default action of $$=$this->semStack[$1]. See $551. */
            },
            500 => function ($stackPos) {
                 $this->semStack[$stackPos-(3-1)][] = $this->semStack[$stackPos-(3-3)]; $this->semValue = $this->semStack[$stackPos-(3-1)];
            },
            501 => function ($stackPos) {
                 $this->semValue = array($this->semStack[$stackPos-(1-1)]);
            },
            502 => function ($stackPos) {
                 $this->semValue = new Expr\ArrayItem($this->semStack[$stackPos-(3-3)], $this->semStack[$stackPos-(3-1)], false, $this->startAttributeStack[$stackPos-(3-1)] + $this->endAttributes);
            },
            503 => function ($stackPos) {
                 $this->semValue = new Expr\ArrayItem($this->semStack[$stackPos-(1-1)], null, false, $this->startAttributeStack[$stackPos-(1-1)] + $this->endAttributes);
            },
            504 => function ($stackPos) {
                 $this->semValue = new Expr\ArrayItem($this->semStack[$stackPos-(4-4)], $this->semStack[$stackPos-(4-1)], true, $this->startAttributeStack[$stackPos-(4-1)] + $this->endAttributes);
            },
            505 => function ($stackPos) {
                 $this->semValue = new Expr\ArrayItem($this->semStack[$stackPos-(2-2)], null, true, $this->startAttributeStack[$stackPos-(2-1)] + $this->endAttributes);
            },
            506 => function ($stackPos) {
                 $this->semValue = null;
            },
            507 => function ($stackPos) {
                 $this->semStack[$stackPos-(2-1)][] = $this->semStack[$stackPos-(2-2)]; $this->semValue = $this->semStack[$stackPos-(2-1)];
            },
            508 => function ($stackPos) {
                 $this->semStack[$stackPos-(2-1)][] = $this->semStack[$stackPos-(2-2)]; $this->semValue = $this->semStack[$stackPos-(2-1)];
            },
            509 => function ($stackPos) {
                 $this->semValue = array($this->semStack[$stackPos-(1-1)]);
            },
            510 => function ($stackPos) {
                 $this->semValue = array($this->semStack[$stackPos-(2-1)], $this->semStack[$stackPos-(2-2)]);
            },
            511 => function ($stackPos) {
                 $this->semValue = new Scalar\EncapsedStringPart($this->semStack[$stackPos-(1-1)], $this->startAttributeStack[$stackPos-(1-1)] + $this->endAttributes);
            },
            512 => function ($stackPos) {
                 $this->semValue = new Expr\Variable($this->semStack[$stackPos-(1-1)], $this->startAttributeStack[$stackPos-(1-1)] + $this->endAttributes);
            },
            513 => function ($stackPos) {
                 $this->semValue = $this->semStack[$stackPos-(1-1)];
            },
            514 => function ($stackPos) {
                 $this->semValue = new Expr\ArrayDimFetch($this->semStack[$stackPos-(4-1)], $this->semStack[$stackPos-(4-3)], $this->startAttributeStack[$stackPos-(4-1)] + $this->endAttributes);
            },
            515 => function ($stackPos) {
                 $this->semValue = new Expr\PropertyFetch($this->semStack[$stackPos-(3-1)], $this->semStack[$stackPos-(3-3)], $this->startAttributeStack[$stackPos-(3-1)] + $this->endAttributes);
            },
            516 => function ($stackPos) {
                 $this->semValue = new Expr\Variable($this->semStack[$stackPos-(3-2)], $this->startAttributeStack[$stackPos-(3-1)] + $this->endAttributes);
            },
            517 => function ($stackPos) {
                 $this->semValue = new Expr\Variable($this->semStack[$stackPos-(3-2)], $this->startAttributeStack[$stackPos-(3-1)] + $this->endAttributes);
            },
            518 => function ($stackPos) {
                 $this->semValue = new Expr\ArrayDimFetch($this->semStack[$stackPos-(6-2)], $this->semStack[$stackPos-(6-4)], $this->startAttributeStack[$stackPos-(6-1)] + $this->endAttributes);
            },
            519 => function ($stackPos) {
                 $this->semValue = $this->semStack[$stackPos-(3-2)];
            },
            520 => function ($stackPos) {
                 $this->semValue = new Scalar\String_($this->semStack[$stackPos-(1-1)], $this->startAttributeStack[$stackPos-(1-1)] + $this->endAttributes);
            },
            521 => function ($stackPos) {
                 $this->semValue = $this->parseNumString($this->semStack[$stackPos-(1-1)], $this->startAttributeStack[$stackPos-(1-1)] + $this->endAttributes);
            },
            522 => function ($stackPos) {
                 $this->semValue = $this->parseNumString('-' . $this->semStack[$stackPos-(2-2)], $this->startAttributeStack[$stackPos-(2-1)] + $this->endAttributes);
            },
            523 => function ($stackPos) {
                 $this->semValue = $this->semStack[$stackPos-(1-1)];
            },
        ];
    }
}
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                     <?php

namespace PhpParser\Parser;

/* GENERATED file based on grammar/tokens.y */
final class Tokens
{
    const YYERRTOK = 256;
    const T_INCLUDE = 257;
    const T_INCLUDE_ONCE = 258;
    const T_EVAL = 259;
    const T_REQUIRE = 260;
    const T_REQUIRE_ONCE = 261;
    const T_LOGICAL_OR = 262;
    const T_LOGICAL_XOR = 263;
    const T_LOGICAL_AND = 264;
    const T_PRINT = 265;
    const T_YIELD = 266;
    const T_DOUBLE_ARROW = 267;
    const T_YIELD_FROM = 268;
    const T_PLUS_EQUAL = 269;
    const T_MINUS_EQUAL = 270;
    const T_MUL_EQUAL = 271;
    const T_DIV_EQUAL = 272;
    const T_CONCAT_EQUAL = 273;
    const T_MOD_EQUAL = 274;
    const T_AND_EQUAL = 275;
    const T_OR_EQUAL = 276;
    const T_XOR_EQUAL = 277;
    const T_SL_EQUAL = 278;
    const T_SR_EQUAL = 279;
    const T_POW_EQUAL = 280;
    const T_COALESCE_EQUAL = 281;
    const T_COALESCE = 282;
    const T_BOOLEAN_OR = 283;
    const T_BOOLEAN_AND = 284;
    const T_IS_EQUAL = 285;
    const T_IS_NOT_EQUAL = 286;
    const T_IS_IDENTICAL = 287;
    const T_IS_NOT_IDENTICAL = 288;
    const T_SPACESHIP = 289;
    const T_IS_SMALLER_OR_EQUAL = 290;
    const T_IS_GREATER_OR_EQUAL = 291;
    const T_SL = 292;
    const T_SR = 293;
    const T_INSTANCEOF = 294;
    const T_INC = 295;
    const T_DEC = 296;
    const T_INT_CAST = 297;
    const T_DOUBLE_CAST = 298;
    const T_STRING_CAST = 299;
    const T_ARRAY_CAST = 300;
    const T_OBJECT_CAST = 301;
    const T_BOOL_CAST = 302;
    const T_UNSET_CAST = 303;
    const T_POW = 304;
    const T_NEW = 305;
    const T_CLONE = 306;
    const T_EXIT = 307;
    const T_IF = 308;
    const T_ELSEIF = 309;
    const T_ELSE = 310;
    const T_ENDIF = 311;
    const T_LNUMBER = 312;
    const T_DNUMBER = 313;
    const T_STRING = 314;
    const T_STRING_VARNAME = 315;
    const T_VARIABLE = 316;
    const T_NUM_STRING = 317;
    const T_INLINE_HTML = 318;
    const T_CHARACTER = 319;
    const T_BAD_CHARACTER = 320;
    const T_ENCAPSED_AND_WHITESPACE = 321;
    const T_CONSTANT_ENCAPSED_STRING = 322;
    const T_ECHO = 323;
    const T_DO = 324;
    const T_WHILE = 325;
    const T_ENDWHILE = 326;
    const T_FOR = 327;
    const T_ENDFOR = 328;
    const T_FOREACH = 329;
    const T_ENDFOREACH = 330;
    const T_DECLARE = 331;
    const T_ENDDECLARE = 332;
    const T_AS = 333;
    const T_SWITCH = 334;
    const T_ENDSWITCH = 335;
    const T_CASE = 336;
    const T_DEFAULT = 337;
    const T_BREAK = 338;
    const T_CONTINUE = 339;
    const T_GOTO = 340;
    const T_FUNCTION = 341;
    const T_CONST = 342;
    const T_RETURN = 343;
    const T_TRY = 344;
    const T_CATCH = 345;
    const T_FINALLY = 346;
    const T_THROW = 347;
    const T_USE = 348;
    const T_INSTEADOF = 349;
    const T_GLOBAL = 350;
    const T_STATIC = 351;
    const T_ABSTRACT = 352;
    const T_FINAL = 353;
    const T_PRIVATE = 354;
    const T_PROTECTED = 355;
    const T_PUBLIC = 356;
    const T_VAR = 357;
    const T_UNSET = 358;
    const T_ISSET = 359;
    const T_EMPTY = 360;
    const T_HALT_COMPILER = 361;
    const T_CLASS = 362;
    const T_TRAIT = 363;
    const T_INTERFACE = 364;
    const T_EXTENDS = 365;
    const T_IMPLEMENTS = 366;
    const T_OBJECT_OPERATOR = 367;
    const T_LIST = 368;
    const T_ARRAY = 369;
    const T_CALLABLE = 370;
    const T_CLASS_C = 371;
    const T_TRAIT_C = 372;
    const T_METHOD_C = 373;
    const T_FUNC_C = 374;
    const T_LINE = 375;
    const T_FILE = 376;
    const T_COMMENT = 377;
    const T_DOC_COMMENT = 378;
    const T_OPEN_TAG = 379;
    const T_OPEN_TAG_WITH_ECHO = 380;
    const T_CLOSE_TAG = 381;
    const T_WHITESPACE = 382;
    const T_START_HEREDOC = 383;
    const T_END_HEREDOC = 384;
    const T_DOLLAR_OPEN_CURLY_BRACES = 385;
    const T_CURLY_OPEN = 386;
    const T_PAAMAYIM_NEKUDOTAYIM = 387;
    const T_NAMESPACE = 388;
    const T_NS_C = 389;
    const T_DIR = 390;
    const T_NS_SEPARATOR = 391;
    const T_ELLIPSIS = 392;
}
                                                                                                                      <?php declare(strict_types=1);

namespace PhpParser\PrettyPrinter;

use PhpParser\Node;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\AssignOp;
use PhpParser\Node\Expr\BinaryOp;
use PhpParser\Node\Expr\Cast;
use PhpParser\Node\Name;
use PhpParser\Node\Scalar;
use PhpParser\Node\Scalar\MagicConst;
use PhpParser\Node\Stmt;
use PhpParser\PrettyPrinterAbstract;

class Standard extends PrettyPrinterAbstract
{
    // Special nodes

    protected function pParam(Node\Param $node) {
        return ($node->type ? $this->p($node->type) . ' ' : '')
             . ($node->byRef ? '&' : '')
             . ($node->variadic ? '...' : '')
             . $this->p($node->var)
             . ($node->default ? ' = ' . $this->p($node->default) : '');
    }

    protected function pArg(Node\Arg $node) {
        return ($node->byRef ? '&' : '') . ($node->unpack ? '...' : '') . $this->p($node->value);
    }

    protected function pConst(Node\Const_ $node) {
        return $node->name . ' = ' . $this->p($node->value);
    }

    protected function pNullableType(Node\NullableType $node) {
        return '?' . $this->p($node->type);
    }

    protected function pIdentifier(Node\Identifier $node) {
        return $node->name;
    }

    protected function pVarLikeIdentifier(Node\VarLikeIdentifier $node) {
        return '$' . $node->name;
    }

    // Names

    protected function pName(Name $node) {
        return implode('\\', $node->parts);
    }

    protected function pName_FullyQualified(Name\FullyQualified $node) {
        return '\\' . implode('\\', $node->parts);
    }

    protected function pName_Relative(Name\Relative $node) {
        return 'namespace\\' . implode('\\', $node->parts);
    }

    // Magic Constants

    protected function pScalar_MagicConst_Class(MagicConst\Class_ $node) {
        return '__CLASS__';
    }

    protected function pScalar_MagicConst_Dir(MagicConst\Dir $node) {
        return '__DIR__';
    }

    protected function pScalar_MagicConst_File(MagicConst\File $node) {
        return '__FILE__';
    }

    protected function pScalar_MagicConst_Function(MagicConst\Function_ $node) {
        return '__FUNCTION__';
    }

    protected function pScalar_MagicConst_Line(MagicConst\Line $node) {
        return '__LINE__';
    }

    protected function pScalar_MagicConst_Method(MagicConst\Method $node) {
        return '__METHOD__';
    }

    protected function pScalar_MagicConst_Namespace(MagicConst\Namespace_ $node) {
        return '__NAMESPACE__';
    }

    protected function pScalar_MagicConst_Trait(MagicConst\Trait_ $node) {
        return '__TRAIT__';
    }

    // Scalars

    protected function pScalar_String(Scalar\String_ $node) {
        $kind = $node->getAttribute('kind', Scalar\String_::KIND_SINGLE_QUOTED);
        switch ($kind) {
            case Scalar\String_::KIND_NOWDOC:
                $label = $node->getAttribute('docLabel');
                if ($label && !$this->containsEndLabel($node->value, $label)) {
                    if ($node->value === '') {
                        return "<<<'$label'\n$label" . $this->docStringEndToken;
                    }

                    return "<<<'$label'\n$node->value\n$label"
                         . $this->docStringEndToken;
                }
                /* break missing intentionally */
            case Scalar\String_::KIND_SINGLE_QUOTED:
                return $this->pSingleQuotedString($node->value);
            case Scalar\String_::KIND_HEREDOC:
                $label = $node->getAttribute('docLabel');
                if ($label && !$this->containsEndLabel($node->value, $label)) {
                    if ($node->value === '') {
                        return "<<<$label\n$label" . $this->docStringEndToken;
                    }

                    $escaped = $this->escapeString($node->value, null);
                    return "<<<$label\n" . $escaped . "\n$label"
                         . $this->docStringEndToken;
                }
            /* break missing intentionally */
            case Scalar\String_::KIND_DOUBLE_QUOTED:
                return '"' . $this->escapeString($node->value, '"') . '"';
        }
        throw new \Exception('Invalid string kind');
    }

    protected function pScalar_Encapsed(Scalar\Encapsed $node) {
        if ($node->getAttribute('kind') === Scalar\String_::KIND_HEREDOC) {
            $label = $node->getAttribute('docLabel');
            if ($label && !$this->encapsedContainsEndLabel($node->parts, $label)) {
                if (count($node->parts) === 1
                    && $node->parts[0] instanceof Scalar\EncapsedStringPart
                    && $node->parts[0]->value === ''
                ) {
                    return "<<<$label\n$label" . $this->docStringEndToken;
                }

                return "<<<$label\n" . $this->pEncapsList($node->parts, null) . "\n$label"
                     . $this->docStringEndToken;
            }
        }
        return '"' . $this->pEncapsList($node->parts, '"') . '"';
    }

    protected function pScalar_LNumber(Scalar\LNumber $node) {
        if ($node->value === -\PHP_INT_MAX-1) {
            // PHP_INT_MIN cannot be represented as a literal,
            // because the sign is not part of the literal
            return '(-' . \PHP_INT_MAX . '-1)';
        }

        $kind = $node->getAttribute('kind', Scalar\LNumber::KIND_DEC);
        if (Scalar\LNumber::KIND_DEC === $kind) {
            return (string) $node->value;
        }

        $sign = $node->value < 0 ? '-' : '';
        $str = (string) $node->value;
        switch ($kind) {
            case Scalar\LNumber::KIND_BIN:
                return $sign . '0b' . base_convert($str, 10, 2);
            case Scalar\LNumber::KIND_OCT:
                return $sign . '0' . base_convert($str, 10, 8);
            case Scalar\LNumber::KIND_HEX:
                return $sign . '0x' . base_convert($str, 10, 16);
        }
        throw new \Exception('Invalid number kind');
    }

    protected function pScalar_DNumber(Scalar\DNumber $node) {
        if (!is_finite($node->value)) {
            if ($node->value === \INF) {
                return '\INF';
            } elseif ($node->value === -\INF) {
                return '-\INF';
            } else {
                return '\NAN';
            }
        }

        // Try to find a short full-precision representation
        $stringValue = sprintf('%.16G', $node->value);
        if ($node->value !== (double) $stringValue) {
            $stringValue = sprintf('%.17G', $node->value);
        }

        // %G is locale dependent and there exists no locale-independent alternative. We don't want
        // mess with switching locales here, so let's assume that a comma is the only non-standard
        // decimal separator we may encounter...
        $stringValue = str_replace(',', '.', $stringValue);

        // ensure that number is really printed as float
        return preg_match('/^-?[0-9]+$/', $stringValue) ? $stringValue . '.0' : $stringValue;
    }

    protected function pScalar_EncapsedStringPart(Scalar\EncapsedStringPart $node) {
        throw new \LogicException('Cannot directly print EncapsedStringPart');
    }

    // Assignments

    protected function pExpr_Assign(Expr\Assign $node) {
        return $this->pInfixOp(Expr\Assign::class, $node->var, ' = ', $node->expr);
    }

    protected function pExpr_AssignRef(Expr\AssignRef $node) {
        return $this->pInfixOp(Expr\AssignRef::class, $node->var, ' =& ', $node->expr);
    }

    protected function pExpr_AssignOp_Plus(AssignOp\Plus $node) {
        return $this->pInfixOp(AssignOp\Plus::class, $node->var, ' += ', $node->expr);
    }

    protected function pExpr_AssignOp_Minus(AssignOp\Minus $node) {
        return $this->pInfixOp(AssignOp\Minus::class, $node->var, ' -= ', $node->expr);
    }

    protected function pExpr_AssignOp_Mul(AssignOp\Mul $node) {
        return $this->pInfixOp(AssignOp\Mul::class, $node->var, ' *= ', $node->expr);
    }

    protected function pExpr_AssignOp_Div(AssignOp\Div $node) {
        return $this->pInfixOp(AssignOp\Div::class, $node->var, ' /= ', $node->expr);
    }

    protected function pExpr_AssignOp_Concat(AssignOp\Concat $node) {
        return $this->pInfixOp(AssignOp\Concat::class, $node->var, ' .= ', $node->expr);
    }

    protected function pExpr_AssignOp_Mod(AssignOp\Mod $node) {
        return $this->pInfixOp(AssignOp\Mod::class, $node->var, ' %= ', $node->expr);
    }

    protected function pExpr_AssignOp_BitwiseAnd(AssignOp\BitwiseAnd $node) {
        return $this->pInfixOp(AssignOp\BitwiseAnd::class, $node->var, ' &= ', $node->expr);
    }

    protected function pExpr_AssignOp_BitwiseOr(AssignOp\BitwiseOr $node) {
        return $this->pInfixOp(AssignOp\BitwiseOr::class, $node->var, ' |= ', $node->expr);
    }

    protected function pExpr_AssignOp_BitwiseXor(AssignOp\BitwiseXor $node) {
        return $this->pInfixOp(AssignOp\BitwiseXor::class, $node->var, ' ^= ', $node->expr);
    }

    protected function pExpr_AssignOp_ShiftLeft(AssignOp\ShiftLeft $node) {
        return $this->pInfixOp(AssignOp\ShiftLeft::class, $node->var, ' <<= ', $node->expr);
    }

    protected function pExpr_AssignOp_ShiftRight(AssignOp\ShiftRight $node) {
        return $this->pInfixOp(AssignOp\ShiftRight::class, $node->var, ' >>= ', $node->expr);
    }

    protected function pExpr_AssignOp_Pow(AssignOp\Pow $node) {
        return $this->pInfixOp(AssignOp\Pow::class, $node->var, ' **= ', $node->expr);
    }

    protected function pExpr_AssignOp_Coalesce(AssignOp\Coalesce $node) {
        return $this->pInfixOp(AssignOp\Coalesce::class, $node->var, ' ??= ', $node->expr);
    }

    // Binary expressions

    protected function pExpr_BinaryOp_Plus(BinaryOp\Plus $node) {
        return $this->pInfixOp(BinaryOp\Plus::class, $node->left, ' + ', $node->right);
    }

    protected function pExpr_BinaryOp_Minus(BinaryOp\Minus $node) {
        return $this->pInfixOp(BinaryOp\Minus::class, $node->left, ' - ', $node->right);
    }

    protected function pExpr_BinaryOp_Mul(BinaryOp\Mul $node) {
        return $this->pInfixOp(BinaryOp\Mul::class, $node->left, ' * ', $node->right);
    }

    protected function pExpr_BinaryOp_Div(BinaryOp\Div $node) {
        return $this->pInfixOp(BinaryOp\Div::class, $node->left, ' / ', $node->right);
    }

    protected function pExpr_BinaryOp_Concat(BinaryOp\Concat $node) {
        return $this->pInfixOp(BinaryOp\Concat::class, $node->left, ' . ', $node->right);
    }

    protected function pExpr_BinaryOp_Mod(BinaryOp\Mod $node) {
        return $this->pInfixOp(BinaryOp\Mod::class, $node->left, ' % ', $node->right);
    }

    protected function pExpr_BinaryOp_BooleanAnd(BinaryOp\BooleanAnd $node) {
        return $this->pInfixOp(BinaryOp\BooleanAnd::class, $node->left, ' && ', $node->right);
    }

    protected function pExpr_BinaryOp_BooleanOr(BinaryOp\BooleanOr $node) {
        return $this->pInfixOp(BinaryOp\BooleanOr::class, $node->left, ' || ', $node->right);
    }

    protected function pExpr_BinaryOp_BitwiseAnd(BinaryOp\BitwiseAnd $node) {
        return $this->pInfixOp(BinaryOp\BitwiseAnd::class, $node->left, ' & ', $node->right);
    }

    protected function pExpr_BinaryOp_BitwiseOr(BinaryOp\BitwiseOr $node) {
        return $this->pInfixOp(BinaryOp\BitwiseOr::class, $node->left, ' | ', $node->right);
    }

    protected function pExpr_BinaryOp_BitwiseXor(BinaryOp\BitwiseXor $node) {
        return $this->pInfixOp(BinaryOp\BitwiseXor::class, $node->left, ' ^ ', $node->right);
    }

    protected function pExpr_BinaryOp_ShiftLeft(BinaryOp\ShiftLeft $node) {
        return $this->pInfixOp(BinaryOp\ShiftLeft::class, $node->left, ' << ', $node->right);
    }

    protected function pExpr_BinaryOp_ShiftRight(BinaryOp\ShiftRight $node) {
        return $this->pInfixOp(BinaryOp\ShiftRight::class, $node->left, ' >> ', $node->right);
    }

    protected function pExpr_BinaryOp_Pow(BinaryOp\Pow $node) {
        return $this->pInfixOp(BinaryOp\Pow::class, $node->left, ' ** ', $node->right);
    }

    protected function pExpr_BinaryOp_LogicalAnd(BinaryOp\LogicalAnd $node) {
        return $this->pInfixOp(BinaryOp\LogicalAnd::class, $node->left, ' and ', $node->right);
    }

    protected function pExpr_BinaryOp_LogicalOr(BinaryOp\LogicalOr $node) {
        return $this->pInfixOp(BinaryOp\LogicalOr::class, $node->left, ' or ', $node->right);
    }

    protected function pExpr_BinaryOp_LogicalXor(BinaryOp\LogicalXor $node) {
        return $this->pInfixOp(BinaryOp\LogicalXor::class, $node->left, ' xor ', $node->right);
    }

    protected function pExpr_BinaryOp_Equal(BinaryOp\Equal $node) {
        return $this->pInfixOp(BinaryOp\Equal::class, $node->left, ' == ', $node->right);
    }

    protected function pExpr_BinaryOp_NotEqual(BinaryOp\NotEqual $node) {
        return $this->pInfixOp(BinaryOp\NotEqual::class, $node->left, ' != ', $node->right);
    }

    protected function pExpr_BinaryOp_Identical(BinaryOp\Identical $node) {
        return $this->pInfixOp(BinaryOp\Identical::class, $node->left, ' === ', $node->right);
    }

    protected function pExpr_BinaryOp_NotIdentical(BinaryOp\NotIdentical $node) {
        return $this->pInfixOp(BinaryOp\NotIdentical::class, $node->left, ' !== ', $node->right);
    }

    protected function pExpr_BinaryOp_Spaceship(BinaryOp\Spaceship $node) {
        return $this->pInfixOp(BinaryOp\Spaceship::class, $node->left, ' <=> ', $node->right);
    }

    protected function pExpr_BinaryOp_Greater(BinaryOp\Greater $node) {
        return $this->pInfixOp(BinaryOp\Greater::class, $node->left, ' > ', $node->right);
    }

    protected function pExpr_BinaryOp_GreaterOrEqual(BinaryOp\GreaterOrEqual $node) {
        return $this->pInfixOp(BinaryOp\GreaterOrEqual::class, $node->left, ' >= ', $node->right);
    }

    protected function pExpr_BinaryOp_Smaller(BinaryOp\Smaller $node) {
        return $this->pInfixOp(BinaryOp\Smaller::class, $node->left, ' < ', $node->right);
    }

    protected function pExpr_BinaryOp_SmallerOrEqual(BinaryOp\SmallerOrEqual $node) {
        return $this->pInfixOp(BinaryOp\SmallerOrEqual::class, $node->left, ' <= ', $node->right);
    }

    protected function pExpr_BinaryOp_Coalesce(BinaryOp\Coalesce $node) {
        return $this->pInfixOp(BinaryOp\Coalesce::class, $node->left, ' ?? ', $node->right);
    }

    protected function pExpr_Instanceof(Expr\Instanceof_ $node) {
        return $this->pInfixOp(Expr\Instanceof_::class, $node->expr, ' instanceof ', $node->class);
    }

    // Unary expressions

    protected function pExpr_BooleanNot(Expr\BooleanNot $node) {
        return $this->pPrefixOp(Expr\BooleanNot::class, '!', $node->expr);
    }

    protected function pExpr_BitwiseNot(Expr\BitwiseNot $node) {
        return $this->pPrefixOp(Expr\BitwiseNot::class, '~', $node->expr);
    }

    protected function pExpr_UnaryMinus(Expr\UnaryMinus $node) {
        if ($node->expr instanceof Expr\UnaryMinus || $node->expr instanceof Expr\PreDec) {
            // Enforce -(-$expr) instead of --$expr
            return '-(' . $this->p($node->expr) . ')';
        }
        return $this->pPrefixOp(Expr\UnaryMinus::class, '-', $node->expr);
    }

    protected function pExpr_UnaryPlus(Expr\UnaryPlus $node) {
        if ($node->expr instanceof Expr\UnaryPlus || $node->expr instanceof Expr\PreInc) {
            // Enforce +(+$expr) instead of ++$expr
            return '+(' . $this->p($node->expr) . ')';
        }
        return $this->pPrefixOp(Expr\UnaryPlus::class, '+', $node->expr);
    }

    protected function pExpr_PreInc(Expr\PreInc $node) {
        return $this->pPrefixOp(Expr\PreInc::class, '++', $node->var);
    }

    protected function pExpr_PreDec(Expr\PreDec $node) {
        return $this->pPrefixOp(Expr\PreDec::class, '--', $node->var);
    }

    protected function pExpr_PostInc(Expr\PostInc $node) {
        return $this->pPostfixOp(Expr\PostInc::class, $node->var, '++');
    }

    protected function pExpr_PostDec(Expr\PostDec $node) {
        return $this->pPostfixOp(Expr\PostDec::class, $node->var, '--');
    }

    protected function pExpr_ErrorSuppress(Expr\ErrorSuppress $node) {
        return $this->pPrefixOp(Expr\ErrorSuppress::class, '@', $node->expr);
    }

    protected function pExpr_YieldFrom(Expr\YieldFrom $node) {
        return $this->pPrefixOp(Expr\YieldFrom::class, 'yield from ', $node->expr);
    }

    protected function pExpr_Print(Expr\Print_ $node) {
        return $this->pPrefixOp(Expr\Print_::class, 'print ', $node->expr);
    }

    // Casts

    protected function pExpr_Cast_Int(Cast\Int_ $node) {
        return $this->pPrefixOp(Cast\Int_::class, '(int) ', $node->expr);
    }

    protected function pExpr_Cast_Double(Cast\Double $node) {
        $kind = $node->getAttribute('kind', Cast\Double::KIND_DOUBLE);
        if ($kind === Cast\Double::KIND_DOUBLE) {
            $cast = '(double)';
        } elseif ($kind === Cast\Double::KIND_FLOAT) {
            $cast = '(float)';
        } elseif ($kind === Cast\Double::KIND_REAL) {
            $cast = '(real)';
        }
        return $this->pPrefixOp(Cast\Double::class, $cast . ' ', $node->expr);
    }

    protected function pExpr_Cast_String(Cast\String_ $node) {
        return $this->pPrefixOp(Cast\String_::class, '(string) ', $node->expr);
    }

    protected function pExpr_Cast_Array(Cast\Array_ $node) {
        return $this->pPrefixOp(Cast\Array_::class, '(array) ', $node->expr);
    }

    protected function pExpr_Cast_Object(Cast\Object_ $node) {
        return $this->pPrefixOp(Cast\Object_::class, '(object) ', $node->expr);
    }

    protected function pExpr_Cast_Bool(Cast\Bool_ $node) {
        return $this->pPrefixOp(Cast\Bool_::class, '(bool) ', $node->expr);
    }

    protected function pExpr_Cast_Unset(Cast\Unset_ $node) {
        return $this->pPrefixOp(Cast\Unset_::class, '(unset) ', $node->expr);
    }

    // Function calls and similar constructs

    protected function pExpr_FuncCall(Expr\FuncCall $node) {
        return $this->pCallLhs($node->name)
             . '(' . $this->pMaybeMultiline($node->args) . ')';
    }

    protected function pExpr_MethodCall(Expr\MethodCall $node) {
        return $this->pDereferenceLhs($node->var) . '->' . $this->pObjectProperty($node->name)
             . '(' . $this->pMaybeMultiline($node->args) . ')';
    }

    protected function pExpr_StaticCall(Expr\StaticCall $node) {
        return $this->pDereferenceLhs($node->class) . '::'
             . ($node->name instanceof Expr
                ? ($node->name instanceof Expr\Variable
                   ? $this->p($node->name)
                   : '{' . $this->p($node->name) . '}')
                : $node->name)
             . '(' . $this->pMaybeMultiline($node->args) . ')';
    }

    protected function pExpr_Empty(Expr\Empty_ $node) {
        return 'empty(' . $this->p($node->expr) . ')';
    }

    protected function pExpr_Isset(Expr\Isset_ $node) {
        return 'isset(' . $this->pCommaSeparated($node->vars) . ')';
    }

    protected function pExpr_Eval(Expr\Eval_ $node) {
        return 'eval(' . $this->p($node->expr) . ')';
    }

    protected function pExpr_Include(Expr\Include_ $node) {
        static $map = [
            Expr\Include_::TYPE_INCLUDE      => 'include',
            Expr\Include_::TYPE_INCLUDE_ONCE => 'include_once',
            Expr\Include_::TYPE_REQUIRE      => 'require',
            Expr\Include_::TYPE_REQUIRE_ONCE => 'require_once',
        ];

        return $map[$node->type] . ' ' . $this->p($node->expr);
    }

    protected function pExpr_List(Expr\List_ $node) {
        return 'list(' . $this->pCommaSeparated($node->items) . ')';
    }

    // Other

    protected function pExpr_Error(Expr\Error $node) {
        throw new \LogicException('Cannot pretty-print AST with Error nodes');
    }

    protected function pExpr_Variable(Expr\Variable $node) {
        if ($node->name instanceof Expr) {
            return '${' . $this->p($node->name) . '}';
        } else {
            return '$' . $node->name;
        }
    }

    protected function pExpr_Array(Expr\Array_ $node) {
        $syntax = $node->getAttribute('kind',
            $this->options['shortArraySyntax'] ? Expr\Array_::KIND_SHORT : Expr\Array_::KIND_LONG);
        if ($syntax === Expr\Array_::KIND_SHORT) {
            return '[' . $this->pMaybeMultiline($node->items, true) . ']';
        } else {
            return 'array(' . $this->pMaybeMultiline($node->items, true) . ')';
        }
    }

    protected function pExpr_ArrayItem(Expr\ArrayItem $node) {
        return (null !== $node->key ? $this->p($node->key) . ' => ' : '')
             . ($node->byRef ? '&' : '') . $this->p($node->value);
    }

    protected function pExpr_ArrayDimFetch(Expr\ArrayDimFetch $node) {
        return $this->pDereferenceLhs($node->var)
             . '[' . (null !== $node->dim ? $this->p($node->dim) : '') . ']';
    }

    protected function pExpr_ConstFetch(Expr\ConstFetch $node) {
        return $this->p($node->name);
    }

    protected function pExpr_ClassConstFetch(Expr\ClassConstFetch $node) {
        return $this->p($node->class) . '::' . $this->p($node->name);
    }

    protected function pExpr_PropertyFetch(Expr\PropertyFetch $node) {
        return $this->pDereferenceLhs($node->var) . '->' . $this->pObjectProperty($node->name);
    }

    protected function pExpr_StaticPropertyFetch(Expr\StaticPropertyFetch $node) {
        return $this->pDereferenceLhs($node->class) . '::$' . $this->pObjectProperty($node->name);
    }

    protected function pExpr_ShellExec(Expr\ShellExec $node) {
        return '`' . $this->pEncapsList($node->parts, '`') . '`';
    }

    protected function pExpr_Closure(Expr\Closure $node) {
        return ($node->static ? 'static ' : '')
             . 'function ' . ($node->byRef ? '&' : '')
             . '(' . $this->pCommaSeparated($node->params) . ')'
             . (!empty($node->uses) ? ' use(' . $this->pCommaSeparated($node->uses) . ')' : '')
             . (null !== $node->returnType ? ' : ' . $this->p($node->returnType) : '')
             . ' {' . $this->pStmts($node->stmts) . $this->nl . '}';
    }

    protected function pExpr_ClosureUse(Expr\ClosureUse $node) {
        return ($node->byRef ? '&' : '') . $this->p($node->var);
    }

    protected function pExpr_New(Expr\New_ $node) {
        if ($node->class instanceof Stmt\Class_) {
            $args = $node->args ? '(' . $this->pMaybeMultiline($node->args) . ')' : '';
            return 'new ' . $this->pClassCommon($node->class, $args);
        }
        return 'new ' . $this->p($node->class) . '(' . $this->pMaybeMultiline($node->args) . ')';
    }

    protected function pExpr_Clone(Expr\Clone_ $node) {
        return 'clone ' . $this->p($node->expr);
    }

    protected function pExpr_Ternary(Expr\Ternary $node) {
        // a bit of cheating: we treat the ternary as a binary op where the ?...: part is the operator.
        // this is okay because the part between ? and : never needs parentheses.
        return $this->pInfixOp(Expr\Ternary::class,
            $node->cond, ' ?' . (null !== $node->if ? ' ' . $this->p($node->if) . ' ' : '') . ': ', $node->else
        );
    }

    protected function pExpr_Exit(Expr\Exit_ $node) {
        $kind = $node->getAttribute('kind', Expr\Exit_::KIND_DIE);
        return ($kind === Expr\Exit_::KIND_EXIT ? 'exit' : 'die')
             . (null !== $node->expr ? '(' . $this->p($node->expr) . ')' : '');
    }

    protected function pExpr_Yield(Expr\Yield_ $node) {
        if ($node->value === null) {
            return 'yield';
        } else {
            // this is a bit ugly, but currently there is no way to detect whether the parentheses are necessary
            return '(yield '
                 . ($node->key !== null ? $this->p($node->key) . ' => ' : '')
                 . $this->p($node->value)
                 . ')';
        }
    }

    // Declarations

    protected function pStmt_Namespace(Stmt\Namespace_ $node) {
        if ($this->canUseSemicolonNamespaces) {
            return 'namespace ' . $this->p($node->name) . ';'
                 . $this->nl . $this->pStmts($node->stmts, false);
        } else {
            return 'namespace' . (null !== $node->name ? ' ' . $this->p($node->name) : '')
                 . ' {' . $this->pStmts($node->stmts) . $this->nl . '}';
        }
    }

    protected function pStmt_Use(Stmt\Use_ $node) {
        return 'use ' . $this->pUseType($node->type)
             . $this->pCommaSeparated($node->uses) . ';';
    }

    protected function pStmt_GroupUse(Stmt\GroupUse $node) {
        return 'use ' . $this->pUseType($node->type) . $this->pName($node->prefix)
             . '\{' . $this->pCommaSeparated($node->uses) . '};';
    }

    protected function pStmt_UseUse(Stmt\UseUse $node) {
        return $this->pUseType($node->type) . $this->p($node->name)
             . (null !== $node->alias ? ' as ' . $node->alias : '');
    }

    protected function pUseType($type) {
        return $type === Stmt\Use_::TYPE_FUNCTION ? 'function '
            : ($type === Stmt\Use_::TYPE_CONSTANT ? 'const ' : '');
    }

    protected function pStmt_Interface(Stmt\Interface_ $node) {
        return 'interface ' . $node->name
             . (!empty($node->extends) ? ' extends ' . $this->pCommaSeparated($node->extends) : '')
             . $this->nl . '{' . $this->pStmts($node->stmts) . $this->nl . '}';
    }

    protected function pStmt_Class(Stmt\Class_ $node) {
        return $this->pClassCommon($node, ' ' . $node->name);
    }

    protected function pStmt_Trait(Stmt\Trait_ $node) {
        return 'trait ' . $node->name
             . $this->nl . '{' . $this->pStmts($node->stmts) . $this->nl . '}';
    }

    protected function pStmt_TraitUse(Stmt\TraitUse $node) {
        return 'use ' . $this->pCommaSeparated($node->traits)
             . (empty($node->adaptations)
                ? ';'
                : ' {' . $this->pStmts($node->adaptations) . $this->nl . '}');
    }

    protected function pStmt_TraitUseAdaptation_Precedence(Stmt\TraitUseAdaptation\Precedence $node) {
        return $this->p($node->trait) . '::' . $node->method
             . ' insteadof ' . $this->pCommaSeparated($node->insteadof) . ';';
    }

    protected function pStmt_TraitUseAdaptation_Alias(Stmt\TraitUseAdaptation\Alias $node) {
        return (null !== $node->trait ? $this->p($node->trait) . '::' : '')
             . $node->method . ' as'
             . (null !== $node->newModifier ? ' ' . rtrim($this->pModifiers($node->newModifier), ' ') : '')
             . (null !== $node->newName     ? ' ' . $node->newName                        : '')
             . ';';
    }

    protected function pStmt_Property(Stmt\Property $node) {
        return (0 === $node->flags ? 'var ' : $this->pModifiers($node->flags))
            . ($node->type ? $this->p($node->type) . ' ' : '')
            . $this->pCommaSeparated($node->props) . ';';
    }

    protected function pStmt_PropertyProperty(Stmt\PropertyProperty $node) {
        return '$' . $node->name
             . (null !== $node->default ? ' = ' . $this->p($node->default) : '');
    }

    protected function pStmt_ClassMethod(Stmt\ClassMethod $node) {
        return $this->pModifiers($node->flags)
             . 'function ' . ($node->byRef ? '&' : '') . $node->name
             . '(' . $this->pCommaSeparated($node->params) . ')'
             . (null !== $node->returnType ? ' : ' . $this->p($node->returnType) : '')
             . (null !== $node->stmts
                ? $this->nl . '{' . $this->pStmts($node->stmts) . $this->nl . '}'
                : ';');
    }

    protected function pStmt_ClassConst(Stmt\ClassConst $node) {
        return $this->pModifiers($node->flags)
             . 'const ' . $this->pCommaSeparated($node->consts) . ';';
    }

    protected function pStmt_Function(Stmt\Function_ $node) {
        return 'function ' . ($node->byRef ? '&' : '') . $node->name
             . '(' . $this->pCommaSeparated($node->params) . ')'
             . (null !== $node->returnType ? ' : ' . $this->p($node->returnType) : '')
             . $this->nl . '{' . $this->pStmts($node->stmts) . $this->nl . '}';
    }

    protected function pStmt_Const(Stmt\Const_ $node) {
        return 'const ' . $this->pCommaSeparated($node->consts) . ';';
    }

    protected function pStmt_Declare(Stmt\Declare_ $node) {
        return 'declare (' . $this->pCommaSeparated($node->declares) . ')'
             . (null !== $node->stmts ? ' {' . $this->pStmts($node->stmts) . $this->nl . '}' : ';');
    }

    protected function pStmt_DeclareDeclare(Stmt\DeclareDeclare $node) {
        return $node->key . '=' . $this->p($node->value);
    }

    // Control flow

    protected function pStmt_If(Stmt\If_ $node) {
        return 'if (' . $this->p($node->cond) . ') {'
             . $this->pStmts($node->stmts) . $this->nl . '}'
             . ($node->elseifs ? ' ' . $this->pImplode($node->elseifs, ' ') : '')
             . (null !== $node->else ? ' ' . $this->p($node->else) : '');
    }

    protected function pStmt_ElseIf(Stmt\ElseIf_ $node) {
        return 'elseif (' . $this->p($node->cond) . ') {'
             . $this->pStmts($node->stmts) . $this->nl . '}';
    }

    protected function pStmt_Else(Stmt\Else_ $node) {
        return 'else {' . $this->pStmts($node->stmts) . $this->nl . '}';
    }

    protected function pStmt_For(Stmt\For_ $node) {
        return 'for ('
             . $this->pCommaSeparated($node->init) . ';' . (!empty($node->cond) ? ' ' : '')
             . $this->pCommaSeparated($node->cond) . ';' . (!empty($node->loop) ? ' ' : '')
             . $this->pCommaSeparated($node->loop)
             . ') {' . $this->pStmts($node->stmts) . $this->nl . '}';
    }

    protected function pStmt_Foreach(Stmt\Foreach_ $node) {
        return 'foreach (' . $this->p($node->expr) . ' as '
             . (null !== $node->keyVar ? $this->p($node->keyVar) . ' => ' : '')
             . ($node->byRef ? '&' : '') . $this->p($node->valueVar) . ') {'
             . $this->pStmts($node->stmts) . $this->nl . '}';
    }

    protected function pStmt_While(Stmt\While_ $node) {
        return 'while (' . $this->p($node->cond) . ') {'
             . $this->pStmts($node->stmts) . $this->nl . '}';
    }

    protected function pStmt_Do(Stmt\Do_ $node) {
        return 'do {' . $this->pStmts($node->stmts) . $this->nl
             . '} while (' . $this->p($node->cond) . ');';
    }

    protected function pStmt_Switch(Stmt\Switch_ $node) {
        return 'switch (' . $this->p($node->cond) . ') {'
             . $this->pStmts($node->cases) . $this->nl . '}';
    }

    protected function pStmt_Case(Stmt\Case_ $node) {
        return (null !== $node->cond ? 'case ' . $this->p($node->cond) : 'default') . ':'
             . $this->pStmts($node->stmts);
    }

    protected function pStmt_TryCatch(Stmt\TryCatch $node) {
        return 'try {' . $this->pStmts($node->stmts) . $this->nl . '}'
             . ($node->catches ? ' ' . $this->pImplode($node->catches, ' ') : '')
             . ($node->finally !== null ? ' ' . $this->p($node->finally) : '');
    }

    protected function pStmt_Catch(Stmt\Catch_ $node) {
        return 'catch (' . $this->pImplode($node->types, '|') . ' '
             . $this->p($node->var)
             . ') {' . $this->pStmts($node->stmts) . $this->nl . '}';
    }

    protected function pStmt_Finally(Stmt\Finally_ $node) {
        return 'finally {' . $this->pStmts($node->stmts) . $this->nl . '}';
    }

    protected function pStmt_Break(Stmt\Break_ $node) {
        return 'break' . ($node->num !== null ? ' ' . $this->p($node->num) : '') . ';';
    }

    protected function pStmt_Continue(Stmt\Continue_ $node) {
        return 'continue' . ($node->num !== null ? ' ' . $this->p($node->num) : '') . ';';
    }

    protected function pStmt_Return(Stmt\Return_ $node) {
        return 'return' . (null !== $node->expr ? ' ' . $this->p($node->expr) : '') . ';';
    }

    protected function pStmt_Throw(Stmt\Throw_ $node) {
        return 'throw ' . $this->p($node->expr) . ';';
    }

    protected function pStmt_Label(Stmt\Label $node) {
        return $node->name . ':';
    }

    protected function pStmt_Goto(Stmt\Goto_ $node) {
        return 'goto ' . $node->name . ';';
    }

    // Other

    protected function pStmt_Expression(Stmt\Expression $node) {
        return $this->p($node->expr) . ';';
    }

    protected function pStmt_Echo(Stmt\Echo_ $node) {
        return 'echo ' . $this->pCommaSeparated($node->exprs) . ';';
    }

    protected function pStmt_Static(Stmt\Static_ $node) {
        return 'static ' . $this->pCommaSeparated($node->vars) . ';';
    }

    protected function pStmt_Global(Stmt\Global_ $node) {
        return 'global ' . $this->pCommaSeparated($node->vars) . ';';
    }

    protected function pStmt_StaticVar(Stmt\StaticVar $node) {
        return $this->p($node->var)
             . (null !== $node->default ? ' = ' . $this->p($node->default) : '');
    }

    protected function pStmt_Unset(Stmt\Unset_ $node) {
        return 'unset(' . $this->pCommaSeparated($node->vars) . ');';
    }

    protected function pStmt_InlineHTML(Stmt\InlineHTML $node) {
        $newline = $node->getAttribute('hasLeadingNewline', true) ? "\n" : '';
        return '?>' . $newline . $node->value . '<?php ';
    }

    protected function pStmt_HaltCompiler(Stmt\HaltCompiler $node) {
        return '__halt_compiler();' . $node->remaining;
    }

    protected function pStmt_Nop(Stmt\Nop $node) {
        return '';
    }

    // Helpers

    protected function pClassCommon(Stmt\Class_ $node, $afterClassToken) {
        return $this->pModifiers($node->flags)
        . 'class' . $afterClassToken
        . (null !== $node->extends ? ' extends ' . $this->p($node->extends) : '')
        . (!empty($node->implements) ? ' implements ' . $this->pCommaSeparated($node->implements) : '')
        . $this->nl . '{' . $this->pStmts($node->stmts) . $this->nl . '}';
    }

    protected function pObjectProperty($node) {
        if ($node instanceof Expr) {
            return '{' . $this->p($node) . '}';
        } else {
            return $node;
        }
    }

    protected function pEncapsList(array $encapsList, $quote) {
        $return = '';
        foreach ($encapsList as $element) {
            if ($element instanceof Scalar\EncapsedStringPart) {
                $return .= $this->escapeString($element->value, $quote);
            } else {
                $return .= '{' . $this->p($element) . '}';
            }
        }

        return $return;
    }

    protected function pSingleQuotedString(string $string) {
        return '\'' . addcslashes($string, '\'\\') . '\'';
    }

    protected function escapeString($string, $quote) {
        if (null === $quote) {
            // For doc strings, don't escape newlines
            $escaped = addcslashes($string, "\t\f\v$\\");
        } else {
            $escaped = addcslashes($string, "\n\r\t\f\v$" . $quote . "\\");
        }

        // Escape other control characters
        return preg_replace_callback('/([\0-\10\16-\37])(?=([0-7]?))/', function ($matches) {
            $oct = decoct(ord($matches[1]));
            if ($matches[2] !== '') {
                // If there is a trailing digit, use the full three character form
                return '\\' . str_pad($oct, 3, '0', \STR_PAD_LEFT);
            }
            return '\\' . $oct;
        }, $escaped);
    }

    protected function containsEndLabel($string, $label, $atStart = true, $atEnd = true) {
        $start = $atStart ? '(?:^|[\r\n])' : '[\r\n]';
        $end = $atEnd ? '(?:$|[;\r\n])' : '[;\r\n]';
        return false !== strpos($string, $label)
            && preg_match('/' . $start . $label . $end . '/', $string);
    }

    protected function encapsedContainsEndLabel(array $parts, $label) {
        foreach ($parts as $i => $part) {
            $atStart = $i === 0;
            $atEnd = $i === count($parts) - 1;
            if ($part instanceof Scalar\EncapsedStringPart
                && $this->containsEndLabel($part->value, $label, $atStart, $atEnd)
            ) {
                return true;
            }
        }
        return false;
    }

    protected function pDereferenceLhs(Node $node) {
        if (!$this->dereferenceLhsRequiresParens($node)) {
            return $this->p($node);
        } else  {
            return '(' . $this->p($node) . ')';
        }
    }

    protected function pCallLhs(Node $node) {
        if (!$this->callLhsRequiresParens($node)) {
            return $this->p($node);
        } else  {
            return '(' . $this->p($node) . ')';
        }
    }

    /**
     * @param Node[] $nodes
     * @return bool
     */
    private function hasNodeWithComments(array $nodes) {
        foreach ($nodes as $node) {
            if ($node && $node->getComments()) {
                return true;
            }
        }
        return false;
    }

    private function pMaybeMultiline(array $nodes, $trailingComma = false) {
        if (!$this->hasNodeWithComments($nodes)) {
            return $this->pCommaSeparated($nodes);
        } else {
            return $this->pCommaSeparatedMultiline($nodes, $trailingComma) . $this->nl;
        }
    }
}
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                  <?php

namespace PhpParser;

require __DIR__ . '/../vendor/autoload.php';

function canonicalize($str) {
    // normalize EOL style
    $str = str_replace("\r\n", "\n", $str);

    // trim newlines at end
    $str = rtrim($str, "\n");

    // remove trailing whitespace on all lines
    $lines = explode("\n", $str);
    $lines = array_map(function($line) {
        return rtrim($line, " \t");
    }, $lines);
    return implode("\n", $lines);
}

function filesInDir($directory, $fileExtension) {
    $directory = realpath($directory);
    $it = new \RecursiveDirectoryIterator($directory);
    $it = new \RecursiveIteratorIterator($it, \RecursiveIteratorIterator::LEAVES_ONLY);
    $it = new \RegexIterator($it, '(\.' . preg_quote($fileExtension) . '$)');
    foreach ($it as $file) {
        $fileName = $file->getPathname();
        yield $fileName => file_get_contents($fileName);
    }
}
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                   <?php

namespace PhpParser;

require __DIR__ . '/bootstrap.php';
require __DIR__ . '/PhpParser/CodeTestParser.php';
require __DIR__ . '/PhpParser/CodeParsingTest.php';

$dir = __DIR__ . '/code/parser';

$testParser = new CodeTestParser;
$codeParsingTest = new CodeParsingTest;
foreach (filesInDir($dir, 'test') as $fileName => $code) {
    if (false !== strpos($code, '@@{')) {
        // Skip tests with evaluate segments
        continue;
    }

    list($name, $tests) = $testParser->parseTest($code, 2);
    $newTests = [];
    foreach ($tests as list($modeLine, list($input, $expected))) {
        $modes = null !== $modeLine ? array_fill_keys(explode(',', $modeLine), true) : [];
        list($parser5, $parser7) = $codeParsingTest->createParsers($modes);
        list(, $output) = isset($modes['php5'])
            ? $codeParsingTest->getParseOutput($parser5, $input, $modes)
            : $codeParsingTest->getParseOutput($parser7, $input, $modes);
        $newTests[] = [$modeLine, [$input, $output]];
    }

    $newCode = $testParser->reconstructTest($name, $newTests);
    file_put_contents($fileName, $newCode);
}
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                         Arrow function
-----
<?php
fn($a)
=>
$a;
-----
$stmts[0]->expr->expr = new Expr\Variable('b');
-----
<?php
fn($a)
=>
$b;
-----
<?php
fn(
$a
) => $a;
-----
$stmts[0]->expr->params[] = new Node\Param(new Expr\Variable('b'));
-----
<?php
fn(
$a, $b
) => $a;
-----
<?php
fn(
$a
)
=>
$a;
-----
// TODO: Format preserving currently not supported
$stmts[0]->expr->params = [];
-----
<?php
fn() => $a;
-----
<?php
fn($a)
: int
=> $a;
-----
$stmts[0]->expr->returnType = new Node\Identifier('bool');
-----
<?php
fn($a)
: bool
=> $a;
-----
<?php
fn($a)
: int
=> $a;
-----
$stmts[0]->expr->returnType = null;
-----
<?php
fn($a)
=> $a;
-----
<?php
fn($a)
: int
=> $a;

static fn($a)
: int
=> $a;
-----
// TODO: Format preserving currently not supported
$stmts[0]->expr->static = true;
$stmts[1]->expr->static = false;
-----
<?php
static fn($a): int => $a;

fn($a): int => $a;
-----
<?php
fn($a)
: int
=> $a;

fn&($a)
: int
=> $a;
-----
// TODO: Format preserving currently not supported
$stmts[0]->expr->byRef = true;
$stmts[1]->expr->byRef = false;
-----
<?php
fn&($a): int => $a;

fn($a): int => $a;
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                              abc1
-----
<?php
echo
    1
        +
            2
                +
                    3;
-----
$stmts[0]->exprs[0]->left->right->value = 42;
-----
<?php
echo
    1
        +
            42
                +
                    3;
-----
<?php
function foo($a)
    { return $a; }
-----
$stmts[0]->name = new Node\Identifier('bar');
-----
<?php
function bar($a)
    { return $a; }
-----
<?php
function
foo() {
    call(
        $bar
    );
}
-----
// This triggers a fallback
$stmts[0]->byRef = true;
-----
<?php
function &foo()
{
    call(
        $bar
    );
}
-----
<?php
function
foo() {
echo "Start
End";
}
-----
// This triggers a fallback
$stmts[0]->byRef = true;
-----
<?php
function &foo()
{
    echo "Start
End";
}
-----
<?php
function test() {
    call1(
        $bar
    );
}
call2(
    $foo
);
-----
$tmp = $stmts[0]->stmts[0];
$stmts[0]->stmts[0] = $stmts[1];
$stmts[1] = $tmp;
-----
<?php
function test() {
    call2(
        $foo
    );
}
call1(
    $bar
);
-----
<?php
x;
function test() {
    call1(
        $bar
    );
}
call2(
    $foo
);
-----
$tmp = $stmts[1]->stmts[0];
$stmts[1]->stmts[0] = $stmts[2];
$stmts[2] = $tmp;
// Same test, but also removing first statement, triggering fallback
array_splice($stmts, 0, 1, []);
-----
<?php

function test() {
    call2(
        $foo
    );
}
call1(
    $bar
);
-----
<?php
    echo 1;
-----
$stmts[0] = new Stmt\Expression(
    new Expr\Assign(new Expr\Variable('a'), new Expr\Variable('b')));
-----
<?php
    $a = $b;
-----
<?php
echo$a;
-----
$stmts[0]->exprs[0] = new Expr\ConstFetch(new Node\Name('C'));
-----
<?php
echo C;
-----
<?php
function foo() {
    foo();
    /*
     * bar
     */
    baz();
}

{
    $x;
}
-----
$tmp = $stmts[0];
$stmts[0] = $stmts[1];
$stmts[1] = $tmp;
/* TODO This used to do two replacement operations, but with the node list diffing this is a
 * remove, keep, add (which probably makes more sense). As such, this currently triggers a
 * fallback. */
-----
<?php

$x;
function foo() {
    foo();
    /*
     * bar
     */
    baz();
}
-----
<?php
echo "${foo}bar";
echo "${foo['baz']}bar";
-----
$stmts[0]->exprs[0]->parts[0] = new Expr\Variable('bar');
$stmts[1]->exprs[0]->parts[0] = new Expr\Variable('bar');
-----
<?php
echo "{$bar}bar";
echo "{$bar}bar";
-----
<?php
[$a
,$b
,
,] = $b;
-----
/* Nothing */
-----
<?php
[$a
,$b
,
,] = $b;                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                         INDX( 	 tY‡             (   @	  è         t ÔÔ              D     € p     C     úÁQpkÕ CÔ|ÆÔ§ñŸ¢Ü<ÕúÁQpkÕh      a               a d d i n g P r o p e r t y T y p e . t e s t E     x b     C     N¡«RpkÕ CÔ|ÆÔÓT¢¢Ü<ÕN¡«RpkÕp      m               a n o n C l a s s e s . t e s t       F     x d     C     f°RpkÕ³›!%Õ³›!%Õf°RpkÕˆ      ˆ               a r r a y _ s p r e a d . t e s t     G     x h     C     ©*µRpkÕk~µ›!%Õk~µ›!%Õ©*µRpkÕ       B              a r r o w _ f u n c t i o n . t e s t H     h V     C     ÞQ¼RpkÕ CÔ|ÆÔÓT¢¢Ü<ÕÞQ¼RpkÕ       '	              
 b a s i c . t e s t   I     € j     C     lÁRpkÕ CÔ|ÆÔ·¤¢Ü<ÕlÁRpkÕh      a               b l o c k C o n v e r s i o n . t e s t       J     p \     C     3ÛÅRpkÕ CÔ|ÆÔ®§¢Ü<Õ3ÛÅRpkÕ       œ               c o m m e n t s . t e s t     K     € p     C     	 ÊRpkÕÕ¿›!%ÕÕ¿›!%Õ	 ÊRpkÕ       9               e m p t y L i s t I n s e r t i o n . t e s  L     h V     C     DÍRpkÕ CÔ|ÆÔãz©¢Ü<ÕDÍRpkÕ       &	              
 f i x u p . t e s t   M     p `     C      ÇÑRpkÕ CÔ|ÆÔãz©¢Ü<Õ ÇÑRpkÕX      T               i n l i n e H t m l . t e s t N     ˆ r     C     É²ÝRpkÕ CÔ|ÆÔ)Þ«¢Ü<ÕÉ²ÝRpkÕ       š               i n s e r t i o n O f N u l l a b l e . t e s t       O     x f     C     G<çRpkÕ CÔ|ÆÔ¿?®¢Ü<ÕG<çRpkÕ        ñ               l i s t I n s e r t i o n . t e s t   P      |     C     ìRpkÕ CÔ|Æ ¿?®¢Ü<ÕìRpkÕˆ      ˆ               l i s t I n s e r t i o n I n d e n t a t i o n . t e s t     Q     x b     C     NcîRpkÕ CÔ|ÆÔå¢°¢Ü<ÕNcîRpkÕ                     l i s t R e m o v a l . t e s t       R     x h     C     .(óRpkÕ CÔ|ÆÔH³¢Ü<Õ.(óRpkÕ0      +               m o d i f i e r C h a n g e . t e s t S     € j     C     ‹õRpkÕ CÔ|ÆÔ¬fµ¢Ü<Õ‹õRpkÕÀ       ¼                n o p C o m m e n t A t E n d . t e s t       T     x h     C     e±üRpkÕ CÔ|Æ Ê·¢Ü<Õe±üRpkÕ       9               r e m o v a l V i a N u l l . t e s t U     ˆ t     C     bÿRpkÕ CÔ|ÆÔÊ·¢Ü<ÕbÿRpkÕ¨       ¤                r e m o v i n g P r o p e r t y T y p e . t e s t     V     p `     C     vSpkÕ CÔ|ÆÔP,º¢Ü<ÕvSpkÕ¸       ¸                t r a i t A l i a s . t e s t                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                   Inserting into an empty list
-----
<?php
class
Test {}

interface
Test {}
-----
$stmts[0]->implements[] = new Node\Name('Iface');
$stmts[0]->implements[] = new Node\Name('Iface2');
$stmts[1]->extends[] = new Node\Name('Iface');
$stmts[1]->extends[] = new Node\Name('Iface2');
-----
<?php
class
Test implements Iface, Iface2 {}

interface
Test extends Iface, Iface2 {}
-----
<?php
function test
() {}

class Test {
    public function
    test
    () {}
}

function
() {};

fn()
=> 42;
-----
$stmts[0]->params[] = new Node\Param(new Node\Expr\Variable('a'));
$stmts[0]->params[] = new Node\Param(new Node\Expr\Variable('b'));
$stmts[1]->stmts[0]->params[] = new Node\Param(new Node\Expr\Variable('a'));
$stmts[1]->stmts[0]->params[] = new Node\Param(new Node\Expr\Variable('b'));
$stmts[2]->expr->params[] = new Node\Param(new Node\Expr\Variable('a'));
$stmts[2]->expr->params[] = new Node\Param(new Node\Expr\Variable('b'));
$stmts[2]->expr->uses[] = new Node\Expr\Variable('c');
$stmts[2]->expr->uses[] = new Node\Expr\Variable('d');
$stmts[3]->expr->params[] = new Node\Param(new Node\Expr\Variable('a'));
$stmts[3]->expr->params[] = new Node\Param(new Node\Expr\Variable('b'));
-----
<?php
function test
($a, $b) {}

class Test {
    public function
    test
    ($a, $b) {}
}

function
($a, $b) use($c, $d) {};

fn($a, $b)
=> 42;
-----
<?php
foo
();

$foo->
bar();

Foo
::bar ();

new
Foo
();

new class
()
extends Foo {};
-----
$stmts[0]->expr->args[] = new Node\Expr\Variable('a');
$stmts[0]->expr->args[] = new Node\Expr\Variable('b');
$stmts[1]->expr->args[] = new Node\Expr\Variable('a');
$stmts[1]->expr->args[] = new Node\Expr\Variable('b');
$stmts[2]->expr->args[] = new Node\Expr\Variable('a');
$stmts[2]->expr->args[] = new Node\Expr\Variable('b');
$stmts[3]->expr->args[] = new Node\Expr\Variable('a');
$stmts[3]->expr->args[] = new Node\Expr\Variable('b');
$stmts[4]->expr->args[] = new Node\Expr\Variable('a');
$stmts[4]->expr->args[] = new Node\Expr\Variable('b');
-----
<?php
foo
($a, $b);

$foo->
bar($a, $b);

Foo
::bar ($a, $b);

new
Foo
($a, $b);

new class
($a, $b)
extends Foo {};                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                       Fixup for precedence and some special syntax
-----
<?php
$a ** $b  *  $c;
$a  +  $b * $c;
$a * $b  +  $c;
$a  ?  $b  :  $c;
($a ** $b)  *  $c;
( $a ** $b )  *  $c;
!$a = $b;
-----
// Parens necessary
$stmts[0]->expr->left = new Expr\BinaryOp\Plus(new Expr\Variable('a'), new Expr\Variable('b'));
// The parens here are "correct", because add is left assoc
$stmts[1]->expr->right = new Expr\BinaryOp\Plus(new Expr\Variable('b'), new Expr\Variable('c'));
// No parens necessary
$stmts[2]->expr->left = new Expr\BinaryOp\Plus(new Expr\Variable('a'), new Expr\Variable('b'));
// Parens for RHS not strictly necessary due to assign speciality
$stmts[3]->expr->cond = new Expr\Assign(new Expr\Variable('a'), new Expr\Variable('b'));
$stmts[3]->expr->if = new Expr\Assign(new Expr\Variable('a'), new Expr\Variable('b'));
$stmts[3]->expr->else = new Expr\Assign(new Expr\Variable('a'), new Expr\Variable('b'));
// Already has parens
$stmts[4]->expr->left = new Expr\BinaryOp\Plus(new Expr\Variable('a'), new Expr\Variable('b'));
$stmts[5]->expr->left = new Expr\BinaryOp\Plus(new Expr\Variable('a'), new Expr\Variable('b'));
-----
<?php
($a + $b)  *  $c;
$a  +  ($b + $c);
$a + $b  +  $c;
($a = $b)  ?  $a = $b  :  ($a = $b);
($a + $b)  *  $c;
( $a + $b )  *  $c;
!$a = $b;
-----
<?php
foo ();
foo ();
$foo -> bar;
$foo -> bar;
$foo -> bar;
$foo -> bar;
$foo -> bar;
self :: $foo;
self :: $foo;
-----
$stmts[0]->expr->name = new Expr\Variable('a');
$stmts[1]->expr->name = new Expr\BinaryOp\Concat(new Expr\Variable('a'), new Expr\Variable('b'));
$stmts[2]->expr->var = new Expr\Variable('bar');
$stmts[3]->expr->var = new Expr\BinaryOp\Concat(new Expr\Variable('a'), new Expr\Variable('b'));
$stmts[4]->expr->name = new Node\Identifier('foo');
// In this case the braces are not strictly necessary. However, on PHP 5 they may be required
// depending on where the property fetch node itself occurs.
$stmts[5]->expr->name = new Expr\Variable('bar');
$stmts[6]->expr->name = new Expr\BinaryOp\Concat(new Expr\Variable('a'), new Expr\Variable('b'));
$stmts[7]->expr->name = new Node\VarLikeIdentifier('bar');
$stmts[8]->expr->name = new Expr\BinaryOp\Concat(new Expr\Variable('a'), new Expr\Variable('b'));
-----
<?php
$a ();
($a . $b) ();
$bar -> bar;
($a . $b) -> bar;
$foo -> foo;
$foo -> {$bar};
$foo -> {$a . $b};
self :: $bar;
self :: ${$a . $b};                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                          Insertion of a nullable node
-----
<?php

// TODO: The result spacing isn't always optimal. We may want to skip whitespace in some cases.

function
foo(
$x,
&$y
)
{}

$foo
[
];

[
    $value
];

function
()
{};

$x
?
:
$y;

yield
$v  ;
yield  ;

break
;
continue
;
return
;

class
X
{
    public
    function y()
    {}

    private
        $x
    ;
}

foreach (
    $x
    as
    $y
) {}

static
$var
;

try {
} catch (X
$y) {
}

if ($cond) { // Foo
} elseif ($cond2) { // Bar
}
-----
$stmts[0]->returnType = new Node\Name('Foo');
$stmts[0]->params[0]->type = new Node\Identifier('int');
$stmts[0]->params[1]->type = new Node\Identifier('array');
$stmts[0]->params[1]->default = new Expr\ConstFetch(new Node\Name('null'));
$stmts[1]->expr->dim = new Expr\Variable('a');
$stmts[2]->expr->items[0]->key = new Scalar\String_('X');
$stmts[3]->expr->returnType = new Node\Name('Bar');
$stmts[4]->expr->if = new Expr\Variable('z');
$stmts[5]->expr->key = new Expr\Variable('k');
$stmts[6]->expr->value = new Expr\Variable('v');
$stmts[7]->num = new Scalar\LNumber(2);
$stmts[8]->num = new Scalar\LNumber(2);
$stmts[9]->expr = new Expr\Variable('x');
$stmts[10]->extends = new Node\Name\FullyQualified('Bar');
$stmts[10]->stmts[0]->returnType = new Node\Name('Y');
$stmts[10]->stmts[1]->props[0]->default = new Scalar\DNumber(42.0);
$stmts[11]->keyVar = new Expr\Variable('z');
$stmts[12]->vars[0]->default = new Scalar\String_('abc');
$stmts[13]->finally = new Stmt\Finally_([]);
$stmts[14]->else = new Stmt\Else_([]);
-----
<?php

// TODO: The result spacing isn't always optimal. We may want to skip whitespace in some cases.

function
foo(
int $x,
array &$y = null
) : Foo
{}

$foo
[$a
];

[
    'X' => $value
];

function
() : Bar
{};

$x
? $z
:
$y;

yield
$k => $v  ;
yield $v  ;

break 2
;
continue 2
;
return $x
;

class
X extends \Bar
{
    public
    function y() : Y
    {}

    private
        $x = 42.0
    ;
}

foreach (
    $x
    as
    $z => $y
) {}

static
$var = 'abc'
;

try {
} catch (X
$y) {
} finally {
}

if ($cond) { // Foo
} elseif ($cond2) { // Bar
} else {
}
-----
<?php

namespace
{ echo 42; }
-----
$stmts[0]->name = new Node\Name('Foo');
-----
<?php

namespace Foo
{ echo 42; }                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      Insertion into list nodes
-----
<?php
$foo;

$bar;
-----
$stmts[] = new Stmt\Expression(new Expr\Variable('baz'));
-----
<?php
$foo;

$bar;
$baz;
-----
<?php

function test() {
    $foo;

    $bar;
}
-----
$stmts[0]->stmts[] = new Stmt\Expression(new Expr\Variable('baz'));
-----
<?php

function test() {
    $foo;

    $bar;
    $baz;
}
-----
<?php

function test(Foo     $param1) {}
-----
$stmts[0]->params[] = new Node\Param(new Expr\Variable('param2'));
-----
<?php

function test(Foo     $param1, $param2) {}
-----
<?php

try {
    /* stuff */
} catch
(Foo $x) {}
-----
$stmts[0]->catches[0]->types[] = new Node\Name('Bar');
-----
<?php

try {
    /* stuff */
} catch
(Foo|Bar $x) {}
-----
<?php

function test(Foo     $param1) {}
-----
array_unshift($stmts[0]->params, new Node\Param(new Expr\Variable('param0')));
-----
<?php

function test($param0, Foo     $param1) {}
-----
<?php

function test() {}
-----
$stmts[0]->params[] = new Node\Param(new Expr\Variable('param0'));
/* Insertion into empty list not handled yet */
-----
<?php

function test($param0)
{
}
-----
<?php

if ($cond) {
} elseif ($cond2) {
}
-----
$stmts[0]->elseifs[] = new Stmt\ElseIf_(new Expr\Variable('cond3'), []);
-----
<?php

if ($cond) {
} elseif ($cond2) {
} elseif ($cond3) {
}
-----
<?php

try {
} catch (Foo $foo) {
}
-----
$stmts[0]->catches[] = new Stmt\Catch_([new Node\Name('Bar')], new Expr\Variable('bar'), []);
-----
<?php

try {
} catch (Foo $foo) {
} catch (Bar $bar) {
}
-----
<?php
$foo; $bar;
-----
$node = new Stmt\Expression(new Expr\Variable('baz'));
$node->setAttribute('comments', [new Comment('// Test')]);
$stmts[] = $node;
-----
<?php
$foo; $bar;
// Test
$baz;
-----
<?php
function test() {
    $foo; $bar;
}
-----
$node = new Stmt\Expression(new Expr\Variable('baz'));
$node->setAttribute('comments', [new Comment('// Test'), new Comment('// Test 2')]);
$stmts[0]->stmts[] = $node;
-----
<?php
function test() {
    $foo; $bar;
    // Test
    // Test 2
    $baz;
}
-----
<?php
namespace
Foo;
-----
$stmts[0]->name->parts[0] = 'Xyz';
-----
<?php
namespace
Xyz;
-----
<?php
function test() {
    $foo; $bar;
}
-----
$node = new Stmt\Expression(new Expr\Variable('baz'));
array_unshift($stmts[0]->stmts, $node);
-----
<?php
function test() {
    $baz;
    $foo; $bar;
}
-----
<?php
function test() {
    $foo; $bar;
}
-----
$node = new Stmt\Expression(new Expr\Variable('baz'));
$node->setAttribute('comments', [new Comment('// Test')]);
array_unshift($stmts[0]->stmts, $node);
-----
<?php
function test() {
    // Test
    $baz;
    $foo; $bar;
}
-----
<?php
function test() {

    // Foo bar
    $foo; $bar;
}
-----
$node = new Stmt\Expression(new Expr\Variable('baz'));
$node->setAttribute('comments', [new Comment('// Test')]);
array_unshift($stmts[0]->stmts, $node);
-----
<?php
function test() {

    // Test
    $baz;
    // Foo bar
    $foo; $bar;
}
-----
<?php
function test() {

    // Foo bar
    $foo; $bar;
}
-----
$node = new Stmt\Expression(new Expr\Variable('baz'));
$node->setAttribute('comments', [new Comment('// Test')]);
array_unshift($stmts[0]->stmts, $node);
$stmts[0]->stmts[1]->setAttribute('comments', [new Comment('// Bar foo')]);
-----
<?php
function test() {

    // Test
    $baz;
    // Bar foo
    $foo; $bar;
}
-----
<?php
function test() {

    // Foo bar
    $foo; $bar;
}
-----
$node = new Stmt\Expression(new Expr\Variable('baz'));
$node->setAttribute('comments', [new Comment('// Test')]);
array_unshift($stmts[0]->stmts, $node);
$stmts[0]->stmts[1]->setAttribute('comments', []);
-----
<?php
function test() {

    // Test
    $baz;
    $foo; $bar;
}
-----
<?php
function test() {

    // Foo bar
    $foo; $bar;
}
-----
array_unshift(
    $stmts[0]->stmts,
    new Stmt\Expression(new Expr\Variable('a')),
    new Stmt\Expression(new Expr\Variable('b')));
-----
<?php
function test() {

    $a;
    $b;
    // Foo bar
    $foo; $bar;
}
-----
<?php
function test() {}
-----
/* Insertion into empty list not handled yet */
$stmts[0]->stmts = [
    new Stmt\Expression(new Expr\Variable('a')),
    new Stmt\Expression(new Expr\Variable('b')),
];
-----
<?php
function test()
{
    $a;
    $b;
}
-----
<?php
$array = [
    1,
    2,
    3,
];
-----
array_unshift($stmts[0]->expr->expr->items, new Expr\ArrayItem(new Scalar\LNumber(42)));
$stmts[0]->expr->expr->items[] = new Expr\ArrayItem(new Scalar\LNumber(24));
-----
<?php
$array = [
    42,
    1,
    2,
    3,
    24,
];
-----
<?php
$array = [
    1, 2,
    3,
];
-----
$stmts[0]->expr->expr->items[] = new Expr\ArrayItem(new Scalar\LNumber(24));
-----
<?php
$array = [
    1, 2,
    3, 24,
];                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                               Removing subnodes by setting them to null
-----
<?php
function
foo (
    Bar $foo
        = null,
    Foo $bar) : baz
{}

function
()
: int