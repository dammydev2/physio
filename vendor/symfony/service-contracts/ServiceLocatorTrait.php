)) {
                foreach ($metadata['notes'] as $note) {
                    if (!isset($note['content'])) {
                        continue;
                    }

                    $n = $translation->appendChild($dom->createElement('note'));
                    $n->appendChild($dom->createTextNode($note['content']));

                    if (isset($note['priority'])) {
                        $n->setAttribute('priority', $note['priority']);
                    }

                    if (isset($note['from'])) {
                        $n->setAttribute('from', $note['from']);
                    }
                }
            }

            $xliffBody->appendChild($translation);
        }

        return $dom->saveXML();
    }

    private function dumpXliff2($defaultLocale, MessageCatalogue $messages, $domain, array $options = [])
    {
        $dom = new \DOMDocument('1.0', 'utf-8');
        $dom->formatOutput = true;

        $xliff = $dom->appendChild($dom->createElement('xliff'));
        $xliff->setAttribute('xmlns', 'urn:oasis:names:tc:xliff:document:2.0');
        $xliff->setAttribute('version', '2.0');
        $xliff->setAttribute('srcLang', str_replace('_', '-', $defaultLocale));
        $xliff->setAttribute('trgLang', str_replace('_', '-', $messages->getLocale()));

        $xliffFile = $xliff->appendChild($dom->createElement('file'));
        if (MessageCatalogue::INTL_DOMAIN_SUFFIX === substr($domain, -($suffixLength = \strlen(MessageCatalogue::INTL_DOMAIN_SUFFIX)))) {
            $xliffFile->setAttribute('id', substr($domain, 0, -$suffixLength).'.'.$messages->getLocale());
        } else {
            $xliffFile->setAttribute('id', $domain.'.'.$messages->getLocale());
        }

        foreach ($messages->all($domain) as $source => $target) {
            $translation = $dom->createElement('unit');
            $translation->setAttribute('id', strtr(substr(base64_encode(hash('sha256', $source, true)), 0, 7), '/+', '._'));
            $name = $source;
            if (\strlen($source) > 80) {
                $name = substr(md5($source), -7);
            }
            $translation->setAttribute('name', $name);
            $metadata = $messages->getMetadata($source, $domain);

            // Add notes section
            if ($this->hasMetadataArrayInfo('notes', $metadata)) {
                $notesElement = $dom->createElement('notes');
                foreach ($metadata['notes'] as $note) {
                    $n = $dom->createElement('note');
                    $n->appendChild($dom->createTextNode(isset($note['content']) ? $note['content'] : ''));
                    unset($note['content']);

                    foreach ($note as $name => $value) {
                        $n->setAttribute($name, $value);
                    }
                    $notesElement->appendChild($n);
                }
                $translation->appendChild($notesElement);
            }

            $segment = $translation->appendChild($dom->createElement('segment'));

            $s = $segment->appendChild($dom->createElement('source'));
            $s->appendChild($dom->createTextNode($source));

            // Does the target contain characters requiring a CDATA section?
            $text = 1 === preg_match('/[&<>]/', $target) ? $dom->createCDATASection($target) : $dom->createTextNode($target);

            $targetElement = $dom->createElement('target');
            if ($this->hasMetadataArrayInfo('target-attributes', $metadata)) {
  