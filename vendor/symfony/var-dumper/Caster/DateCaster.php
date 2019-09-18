value)];
                $value = $this->utf8Encode($type);
                break;
        }

        $this->line .= $this->style($style, $value, $attr);

        $this->endValue($cursor);
    }

    /**
     * {@inheritdoc}
     */
    public function dumpString(Cursor $cursor, $str, $bin, $cut)
    {
        $this->dumpKey($cursor);
        $attr = $cursor->attr;

        if ($bin) {
            $str = $this->utf8Encode($str);
        }
        if ('' === $str) {
            $this->line .= '""';
            $this->endValue($cursor);
        } else {
            $attr += [
                'length' => 0 <= $cut ? mb_strlen($str, 'UTF-8') + $cut : 0,
                'binary' => $bin,
            ];
            $str = explode("\n", $str);
            if (isset($str[1]) && !isset($str[2]) && !isset($str[1][0])) {
                unset($str[1]);
                $str[0] .= "\n";
            }
            $m = \count($str) - 1;
            $i = $lineCut = 0;

            if (self::DUMP_STRING_LENGTH & $this->flags) {
                $this->line .= '('.$attr['length'].') ';
            }
            if ($bin) {
                $this->line .= 'b';
            }

            if ($m) {
                $this->line .= '"""';
                $this->dumpLine($cursor->depth);
            } else {
                $this->line .= '"';
            }

            foreach ($str as $str) {
                if ($i < $m) {
                    $str .= "\n";
                }
                if (0 < $this->maxStringWidth && $this->maxStringWidth < $len = mb_strlen($str, 'UTF-8')) {
                    $str = mb_substr($str, 0, $this->maxStringWidth, 'UTF-8');
                    $lineCut = $len - $this->maxStringWidth;
                }
                if ($m && 0 < $cursor->depth) {
                    $this->line .= $this->indentPad;
                }
                if ('' !== $str) {
                    $this->line .= $this->style('str', $str, $attr);
                }
                if ($i++ == $m) {
                    if ($m) {
                        if ('' !== $str) {
                            $this->dumpLine($cursor->depth);
                            if (0 < $cursor->depth) {
                                $this->line .= $this->indentPad;
                            }
                        }
                        $this->line .= '"""';
                    } else {
                        $this->line .= '"';
                    }
                    if ($cut < 0) {
                        $this->line .= '…';
                        $lineCut = 0;
                    } elseif ($cut) {
                        $lineCut += $cut;
                    }
                }
                if ($lineCut) {
                    $this->line .= '…'.$lineCut;
                    $lineCut = 0;
                }

                if ($i > $m) {
                    $this->endValue($cursor);
                } else {
                    $this->dumpLine($cursor->depth);
                }
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function enterHash(Cursor $cursor, $type, $class, $hasChild)
    {
        $this->dumpKey($cursor);

        if ($this->collapseNextHash) {
            $cursor->skipChildren = true;
            $this->collapseNextHash = $hasChild = false;
        }

        $class = $this->utf8Encode($class);
        if (Cursor::HASH_OBJECT === $type) {
            $prefix = $class && 'stdClass' !== $class ? $this->style('note', $class).' {' : '{';
        } elseif (Cursor::HASH_RESOURCE === $type) {
            $prefix = $this->style('note', $class.' resource').($hasChild ? ' {' : ' ');
        } else {
            $prefix = $class && !(self::DUMP_LIGHT_ARRAY & $this->flags) ? $this->style('note', 'array:'.$class).' [' : '[';
        }

        if ($cursor->softRefCount || 0 < $cursor->softRefHandle) {
            $prefix .= $this->style('ref', (Cursor::HASH_RESOURCE === $type ? '@' : '#').(0 < $cursor->softRefHandle ? $cursor->softRefHandle : $cursor->softRefTo), ['count' => $cursor->softRefCount]);
        } elseif ($cursor->hardRefTo && !$cursor->refIndex && $class) {
            $prefix .= $this->style('ref', '&'.$cursor->hardRefTo, ['count' => $cursor->hardRefCount]);
        } elseif (!$hasChild && Cursor::HASH_RESOURCE === $type) {
            $prefix = substr($prefix, 0, -1);
        }

        $this->line .= $prefix;

        if ($hasChild) {
            $this->dumpLine($cursor->depth);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function leaveHash(Cursor $