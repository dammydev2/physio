 '</body>')
        ) {
            if ($response->headers->has('Content-Type') && false !== strpos($response->headers->get('Content-Type'), 'html')) {
                $dumper = new HtmlDumper('php://output', $this->charset);
                $dumper->setDisplayOptions(['fileLinkFormat' => $this->fileLinkFormat]);
            } else {
                $dumper = new CliDumper('php://output', $this->charset);
            }

            foreach ($this->data as $dump) {
                $this->doDump($dumper, $dump['data'], $dump['name'], $dump['file'], $dump['line']);
            }
        }
    }

    public function reset()
    {
        if ($this->stopwatch) {
            $this->stopwatch->reset();
        }
        $this->data = [];
        $this->dataCount = 0;
        $this->isCollected = true;
        $this->clonesCount = 0;
        $this->clonesIndex = 0;
    }

    public function serialize()
    {
        if ($this->clonesCount !== $this->clonesIndex) {
            return 'a:0:{}';
        }

        $this->data[] = $this->fileLinkFormat;
        $this->data[] = $this->charset;
        $ser = serialize($this->data);
        $this->data = [];
        $this->dataCount = 0;
        $this->isCollected = true;

        return $ser;
    }

    public function unserialize($data)
    {
        $this->data = unserialize($data);
        $charset = array_pop($this->data);
        $fileLinkFormat = array_pop($this->data);
        $this->dataCount = \count($this->data);
        self::__construct($this->stopwatch, $fileLinkFormat, $charset);
    }

    public function getDumpsCount()
    {
        return $this->dataCount;
    }

    public function getDumps($format, $maxDepthLimit = -1, $maxItemsPerDepth = -1)
    {
        $data = fopen('php://memory', 'r+b');

        if ('html' === $format) {
            $dumper = new HtmlDumper($data, $this->charset);
            $dumper->setDisplayOptions(['fileLinkFormat' => $this->fileLinkFormat]);
        } else {
            throw new \InvalidArgumentException(sprintf('Invalid dump format: %s', $format));
        }
        $dumps = [];

        foreach ($this->data as $dump) {
            $dumper->dump($dump['data']->withMaxDepth($maxDepthLimit)->withMaxItemsPerDepth($maxItemsPerDepth));
            $dump['data'] = stream_get_contents($data, -1, 0);
            ftruncate($data, 0);
            rewind($data);
            $dumps[] = $dump;
        }

        return $dumps;
    }

    public function getName()
    {
        return 'dump';
    }

    public function __destruct()
    {
        if (0 === $this->clonesCount-- && !$this->isCollected && $this->data) {
            $this->clonesCount = 0;
            $this->isCollected = true;

            $h = headers_list();
            $i = \count($h);
            array_unshift($h, 'Content-Type: '.ini_get('default_mimetype'));
            while (0 !== stripos($h[$i], 'Content-Type:')) {
                --$i;
            }

            if (isset($_SERVER['VAR_DUMPER_FORMAT'])) {
                $html = 'html' === $_SERVER['VAR_DUMPER_FORMAT'];
            } else {
                $html = !\in_array(\PHP_SAPI, ['cli', 'phpdbg'], true) && stripos($h[$i], 'html');
            }

            if ($html) {
                $dumper = new HtmlDumper('php://output', $this->charset);
                $dumper->setDisplayOptions(['fileLinkFormat' => $this->fileLinkFormat]);
            } else {
                $dumper = new CliDumper('php://output', $this->charset);
            }

            foreach ($this->data as $i => $dump) {
                $this->data[$i] = null;
                $this->doDump($dumper, $dump['data'