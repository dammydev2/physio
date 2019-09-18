-F]++/', function ($m) {
                return \class_exists($m[0], false) ? get_parent_class($m[0]).'@anonymous' : $m[0];
            }, $message);
        }

        $this->message = $message;

        return $this;
    }

    public function getCode()
    {
        return $this->code;
    }

    /**
     * @return $this
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    public function getPrevious()
    {
        return $this->previous;
    }

    /**
     * @return $this
     */
    public function setPrevious(self $previous)
    {
        $this->previous = $previous;

        return $this;
    }

    public function getAllPrevious()
    {
        $exceptions = [];
        $e = $this;
        while ($e = $e->getPrevious()) {
            $exceptions[] = $e;
        }

        return $exceptions;
    }

    public function getTrace()
    {
        return $this->trace;
    }

    /**
     * @deprecated since 4.1, use {@see setTraceFromThrowable()} instead.
     */
    public function setTraceFromException(\Exception $exception)
    {
        @trigger_error(sprintf('The "%s()" method is deprecated since Symfony 4.1, use "setTraceFromThrowable()" in