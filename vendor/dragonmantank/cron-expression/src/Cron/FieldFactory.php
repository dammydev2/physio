e;
                }

                $markup .= ' '.$name.'="'.self::escape($value).'"';
            }
        }

        if (isset($Element['text']))
        {
            $markup .= '>';

            if (!isset($Element['nonNestables'])) 
            {
                $Element['nonNestables'] = array();
            }

            if (isset($Element['handler']))
            {
                $markup .= $this->{$Element['handler']}($Element['text'], $Element['nonNestables']);
            }
            else
            {
                $markup .= self::escape($Element['text'], true);
            }

            $markup .= '</'.$Element['name'].'>';
        }
        else
        {
            $markup .= ' />';
        }

        return $markup;
    }

    protected function elements(array $Elements)
    {
        $markup = '';

        foreach ($Elements as $Element)
        {
            $markup .= "\n" . $this->element($Element);
        }

        $markup .= "\n";

        return $markup;
    }

    # ~

    protected function li($lines)
    {
        $markup = $this->lines($lines);

        $trimmedMarkup = trim($markup);

        if ( ! in_array('', $lines) and substr($trimmedMarkup, 0, 3) === '<p>')
        {
            $markup = $trimmedMarkup;
            $markup = substr($markup, 3);

            $position = strpos($markup, "</p>");

            $markup = substr_replace($markup, '', $position, 4);
        }

        return $markup;
    }

    #
    # Deprecated Methods
    #

 