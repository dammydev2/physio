      case T_DOC_COMMENT:
                            break 2;
                        case T_CLASS:
                            $state = 'structure';
                            $structIgnore = true;
                            break;
                        default:
                            $state = 'start';
                    }
                    break;
                case 'invoke':
                    switch ($token[0]) {
                        case T_WHITESPACE:
                        case T_COMMENT:
                        case T_DOC_COMMENT:
                            break 2;
                        default:
                            $state = 'start';
                    }
                    break;
                case 'before_structure':
                    if ($token[0] == T_STRING) {
                        $structName = $token[1];
                        $state = 'structure';
                    }
                    break;
                case 'structure':
                    switch ($token[0]) {
                        case '{':
                        case T_CURLY_OPEN:
                        case T_DOLLAR_OPEN_CURLY_BRACES:
                        case T_STRING_VARNAME:
                            $open++;
                            break;
                        case '}':
                            if (--$open == 0) {
                                if(!$structIgnore){
                                    $structures[] = array(
                                        'type' => $structType,
                                        'name' => $structName,
                                        'start' => $startLine,
                                        'end' => $endLine,
                                    );
                                }
                                $structIgnore = false;
                                $state = 'start';
                            }
                            break;
                        default:
                            if (is_array($token)) {
                                $endLine = $token[2];
                            }
                    }
                    break;
            }
        }

        static::$classes[$key] = $classes;
        static::$functions[$key] = $functions;
     