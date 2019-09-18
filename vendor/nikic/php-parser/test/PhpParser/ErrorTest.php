time(true);

foreach (new RecursiveIteratorIterator(
             new RecursiveDirectoryIterator($dir),
             RecursiveIteratorIterator::LEAVES_ONLY)
         as $file) {
    if (!$fileFilter($file)) {
        continue;
    }

    $startTime = microtime(true);
    $origCode = file_get_contents($file);
    $readTime += microtime(true) - $startTime;

    if (null === $origCode = $codeExtractor($file, $origCode)) {
        continue;
    }

    set_time_limit(10);

    ++$count;

    if ($showProgress) {
        echo substr(str_pad('Testing file ' . $count . ': ' . substr($file, strlen($dir)), 79), 0, 79), "\r";
    }

    try {
        $startTime = microtime(true);
        $origStmts = $parser->parse($origCode);
        $parseTime += microtime(true) - $startTime;

        $origTokens = $lexer->getTokens();

        $startTime = microtime(true);
        $stmts = $cloningTraverser->traverse($origStmts);
        $cloneTime += microtime(true) - $startTime;

        $startTime = microtime(true);
        $code = $prettyPrinter->printFormatPreserving($stmts, $origStmts, $origTokens);
        $fpppTime += microtime(true) - $startTime;

        if ($code !== $origCode) {
            echo $file, ":\n Result of format-preserving pretty-print differs\n";
            if ($verbose) {
                echo "FPPP output:\n=====\n$code\n=====\n\n";
            }

            ++$fpppFail;
        }

        $startTime = microtime(true);
        $code = "<?php\n" . $prettyPrinter->prettyPrint($stmts);
        $ppTime += microtime(true) - $startTime;

        try {
            $startTime = microtime(true);
            $ppStmts = $parser->parse($code);
            $reparseTime += microtime(true) - $startTime;

            $startTime = microtime(true);
            $same = $nodeDumper->dump($stmts) == $nodeDumper->dump($ppStmts);
            $compareTime += microtime(true) - $startTime;

            if (!$same) {
                echo $file, ":\n    Result of initial parse and parse after pretty print differ\n";
                if ($verbose) {
                    echo "Pretty printer output:\n=====\n$code\n=====\n\n";
                }

                ++$compareFail;
            }
        } catch (PhpParser\Error $e) {
            echo $file, ":\n    Parse of pretty print failed with message: {$e->getMessage()}\n";
            if ($verbose) {
                echo "Pretty printer output:\n=====\n$code\n=====\n\n";
            }

            ++$ppFail;
        }
    } catch (PhpParser\Error $e) {
        echo $file, ":\n    Parse failed with message: {$e->getMessage()}\n";

        ++$parseFail;
    }
}

if (0 === $parseFail && 0 === $ppFail && 0 === $compareFail) {
    $exit = 0;
    echo "\n\n", 'All tests passed.', "\n";
} else {
    $exit = 1;
    echo "\n\n", '==========', "\n\n", 'There were: ', "\n";
    if (0 !== $parseFail) {
        echo '    ', $parseFail,   ' parse failures.',        "\n";
    }
    if (0 !== $ppFail) {
        echo '    ', $ppFail,      ' pretty print failures.', "\n";
    }
    if (0 !== $fpppFail) {
        echo '    ', $fpppFail,      ' FPPP failures.', "\n";
    }
    if (0 !== $compareFail) {
        echo '    ', $compareFail, ' compare failures.',      "\n";
    }
}

echo "\n",
     'Tested files:         ', $count,        "\n",
     "\n",
     'Reading files took:   ', $readTime,    "\n",
     'Parsing took:         ', $parseTime,   "\n",
     'Cloning took:         ', $cloneTime,   "\n",
     'FPPP took:            ', $fpppTime,    "\n",
     'Pretty printing took: ', $ppTime,      "\n",
     'Reparsing took:       ',