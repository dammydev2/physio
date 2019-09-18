ed function inlineEmphasis($Excerpt)
    {
        if ( ! isset($Excerpt['text'][1]))
        {
            return;
        }

        $marker = $Excerpt['text'][0];

        if ($Excerpt['text'][1] === $marker and preg_match($this->StrongRegex[$marker], $Excerpt['text'], $matches))
        {
            $emphasis = 'strong';
        }
        elseif (preg_match($this->EmRegex[$marker], $Excerpt['text'], $matches))
        {
            $emphasis = 'em';
        }
        else
        {
            return;
        }

        return array(
            'extent' => strlen($matches[0]),
            'element' => array(
                'name' => $emphasis,
                'handler' => 'line',
                'text' => $matches[1],
            ),
        );
    }

    protected function inlineEscapeSequence($Excerpt)
    {
        if (isset($Excerpt['text'][1]) and in_array($Excerpt['text'][1], $this->specialCharacters))
        {
            return array(
                'markup' => $Excerpt['text'][1],
                'extent' => 2,
            );
        }
    }

    protected function inlineImage($Excerpt)
    {
        if ( ! isset($Excerpt['text'][1]) or $Excerpt['text'][1] !== '[')
        {
            return;
        }

        $Excerpt['text']= substr($Excerpt['text'], 1);

        $Link = $this->inlineLink($Excerpt);

        if ($Link === null)
        {
            return;
        }

        $Inline = array(
            'extent' => $Link['extent'] + 1,
            'element' => array(
                'name' => 'img',
                'attributes' => array(
                    'src' => $Link['element']['attributes']['href'],
                    'alt' => $Link['element']['text'],
                ),
            ),
        );

        $Inline['element']['attributes'] += $Link['element']['attributes'];

        unset($Inline['element']['attributes']['href']);

        return $Inline;
    }

    protected function inlineLink($Excerpt)
    {
        $Element = array(
            'name' => 'a',
            'handler' => 'line',
            'nonNestables' => array('Url', 'Link'),
            'text' => null,
            'attributes' => array(
                'href' => null,
                'title' => null,
            ),
        );

        $extent = 0;

        $remainder = $Excerpt['text'];

        if (preg_match('/\[((?:[^][]++|(?R))*+)\]/', $remainder, $matches))
        {
            $Element['text'] = $matches[1];

            $extent += strlen($matches[0]);

            $remainder = substr($remainder, $extent);
        }
        else
        {
            return;
        }

        if (preg_match('/^[(]\s*+((?:[^ ()]++|[(][^ )]+[)])++)(?:[ ]+("[^"]*"|\'[^\']*\'))?\s*[)]/', $remainder, $matches))
        {
            $Element['attributes']['href'] = $matches[1];

            if (isset($matches[2]))
            {
                $Element['attributes']['title'] = substr($matches[2], 1, - 1);
            }

            $extent += strlen($matches[0]);
        }
        else
        {
            if (preg_match('/^\s*\[(.*?)\]/', $remainder, $matches))
            {
                $definition = strlen($matches[1]) ? $matches[1] : $Element['text'];
                $definition = strtolower($definition);

                $extent += strlen($matches[0]);
            }
            else
            {
                $definition = strtolower($Element['text']);
            }

            if ( ! isset($this->DefinitionData['Reference'][$definition]))
            {
                return;
            }

            $Definition = $this->DefinitionData['Reference'][$definition];

            $Element['attributes']['href'] = $Definition['url'];
            $Element['attributes']['title'] = $Definition['title'];
        }

        return array(
            'extent' => $extent,
            'element' => $Element,
        );
    }

    protected function inlineMarkup($Excerpt)
    {
        if ($this->markupEscaped or $this->safeMode or strpos($Excerpt['text'], '>') === false)
        {
            return;
        }

        if ($Excerpt['text'][1] === '/' and preg_match('/^<\/\w[\w-]*[ ]*>/s', $Excerpt['text'], $matches))
        {
            return array(
                'markup' => $matches[0],
                'extent' => strlen($matches[0]),
            );
        }

        if ($Excerpt['text'][1] === '!' and preg_match('/^<!---?[^>-](?:-?[^-])*-->/s', $Excerpt['text'], $matches))
        {
            return array(
                'markup' => $matches[0],
                'extent' => strlen($matches[0]),
            );
        }

        if ($Excerpt['text'][1] !== ' ' and preg_match('/^<\w[\w-]*(?:[ ]*'.$this->regexHtmlAttribute.')*[ ]*\/?>/s', $Excerpt['text'], $matches))
        {
            return array(
                'markup' => $matches[0],
                'extent' => strlen($matches[0]),
            );
        }
    }

    protected function inlineSpecialCharacter($Excerpt)
    {
        if ($Excerpt['text'][0] === '&' and ! preg_match('/^&#?\w+;/', $Excerpt['text']))
        {
            return array(
                'markup' => '&amp;',
                'extent' => 1,
            );
        }

        $SpecialCharacter = array('>' => 'gt', '<' => 'lt', '"' => 'quot');

        if (isset($SpecialCharacter[$Excerpt['text'][0]]))
        {
            return array(
                'markup' => '&'.$SpecialCharacter[$Excerpt['text'][0]].';',
                'extent' => 1,
            );
        }
    }

    protected function inlineStrikethrough($Excerpt)
    {
        if ( ! isset($Excerpt['text'][1]))
        {
            return;
        }

        if ($Excerpt['text'][1] === '~' and preg_match('/^~~(?=\S)(.+?)(?<=\S)~~/', $Excer