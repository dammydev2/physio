 public function markdown($view, array $data = [])
    {
        $this->markdown = $view;
        $this->viewData = array_merge($this->viewData, $data);

        return $this;
    }

    /**
     * Set the view and view data for the message.
     *
     * @param  string  $view
     * @param  array  $data
     * @return $this
     */
    public function view($view, array $data = [])
    {
        $this->view = $view;
        $this->viewData = array_merge($this->viewData, $data);

        return $this;
    }

    /**
     * Set the rendered HTML content for the message.
     *
     * @param  string  $html
     * @return $this
     */
    public function html($html)
    {
        $this->html = $html;

        return $this;
    }

    /**
     * Set the plain text view for the message.
     *
     * @param  string  $textView
     * @param  array  $data
     * @return $this
     */
    public function text($textView, array $data = [])
    {
        $this->textView = $textView;
        $