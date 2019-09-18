1];
                            $state = 'anonymous';
                            break;
                        default:
                            $i--;//reprocess last
                            $state = 'id_name';
                    }
                    break;
                case 'id_name':
                    switch ($token[0]){
                        case T_NS_SEPARATOR:
                        case T_STRING:
                            $id_name .= $token[1];
                            break;
                        case T_WHITESPACE:
                        case T_COMMENT:
                        case T_DOC_COMMENT:
                            $id_name .= $token[1];
                            break;
                        case '(':
                            if($context === 'new' || false !== strpos($id_name, '\\')){
                                if($id_start !== '\\'){
                                    if ($classes === null) {
                                        $classes = $this->getClasses();
                                    }
                                    if (isset($classes[$id_start_ci])) {
                                        $id_start = $classes[$id_start_ci];
                                    }
                                    if($id_start[0] !== '\\'){
                                        $id_start = $nsf . '\\' . $id_start;
                                    }
                                }
                            } else {
                                if($id_start !== '\\'){
                                    if($functions === null){
                                        $functions = $this->getFunctions();
                                    }
                                    if(isset($functions[$id_start_ci])){
                                        $id_start = $functions[$id_start_ci];
                                    }
                                }
                            }
                            $code .= $id_start . $id_name . '(';
                            $state = $lastState;
                            break;
                        case T_VARIABLE:
                        case T_DOUBLE_COLON:
                            if($id_start !== '\\') {
                                if($id_start_ci === 'self' || $id_start_ci === 'static' || $id_start_ci === 'parent'){
                                    $isUsingScope = true;
                                } elseif (!($php7 && in_array($id_start_ci, $php7_types))){
                                    if ($classes === null) {
                                        $classes = $this->getClasses();
                                    }
                                    if (isset($classes[$id_start_ci])) {
                                        $id_start = $classes[$id_start_ci];
                                    }
                                    if($id_start[0] !== '\\'){
                                        $id_start = $nsf . '\\' . $id_start;
                                    }
                                }
                            }
                            $code .= $id_start . $id_name . $token[1];
                            $state = $token[0] === T_DOUBLE_COLON ? 'ignore_next' : $lastState;
                            break;
                        default:
                            if($id_start !== '\\'){
                                if($context === 'use' ||
                                    $context === 'instanceof