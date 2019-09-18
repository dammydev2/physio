<?php

namespace Illuminate\Mail;

use ReflectionClass;
use ReflectionProperty;
use Illuminate\Support\Str;
use Illuminate\Support\Collection;
use Illuminate\Support\HtmlString;
use Illuminate\Container\Container;
use Illuminate\Support\Traits\Localizable;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Traits\ForwardsCalls;
use Illuminate\Contracts\Queue\Factory as Queue;
use Illuminate\Contracts\Mail\Mailer as MailerContract;
use Illuminate\Contracts\Mail\Mailable as MailableContract;
use Illuminate\Contracts\Filesystem\Factory as FilesystemFactory;

class Mailable implements MailableContract, Renderable
{
    use ForwardsCalls, Localizable;

    /**
     * The locale of the message.
     *
     * @var string
     */
    public $locale;

    /**
     * The person the message is from.
     *
     * @var array
     */
    public $from = [];

    /**
     * The "to" recipients of the message.
     *
     * @var array
     */
    public $to = [];

    /**
     * The "cc" recipients of the message.
     *
     * @var array
     */
    public $cc = [];

    /**
     * The "bcc" recipients of the message.
     *
     * @var array
     */
    public $bcc = [];

    /**
     * The "reply to" recipients of the message.
     *
     * @var array
     */
    public $replyTo = [];

    /**
     * The subject of the message.
     *
     * @var string
     */
    public $subject;

    /**
     * The Markdown template for the message (if applicable).
     *
     * @var string
     */
    protected $markdown;

    /**
     * The HTML to use for the message.
     *
     * @var string
     */
    protected $html;

    /**
     * The view to use for the message.
     *
     * @var string
     */
    public $view;

    /**
     * The plain text view to use for the message.
     *
     * @var string
     */
    public $textView;

    /**
     * The view data for the message.
     *
     * @var array
     */
    public $viewData = [];

    /**
     * The attachments for the message.
     *
     * @var array
     */
    public $attachments = [];

    /**
     * The raw attachments for the message.
     *
     * @var array
     */
    public $rawAttachments = [];

    /**
     * The attachments from a storage disk.
     *
     * @var array
     */
    public $diskAttachments = [