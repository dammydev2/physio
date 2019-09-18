a-z])/', function ($match) {
            [$code] = $match;

            static $replacements = null;

            if ($replacements === null) {
                $replacements = [
                    'OD' => 'd',
                    'OM' => 'M',
                    'OY' => 'Y',
                    'OH' => 'G',
                    'Oh' => 'g',
                    'Om' => 'i',
                    'Os' => 's',
                    'D' => 'd',
                    'DD' => 'd',
                    'Do' => 'd',
                    'd' => '!',
                    'dd' => '!',
                    'ddd' => 'D',
                    'dddd' => 'D',
                    'DDD' => 'z',
                    'DDDD' => 'z',
                    'DDDo' => 'z',
                    'e' => '!',
                    'E' => '!',
                    'H' => 'G',
                    'HH' => 'H',
                    'h' => 'g',
                    'hh' => 'h',
                    'k' => 'G',
                    'kk' => 'G',
                    'hmm' => 'gi',
                    'hmmss' => 'gis',
                    'Hmm' => 'Gi',
                    'Hmmss