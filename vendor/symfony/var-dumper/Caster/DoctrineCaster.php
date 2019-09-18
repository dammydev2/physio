 ($attr['collapse']) {
                                $this->collapseNextHash = true;
                            } else {
                                $this->expandNextHash = true;
                            }
                        }

                        $this->line .= $bin.$this->style($style, $key[1], $attr).(isset($attr['separator']) ? $attr['separator'] : ': ');
                    } else {
                        // This case should not happen
                        $this->line .= '-'.$bin.'"'.$this->style('private', $key, ['class' => '']).'": ';
                    }
                    break;
            }

            if ($cursor->hardRefTo) {
                $this->line .= $this->style('ref', '&'.($cursor->hardRefCount ? $cursor->hardRefTo : ''), ['count' => $cursor->hardRefCount]).' ';
            }
        }
    }

    /**
     * Decorates a value with some style.
     *
     * @param string $style The type of style being applied
     * @param string $value The value being styled
     * @param array  $attr  Optional context information
     *
     * @return string The value with style decoration
     */
    protected function style($style, $value, $attr = [])
    {
        if (null === $this->colors) {
            $this->colors = $this->supportsColors();
        }

        if (isset($attr['ellipsis'], $attr['ellipsis-type'])) {
            $prefix = substr($value, 0, -$attr['ellipsis']);
            if ('cli' === \PHP_SAPI && 'path' === $attr['ellipsis-type'] && isset($_SERVER[$pwd = '\\' === \DIRECTORY_SEPARATOR ? 'CD' : 'PWD']) && 0 === strpos($prefix, $_SERVER[$pwd])) {
                $pre