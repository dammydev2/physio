s">%s</span>)', $this->formatClass($trace['class']), $trace['type'], $trace['function'], $this->formatArgs($trace['args']));
                    }
                    if (isset($trace['file']) && isset($trace['line'])) {
                        $content .= $this->formatPath($trace['file'], $trace['line']);
                    }
                    $content .= "</td></tr>\n";
                }

                $content .= "</tbody>\n</table>\n</div>\n";
            }
        } catch (\Exception $e) {
            // something nasty happened and we cannot throw an exception anymore
            if ($this->debug) {
                $e = FlattenException::create($e);
                $title = sprintf('Exception thrown when handling an exception (%s: %s)', $e->getClass(), $this->escapeHtml($e->getMessage()));
            } else {
                $title = 'Whoops, looks like something went wrong.';
            }
        }

        $symfonyGhostImageContents = $this->getSymfonyGhostAsSvg();

        return <<<EOF
            <div class="exception-summary">
                <div class="container">
                    <div class="exception-message-wrapper">
                        <h1 class="break-long-words exception-message">$title</h1>
                        <div class="exception-illustration hidden-xs-down">$symfonyGhostImageContents</div>
                    </div>
                </div>
            </div>

            <div class="container">
                $content
            </div>
EOF;
    }

    /**
     * Gets the stylesheet associated with the given exception.
     *
     * @return string The stylesheet as a string
     */
    public function getStylesheet(FlattenException $exception)
    {
        if (!$this->debug) {
            return <<<'EOF'
                body { background-color: #fff; color: #222; font: 16px/1.5 -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif; margin: 0; }
                .container { margin: 30px; max-width: 600px; }
                h1 { color: #dc3545; font-size: 24px; }
EOF;
        }

        return <<<'EOF'
            body { background-color: #F9F9F9; color: #222; font: 14px/1.4 Helvetica, Arial, sans-serif; margin: 0; padding-bottom: 45px; }

            a { cursor: pointer; text-decoration: none; }
            a:hover { text-decoration: underline; }
            abbr[title] { border-bottom: none; cursor: help; text-decoration: none; }

            code, pre { font: 13px/1.5 Consolas, Monaco, Menlo, "Ubuntu Mono", "Liberation Mono", monospace; }

            table, tr, th, td { background: #FFF; border-collapse: collapse; vertical-align: top; }
            table { background: #FFF; border: 1px solid #E0E0E0; box-shadow: 0px 0px 1px rgba(128, 128, 128, .2); margin: 1em 0; width: 100%; }
            table th, table td { border: solid #E0E0E0; border-width: 1px 0; padding: 8px 10px; }
            table th { background-colo