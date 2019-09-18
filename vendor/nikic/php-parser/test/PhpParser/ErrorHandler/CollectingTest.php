    break;
                        case T_NEW:
                            $state = 'new';
                            break;
                        case T_OBJECT_OPERATOR:
                        case T_DOUBLE_COLON:
                            $state = 'invoke';
                            break;
                    }
                    break;
                case 'use':
                    switch ($token[0]) {
                        case T_FUNCTION:
                            $isFunc = true;
                            break;
                        case T_CONST:
                            $isConst = true;
                            break;
                        case T_NS_SEPARATOR:
                            $name .= $token[1];
