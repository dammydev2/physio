                       break;
                        case T_INSTANCEOF:
                            $code .= $token[1];
                            $context = 'instanceof';
                            $state = 'id_start';
                            $lastState = 'closure';
                            break;
                        case T_OBJECT_OPERATOR:
                        case T_DOUBLE_COLON:
                            $code .= $token[1];
                            $lastState = 'closure';
                            $state = 'ignore_next';
                            break;
                        case T_FUNCTION:
                            $code .= $token[1];
                            $state = 'closure_args';
                            break;
                        case T_TRAIT_C:
                            if ($_trait === null) {
                                $startLine = $this->getStartLine();
                                $endLine = $this->getEndLine();
                                $structures = $this->getStructures();

                                $_trait = '';

                                foreach ($structures as &$struct) {
                                    if ($struct['type'] === 'trait' &&
                                        $struct['start'] <= $startLine &&
                                        $struct['end'] >= $endLine
                                    ) {
                                     