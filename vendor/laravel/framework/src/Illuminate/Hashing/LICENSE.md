) {
            $message->subject($this->subject);
        } else {
            $message->subject(Str::title(Str::snake(class_basename($this), ' ')));
        }

        return $this;
    }

    /**
     * Add all of the attachments to the message.
     *
     * @param  \Illuminate\Mail\Message  $message
     * @return $this
     */
    protected function buildAttachments($message)
    {
        foreach ($this->attachments as $attachment) {
            $message->attach($attachment['file'], $attachment['options']);
        }

        foreach ($this->rawAttachments as $attachment) {
            $message->attachData(
                $attachment['data'], $attachment['name'], $attachment['options']
            );
        }

        $this->buildDiskAttachments($message);

        return $this;
    }

    /**
     * Add all of the disk attachments to the message.
     *
     * @param  \Illuminate\Mail\Message  $message
     * @return void
     */
    protected function buildDiskAttachments($message)
    {
        foreach ($this->diskAttachments as $attachment) {
   