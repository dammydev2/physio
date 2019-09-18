              $paths[$i] = \substr($paths[$i], 7);
                $paths[$i] = \str_replace('/', \DIRECTORY_SEPARATOR, $paths[$i]);
            }
            $paths[$i] = \explode(\DIRECTORY_SEPARATOR, $paths[$i]);

            if (empty($paths[$i][0])) {
                $paths[$i][0] = \DIRECTORY_SEPARATOR;
            }
        }

        $done = false;
        $max  = \count($paths);

        while (!$done) {
            for ($i = 0; $i < $max - 1; $i++) {
                if (!isset($paths[$i][0]) ||
                    !isset($paths[$i + 1][0]) ||
                    $paths[$i][0] != $paths[$i + 1][0]) {
                    $done = true;

                    break;
                }
            }

            if (!$done) {
                $commonPath .= $paths[0][0];

                if ($path