            return $generator->currencyCode;
                };
            case 'url':
            case 'website':
                return function () use ($generator) {
                    return $generator->url;
                };
            case 'company':
            case 'companyname':
            case 'employer':
                return function () use ($generator) {
                    return $generator->company;
                };
            case 'title':
                if ($size !== null && $size <= 10) {
                    return function () use ($generator) {
                        return $generator->title;
                    };
                }

                return function () use ($generator) {
                    return $generator->sentence;
                };
            case 'body':
    