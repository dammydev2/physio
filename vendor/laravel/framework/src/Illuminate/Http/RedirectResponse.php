 an array but doesn't contain numeric keys, we will assume
        // the views are being explicitly specified and will extract them via the
        // named keys instead, allowing the developers to use one or the other.
        if (is_array($view)) {
            return [
                $view['html'] ?? null,
                $view['text'] ?? null,
                $view['raw'] ?? null,
            ];
        }

        throw new InvalidArgumentException('Invalid view.');
    }

    /**
     * Add the content to a given message.
     *
     * @param  \Illuminate\Mail\Message  $message
     * @param  string  $view
     * @param  string  $plain
     * @param  string  $raw
     * @param  array  $data
     * @return void
     */
    protected function addContent($message, $view, $plain, $raw, $data)
    {
        if (isset($view)) {
            $message->setBody($this->renderView($view, $data), 'text/html');
        }

        if (isset($plain)) {
            $method = isset($view) ? 'addPart' : 'setBody';

            $message->$method($this->renderView($plain, $data), 'text/plain');
        }

        if (isset($raw)) {
            $method = (isset($view) || isset($plain)) ? 'addPart' : 'setBody';

            $message->$method($raw, 'text/plain');
        }
    }

    /**
     * Render the given view.
     *
     * @param  string  $view
     * @param  array  $data
     * @return string
     */
    protected function renderView($view, $data)
    {
        return $view instanceof Htmlable
                        ? $view->toHtml()
                        : $this->views->make($view, $data)->render();
    }

    /**
     * Set the global "to" address on the given message.
     *
     * @param  \Illuminate\Mail\Message  $message
     * @return void
     */
    protected function setGlobalToAndRemoveCcAndBcc($message)
    {
        $message->to($this->to['address'], $this->to['name'], true);
        $message->cc(null, null, true);
        $message->bcc(null, null, true);
    }

    /**
     * Queue a new e-mail message for sending.
     *
     * @param  \Illuminate\Contracts\Mail\Mailable  $view
     * @param  string|null  $queue
     * @return mixed
     *
     * @throws \InvalidArgumentException
     */
    public function queue($view, $queue = null)
    {
        if (! $view instanceof MailableContract) {
            throw new InvalidArgumentException('Only mailables may be queued.');
        }

        if (is_string($queue)) {
            $view->onQueue($queue);
        }

        return $view->queue($this->queue);
    }

    /**
     * Queue a new e-mail message for sending on the given queue.
     *
     * @param  string  $queue
     * @param  \Illuminate\Contracts\Mail\Mailable  $view
     * @return mixed
     */
    public function onQueue($queue, $view)
    {
        return $this->queue($view, $queue);
    }

    /**
     * Queue a new e-mail message for sending on the given queue.
     *
     * This method didn't match rest of framework's "onQueue" phrasing. Added "onQueue".
     *
     * @param  string  $queue
     * @param  \Illuminate\Contracts\Mail\Mailable  $view
     * @return mixed
     */
    public function queueOn($queue, $view)
    {
        return $this->onQueue($queue, $view);
    }

    /**
     * Queue a new e-mail message for sending after (n) seconds.
     *
     * @param  \DateTimeInterface|\DateInterval|int  $delay
     * @param  \Illuminate\Contracts\Mail\Mailable  $view
     * @param  string|null  $queue
     * @return mixed
     *
     * @throws \InvalidArgumentException
     */
    public function later($delay, $view, $queue = null)
    {
        if (! $view instanceof MailableContract) {
            throw new InvalidArgumentException('Only mailables may be queued.');
        }

        return $view->later($delay, is_null($queue) ? $this->queue : $queue);
    }

    /**
     * Queue a new e-mail message for sending after (n) seconds on the given queue.
     *
     * @param  string  $queue
     * @param  \DateTimeInterface|\DateInterval|int  $delay
     * @param  \Illuminate\Contracts\Mail\Mailable  $view
     * @return mixed
     */
    public function laterOn($queue, $delay, $view)
    {
        return $this->later($delay, $view, $queue);
    }

    /**
     * Create a new message instance.
     *
     * @return \Illuminate\Mail\Message
     */
    protected function createMessage()
    {
        $message = new Message($this->swift->createMessage('message'));

        // If a global from address has been specified we will set it on every message
        // instance so the developer does not have to repeat themselves every time
        // they create a new message. We'll just go ahead and push this address.
        if (! empty($this->from['address'])) {
            $message->from($this->from['address'], $this->from['name']);
        }

        // When a global reply address was specified we will set this on every message
        // instance so the developer does not have to repeat themselves every time
        // they create a new message. We will just go ahead and push this address.
        if (! empty($this->replyTo['address'])) {
            $message->replyTo($this->replyTo['address'], $this->replyTo['name']);
        }

        return $message;
    }

    /**
     * Send a Swift Message instance.
     *
     * @param  \Swift_Message  $message
     * @return int|null
     */
    protected