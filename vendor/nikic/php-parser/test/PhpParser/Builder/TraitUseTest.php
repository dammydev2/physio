   if($classes === null){
                                            $classes = $this->getClasses();
                                        }
                                        if(isset($classes[$id_start_ci])){
                                            $id_start = $classes[$id_start_ci];
                                        }
                                        if($id_start[0] !== '\\'){
                                            $id_start = $nsf . '\\' . $id_start;
                                        }
                                    }
                                } else {
                                    if($constants === null){
                                        $constants = $this->getConstants();
                                    }
                                    if(isset($constants[$id_start])){
                                        $id_start = $constants[$id_start];
                                    }
                                }
                            }
                            $code .= $id_start . $id_name;
                            $state = $lastState;
                            $i--;//reprocess last token
                    }
                    break;
                case 'anonymous':
                    switch ($token[0]) {
                        case T_NS_SEPARATOR:
                        case T_STRING:
                            $id_start = $token[1];
                            $id_start