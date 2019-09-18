      // does not contain the final \n character in its text
                        if (isset($lines[$i - 1]) && 0 === \strpos($_token, '/*') && '*/' === \substr(\trim($lines[$i - 1]), -2)) {
                            $this->ignoredLines[$fileName][] = $i;
                        }
                    }

                    break;

                case \PHP_Token_INTERFACE::class:
                case \PHP_Token_TRAIT::class:
                case \PHP_Token_CLASS::class:
                case \PHP_Token_FUNCTION::class:
                    /* @var \PHP_Token_Interface $token */

                    $docblock = $token->getDocblock();

                    $this->ignoredLines[$fileName][] = $token->getLine();

                    if (\strpos($docblock, '@codeCoverageIgnore') || ($this->ignoreDeprecatedCode && \strpos($docblock, '@deprecated'))) {
                        $endLine = $token->getEndLine();

                        for ($i = $token->getLine(); $i <= $endLine; $i++) {
                            $this->ignoredLines[$fileName][] = $i;
                        }
                    }

                    break;

                /* @noinspection PhpMissingBreakStatementInspection */
                case \PHP_Token_NAMESPACE::class:
                    $this->ignoredLines[$fileName][] = $token->getEndLine();

                // Intentional fallthrough
                case \PHP_Token_DECLARE::class:
                case \PHP_Token_OPEN_TAG::class:
                case \PHP_Token_CLOSE_TAG::class:
                case \PHP_Token_USE::class:
                    $this->ignoredLines[$fileName][] = $token->getLine();

                    break;
            }

            if ($ignore) {
                $this->ignoredLines[$fileName][] = $token->getLine();

                if ($stop) {
                    $ignore = false;
                    $stop   = false;
                }
            }
        }

        $this->ignoredLines[$fileName][] = \count($li