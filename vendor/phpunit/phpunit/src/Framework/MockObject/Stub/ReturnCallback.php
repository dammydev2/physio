>getAttribute('bootstrap')
            );
        }

        if ($root->hasAttribute('convertDeprecationsToExceptions')) {
            $result['convertDeprecationsToExceptions'] = $this->getBoolean(
                (string) $root->getAttribute('convertDeprecationsToExceptions'),
                true
            );
        }

        if ($root->hasAttribute('convertErrorsToExceptions')) {
            $result['convertErrorsToExceptions'] = $this->getBoolean(
                (string) $root->getAttribute('convertErrorsToExceptions'),
                true
            );
        }

        if ($root->hasAttribute('convertNoticesToExceptions')) {
            $result['convertNoticesToExceptions'] = $this->getBoolean(
                (string) $root->getAttribute('convertNoticesToExceptions'),
                true
            );
        }

        if ($root->hasAttribute('convertWarningsToExceptions')) {
            $result['convertWarningsToExceptions'] = $this->getBoolean(
                (string) $root->getAttribute('convertWarningsToExceptions'),
                true
            );
        }

        if ($root->hasAttribute('forceCoversAnnotation')) {
            $result['forceCoversAnnotation'] = $this->getBoolean(
                (string) $root->getAttribute('forceCoversAnnotation'),
                false
            );
        }

        if ($root->hasAttribute('