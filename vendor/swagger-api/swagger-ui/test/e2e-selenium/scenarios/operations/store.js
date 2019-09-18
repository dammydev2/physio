file, the mail client should just present the attachment as
    normal.

Embedding Inline Media Files
~~~~~~~~~~~~~~~~~~~~~~~~~~~~

Often, people want to include an image or other content inline with a HTML
message. It's easy to do this with HTML linking to remote resources, but this
approach is usually blocked by mail clients. Swift Mailer allows you to embed
your media directly into the message.

Mail clients usually block downloads from remote resources because this
technique was often abused as a mean of tracking who opened an email. If
you're sending a HTML email and you want to include an image in the message
another approach you can take is to embed the image directly.

Swift Mailer makes embedding files into messages extremely streamlined. You
embed a file by calling the ``embed()`` method of the message,
which returns a value you can use in a ``src`` or
``href`` attribute in your HTML.

Just like with attachments, it's possible to embed dynamically generated
content without having an existing file available.

The embedded files are sent in the email as a special type of attachment that
has a unique ID used to reference them within your HTML attributes. On mail
clients that do not support embedded files they may appear as attachments.

Although this is commonly done for images, in theory it will work for any
displayable (or playable) media type. Support for other media types (such as
video) is dependent on the mail client however.

Embedding Existing Files
........................

Files that already exist, either on disk or at a URL can be embedded in a
message with just one line of code, using ``Swift_EmbeddedFile::fromPath()``.

You can embed files that exist locally, or if your PHP installation has
``allow_url_fopen`` turned on you can embed files from other websites.

The file will be displayed with the message inline with the HTML wherever its ID
is used as a ``src`` attribute::

    // Create the message
    $message = new Swift_Message('My subject');

    // Set the body
    $message->setBody(
    '<html>' .
    ' <body>' .
    '  Here is an image <img src="' . // Embed the file
         $message->embed(Swift_Image::fromPath('image.png')) .
       '" alt="Image" />' .
    '  Rest of message' .
    ' </body>' .
    '</html>',
      'text/html' // Mark the content-type as HTML
    );

    // You can embed files from a URL if allow_url_fopen is on in php.ini
    $message->setBody(
    '<html>' .
    ' <body>' .
    '  Here is an image <img src="' .
         $message->embed(Swift_Image::fromPath('http://site.tld/logo.png')) .
       '" alt="Image" />' .
    '  Rest of message' .
    ' </body>' .
    '</html>',
      'text/html'
    );

.. note::

    ``Swift_Image`` and ``Swift_EmbeddedFile`` are just aliases of one another.
    ``Swift_Image`` exists for semantic purposes.

.. note::

    You can embed files in two stages if you prefer. Just capture the return
    value of ``embed()`` in a variable and use that as the ``src`` attribute::

        // If placing the embed() code inline becomes cumbersome
        // it's easy to do this in two steps
        $cid = $message->embed(Swift_Image::fromPath('image.png'));

        $message->setBody(
        '<html>' .
        ' <body>' .
        '  Here is an image <img src="' . $cid . '" alt="Image" />' .
        '  Rest of message' .
        ' </body>' .
        '</html>',
          'text/html' // Mark the content-type as HTML
        );

Embedding Dynamic Content
.........................

Images that are generated at runtime, such as images created via GD can be
embedded directly to a message without writing them out to disk. Use the
standard ``new Swift_Image()`` method.

The file will be displayed with the message inline with the HTML wherever its ID
is used as a ``src`` attribute::

    // Create your file contents in the normal way, but don't write them to disk
    $img_data = create_my_image_data();

    // Create the message
    $message = new Swift_Message('My subject');

    // Set the body
    $message->setBody(
    '<html>' .
    ' <body>' .
    '  Here is an image <img src="' . // Embed the file
         $message->embed(new Swift_Image($img_data, 'image.jpg', 'image/jpeg')) .
       '" alt="Image" />' .
    '  Rest of message' .
    ' </body>' .
    '</html>',
      'text/html' // Mark the content-type as HTML
    );

.. note::

    ``Swift_Image`` and ``Swift_EmbeddedFile`` are just aliases of one another.
    ``Swift_Image`` exists for semantic purposes.

.. note::

    You can embed files in two stages if you prefer. Just capture the return
    value of ``embed()`` in a variable and use that as the ``src`` attribute::

        // If placing the embed() code inline becomes cumbersome
        // it's easy to do this in two steps
        $cid = $message->embed(new Swift_Image($img_data, 'image.jpg', 'image/jpeg'));

        $message->setBody(
        '<html>' .
        ' <body>' .
        '  Here is an image <img src="' . $cid . '" alt="Image" />' .
        '  Rest of message' .
        ' </body>' .
        '</html>',
          'text/html' // Mark the content-type as HTML
        );

Adding Recipients to Your Message
---------------------------------

Recipients are specified within the message itself via ``setTo()``, ``setCc()``
and ``setBcc()``. Swift Mailer reads these recipients from the message when it
gets sent s