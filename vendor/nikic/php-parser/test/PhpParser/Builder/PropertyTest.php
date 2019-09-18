 .= '{';
                            $state = 'closure';
                            $open++;
                            break;
                        default:
                            $code .= is_array($token) ? $token[1] : $token;
                            break;
                    }
                    break;
                case 'closure':
                    switch ($token[0]){
                        case T_CURLY_OPEN:
                        case T_DOLLAR_OPEN_CURLY_BRACES:
                        case T_STRING_VARNAME:
                        case '{':
                            $code .= '{';
                            $open++;
                            break;
                        case '}':
                            $code .= '}';
                            if(--$open === 0){
                                break 3;
                            } elseif ($inside_anonymous) {
                                $inside_anonymous = !($open === $anonymous_mark);
                            }
                            break;
                        case T_LINE:
                            $code .= $token[2] - $line + $lineAdd;
                            break;
                        case T_FILE:
                            $code .= $_file;
                            break;
                        case T_DIR:
                            $code .= $_dir;
                            break;
                        case T_NS_C:
                            $code .= $_namespace;
                            break;
                        case T_CLASS_C:
                            $code .= $_class;
                            break;
                        case T_FUNC_C:
                            $code .= $_function;
                            break;
                        case T_METHOD_C:
                            $code .= $_method;
                            break;
                        case T_COMMENT:
                            if (substr($token[1], 0, 8) === '#trackme') {
                                $timestamp = time();
                                $code .= '/**' . PHP_EOL;
                                $code .= '* Date      : ' . date(DATE_W3C, $timestamp) . PHP_EOL;
                                $code .= '* Timestamp : ' . $timestamp . PHP_EOL;
                                $code .= '* Line      : ' . ($line + 1) . PHP_EOL;
                                $code .= '* File      : ' . $_file . PHP_EOL . '*/' . PHP_EOL;
                                $lineAdd += 5;
                            } else {
                                $code .= $token[1];
                            }
                            break;
                        case T_VARIABLE:
                            if($token[1] == '$this' && !$inside_anonymous){
                                $isUsingThisObject = true;
                            }
                            $code .= $token[1];
                            break;
                        case T_STATIC:
                            $isUsingScope = true;
                            $code .= $token[1];
                            break;
                        case T_NS_SEPARATOR:
                        case T_STRING:
                            $id_start = $token[1];
                            $id_start_ci = strtolower($id_start);
                            $id_name = '';
                            $context = 'root';
                            $state = 'id_name';
                            $lastState = 'closure';
                            break 2;
                        case T_NEW:
                            $code .= $token[1];
                            $context = 'new';
                            $state = 'id_start';
                            $lastState = 'closure';
                            break 2;
                       