-----+
| ``Return-Path``               | Specifies where bounces should go (Swift Mailer reads this for other uses)                                                         | ``getReturnPath()`` / ``setReturnPath()``   |
+-------------------------------+------------------------------------------------------------------------------------------------------------------------------------+---------------------------------------------+
| ``From``                      | Specifies the address of the person who the message is from. This can be multiple addresses if multiple people wrote the message.  | ``getFrom()`` / ``setFrom()``               |
+-------------------------------+------------------------------------------------------------------------------------------------------------------------------------+---------------------------------------------+
| ``Sender``                    | Specifies the address of the person who physically sent the message (higher precedence than ``From:``)                             | ``getSender()`` / ``setSender()``           |
+-------------------------------+------------------------------------------------------------------------------------------------------------------------------------+---------------------------------------------+
| ``To``                        | Specifies the addresses of the intended recipients                                                                                 | ``getTo()`` / ``setTo()``                   |
+-------------------------------+------------------------------------------------------------------------------------------------------------------------------------+---------------------------------------------+
| ``Cc``                        | Specifies the addresses of recipients who will be copied in on the message                                                         | ``getCc()`` / ``setCc()``                   |
+-------------------------------+------------------------------------------------------------------------------------------------------------------------------------+---------------------------------------------+
| ``Bcc``                       | Specifies the addresses of recipients who the message will be blind-copied to. Other recipients will not be aware of these copies. | ``getBcc()`` / ``setBcc()``                 |
+-------------------------------+------------------------------------------------------------------------------------------------------------------------------------+---------------------------------------------+
| ``Reply-To``                  | Specifies the address where replies are sent to                                                                                    | ``getReplyTo()`` / ``setReplyTo()``         |
+-------------------------------+------------------------------------------------------------------------------------------------------------------------------------+---------------------------------------------+
| ``Subject``                   | Specifies the subject line that is displayed in the recipients' mail client                                                        | ``getSubject()`` / ``setSubject()``         |
+-------------------------------+------------------------------------------------------------------------------------------------------------------------------------+---------------------------------------------+
| ``Date``                      | Specifies the date at which the message was sent                                                                                   | ``getDate()`` / ``setDate()``               |
+-------------------------------+------------------------------------------------------------------------------------------------------------------------------------+---------------------------------------------+
| ``Content-Type``              | Specifies the format of the message (usually text/plain or text/html)                                                              | ``getContentType()`` / ``setContentType()`` |
+-------------------------------+------------------------------------------------------------------------------------------------------------------------------------+---------------------------------------------+
| ``Content-Transfer-Encoding`` | Specifies the encoding scheme in the message                                                                                       | ``getEncoder()`` / ``setEncoder()``         |
+-------------------------------+------------------------------------------------------------------------------------------------------------------------------------+---------------------------------------------+

Working with a Message Object
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

Although there are a lot of available methods on a message object, you only
need to make use of a small subset of them. Usually you'll use
``setSubject()``, ``setTo()`` and ``setFrom()`` before setting the body of your
message with ``setBody()``::

    $message = new Swift_Message();
    $message->setSubject('My subject');

All MIME entities (including a message) have a ``toString()`` method that you
can call if you want to take a look at what is going to be sent. For example,
if you ``echo $message->toString();`` you would see something like this:

.. code-block:: text

    Message-ID: <1230173678.4952f5eeb1432@swift.generated>
    Date: Thu, 25 Dec 2008 13:54:38 +1100
    Subject: Example subject
    From: Chris Corbyn <chris@w3style.co.uk>
    To: Receiver Name <recipient@example.org>
    MIME-Version: 1.0
    Content-Type: text/plain; charset=utf-8
    Content-Transfer-Encoding: quoted-printable

    Here is the message

We'll take a closer look at the methods you use to create your message in the
following sections.

Adding Content to Your Message
------------------------------

Rich content can be added to messages in Swift Mailer with relative ease by
calling methods such as ``setSubject()``, ``setBody()``, ``addPart()`` and
``attach()``.

