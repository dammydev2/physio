<?php

namespace Illuminate\Notifications\Channels;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Mail\Markdown;
use Illuminate\Contracts\Mail\Mailer;
use Illuminate\Contracts\Mail\Mailable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;

class MailChannel
{
    /**
     * The mailer implementation.
     *
     * @var \Illuminate\Contracts\Mail\Mailer
     */
    protected $mailer;

    /**
     * The markdown implementation.
     *
     * @var \Illuminate\Mail\Markdown
     */
    protected $markdown;

    /**
     * Create a new mail channel instance.
     *
     * @param  \Illuminate\Contracts\Mail\Mailer  $mailer
     * @param  \Illuminate\Mail\Markdown  $markdown
     * @return void
     */
    public function __construct(Mailer $mailer, Markdown $markdown)
    {
        $this->mailer = $mailer;
        $this->markdown = $markdown;
    }

    /**
     * Send the given notification.
     *
     * @param  mixed  $notifiable
     * @param  \Illuminate\Notifications\Notification  $notification