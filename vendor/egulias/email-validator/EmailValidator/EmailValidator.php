heck conditions for outputting HTML:
            // @todo: Make this more robust
            if (PHP_SAPI === 'cli') {
                // Help users who have been relying on an internal test value
                // fix their code to the proper method
                if (isset($_ENV['whoops-test'])) {
                    throw new \Exception(
                        'Use handleUnconditionally instead of whoops-test'
                        .' environment variable'
                    );
                }

                return Handler::DONE;
            }
        }

        $templateFile = $this->getResource("views/layout.html.php");
        $cssFile      = $this->getResource("css/whoops.base.css");
        $zeptoFile    = $this->getResource("js/zepto.min.js");
        $prettifyFile = $this->getResource("js/prettify.min.js");
        $clipboard    = $this->getResource("js/clipboard.min.js");
        $jsFile       = $this->getResource("js/whoops.base.js");

        if ($this->customCss) {
            $customCssFile = $this->getResource($this->customCss);
        }

        $inspector = $this->getInspector();
        $frames = $this->getExceptionFrames();
        $code = $this->getExceptionCode(