Setting the Subject Line
~~~~~~~~~~~~~~~~~~~~~~~~

The subject line, displayed in the recipients' mail client can be set with the
``setSubject()`` method, or as a parameter to ``new Swift_Message()``::

    // Pass it as a parameter when you create the message
    $message = new Swift_Message('My amazing subject');

    // Or set it after like this
    $message->setSubject('My amazing subject');

Setting the Body Content
~~~~~~~~~~~~~~~~~~~~~~~~

The body of the message -- seen when the user opens the message -- is specified
by calling the ``setBody()`` method. If an alternative body is to be included,
``addPart()`` can be used.

The body of a message is the main part that is read by the user. Often people
want to send a message in HTML format (``text/html``), other times people want
to send in plain text (``text/plain``), or sometimes people want to send both
versions and allow the recipient to choose how they view the message.

As a rule of thumb, if you're going to send a HTML email, always include a
plain-text equivalent of the same content so that users who prefer to read
plain text can do so.

If the recipient's mail client offers preferences for displaying text vs. HTML
then the mail client will present that part to the user where available. In
other cases the mail client will display the "best" part it can - usually HTML
if you've included HTML::

    // Pass it as a parameter when you create the message
    $message = new Swift_Message('Subject here', 'My amazing body');

    // Or set it after like this
    $message->setBody('My <em>amazing</em> body', 'text/html');

    // Add alternative parts with addPart()
    $message->addPart('My amazing body in plain text', 'text/plain');

Attaching Files
---------------

Attachments are downloadable parts of a message and can be added by calling the
``attach()`` method on the message. You can add attachments that exist on disk,
or you can create attachments on-the-fly.

Although we refer to files sent over e-mails as "attachments" -- because
they're attached to the message -- lots of other parts of the message are
actually "attached" even if we don't refer to these parts as attachments.

File attachments are created by the ``Swift_Attachment`` class and then
attached to the message via the ``attach()`` method on it. For all of the
"every day" MIME types such as all image formats, word documents, PDFs and
spreadsheets you don't need to explicitly set the content-type of the
attachment, though it would do no harm to do so. For less common formats you
should set the content-type -- which we'll cover in a moment.

Attaching Existing Files
~~~~~~~~~~~~~~~~~~~~~~~~

Files that already exist, either on disk or at a URL can be attached to a
message with just one line of code, using ``Swift_Attachment::fromPath()``.

You can attach files that exist locally, or if your PHP installation has
``allow_url_fopen`` turned on you can attach files from other
websites.

The attachment will be presented to the recipient as a downloadable file with
the same filename as the one you attached::

    // Create the attachment
    // * Note that you can technically leave the content-type parameter out
    $attachment = Swift_Attachment::fromPath('/path/to/image.jpg', 'image/jpeg');

    // Attach it to the message
    $message->attach($attachment);

    // The two statements above could be written in one line instead
    $message->attach(Swift_Attachment::fromPath('/path/to/image.jpg'));

    // You can attach files from a URL if allow_url_fopen is on in php.ini
    $message->attach(Swift_Attachment::fromPath('http://site.tld/logo.png'));

Setting the Filename
~~~~~~~~~~~~~~~~~~~~

Usually you don't need to explicitly set the filename of an attachment because
the name of the attached file will be used by default, but if you want to set
the filename you use the ``setFilename()`` method of the Attachment.

The attachment will be attached in the normal way, but meta-data sent inside
the email will rename the file to something else::

    // Create the attachment and call its setFilename() method
    $attachment = Swift_Attachment::fromPath('/path/to/image.jpg')
      ->setFilename('cool.jpg');

    // Because there's a fluid interface, you can do this in one statement
    $message->attach(
      Swift_Attachment::fromPath('/path/to/image.jpg')->setFilename('cool.jpg')
    );

Attaching Dynamic Content
~~~~~~~~~~~~~~~~~~~~~~~~~

Files that are generated at runtime, such as PDF documents or images created
via GD can be attached directly to a message without writing them out to disk.
Use ``Swift_Attachment`` directly.

The