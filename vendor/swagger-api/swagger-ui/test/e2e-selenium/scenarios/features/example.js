:

    Both methods will accept the above input format in practice.

If all you want to do is set a single address in the header, you can use a
string as the input parameter to ``setAddresses()`` and/or
``setNameAddresses()``::

    $to = $message->getHeaders()->get('To');
    $to->setAddresses('joe-bloggs@example.org');

When output via ``toString()``, a mailbox header produces something like the
following::

    $to = $message->getHeaders()->get('To');
    $to->setNameAddresses([
      'person1@example.org' => 'Name of Person',
      'person2@example.org',
      'person3@example.org' => 'Another Person'
    ]);

    echo $to->toString();

    /*

    To: Name of Person <person1@example.org>, person2@example.org, Another Person
     <person3@example.org>

    */

Internationalized domains are automatically converted to IDN encoding::

    $to = $message->getHeaders()->get('To');
    $to->setAddresses('joe@ëxämple.org');

    echo $to->toString();

    /*

    To: joe@xn--xmple-gra1c.org

    */

ID Headers
~~~~~~~~~~

ID headers contain identifiers for the entity (or the message). The most
notable ID header is the Message-ID header on the message itself.

An ID that exists inside an ID header looks more-or-less less like an email
address. For example, ``<1234955437.499becad62ec2@example.org>``. The part to
the left of the @ sign is usually unique, based on the current time and some
random factor. The part on the right is usually a domain name.

Any ID passed to the header's ``setId()`` method absolutely MUST conform to
this structure, otherwise you'll get an Exception thrown at you by Swift Mailer
(a ``Swift_RfcComplianceException``). This is to ensure that the generated
email complies with relevant RFC documents and therefore is less likely to be
blocked as spam.

It's easy to add a new ID header to a HeaderSet. You do this by calling the
HeaderSet's ``addIdHeader()`` method::

    $message = new Swift_Message();
    $headers = $message->getHeaders();
    $headers->addIdHeader('Your-Header-Name', '123456.unqiue@example.org');

Changing the value of an existing ID header is done by calling its ``setId()``
method::

    $msgId = $message->getHeaders()->get('Message-ID');
    $msgId->setId(time() . '.' . uniqid('thing') . '@example.org');

When output via ``toString()``, an ID header produces something like the
following::

    $msgId = $message->getHeaders()->get('Message-ID');
    echo $msgId->toString();

    /*

    Message-ID: <1234955437.499becad62ec2@example.org>

    */

Path Headers
~~~~~~~~~~~~

Path headers are like very-restricted mailbox headers. They contain a single
email address with no associated name. The Return-Path header of a message is a
path header.

You add a new path header to a HeaderSet by calling the HeaderSet's
``addPathHeader()`` method::

    $message = new Swift_Message();
    $headers = $message->getHeaders();
    $headers->addPathHeader('Your-Header-Name', 'person@example.org');

Changing the value of an existing path header is done by calling its
``setAddress()`` method::

    $return = $message->getHeaders()->get('Return-Path');
    $return->setAddress('my-address@example.org');

When output via ``toString()``, a path header produces something like the
following::

    $return = $message->getHeaders()->get('Return-Path');
    $return->setAddress('person@example.org');
    echo $return->toString();

    /*

    Return-Path: <person@example.org>

    */

Header Operations
-----------------

Working with the headers in a message involves knowing how to use the methods
on the HeaderSet and on the individual Headers within the HeaderSet.

Adding new Headers
~~~~~~~~~~~~~~~~~~

New headers can be added to the HeaderSet by using one of the provided
``add..Header()`` methods.

The added header will appear in the message when it is sent::

    // Adding a custom header to a message
    $message = new Swift_Message();
    $headers = $message->getHeaders();
    $headers->addTextHeader('X-Mine', 'something here');

    // Adding a custom header to an attachment
    $attachment = Swift_Attachment::fromPath('/path/to/doc.pdf');
    $attachment->getHeaders()->addDateHeader('X-Created-Time', time());

Retrieving Headers
~~~~~~~~~~~~~~~~~~

Headers are retrieved through the HeaderSet's ``get()`` and ``getAll()``
methods::

    $headers = $message->getHeaders();

    // Get the To: header
    $toHeader = $headers->get('To');

    // Get all headers named "X-Foo"
    $fooHeaders = $headers->getAll('X-Foo');

    // Get the second header named "X-Foo"
    $foo = $headers->get('X-Foo', 1);

    // Get all headers that are present
    $all = $headers->getAll();

When using ``get()`` a single header is returned that matches the name (case
insensitive) that is passed to it. When using ``getAll()`` with a header name,
an array of headers with that name are returned. Calling ``getAll()`` with no
arguments returns an array of all headers present in the entity.

.. note::

    It's valid for some headers to appear more than once in a message (e.g.
    the Received header). For this reason ``getAll()`` exists to fetch all
    headers with a specified name. In addition, ``get()`` accepts an optional
    numerical index, starting from zero to specify which header you want more
    specifically.

.. note::

    If you want to modify the contents of the header and you don't know for
    sure what type of header it is then you may need to check the type by
    calling its ``getFieldType()`` method.

Check if a Header Exists
~~~~~~~~~~~~~~~~~~~~~~~~

You can check if a named header is present in a HeaderSet by calling its
``has()`` method::

    $headers = $message->getHeaders();

    // Check if the To: header exists
    if ($headers->has('To')) {
      echo 'To: exists';
    }

    // Check if an X-Foo header exists twice (i.e. check for the 2nd one)
    if ($headers->has('X-Foo', 1)) {
      echo 'Second X-Foo header exists';
    }

If the header exists, ``true`` will be returned or ``false`` if not.

.. note::

    It's valid for some headers to appear more than once in a message (e.g.
    the Received header). For this reason ``has()`` accepts an optional
    numerical index, starting from zero to specify which header you want to
    check more specifically.

Removing Headers
~~~~~~~~~~~~~~~~

Removing a Header from the HeaderSet is done by calling the HeaderSet's
``remove()`` or ``removeAll()`` methods::

    $headers = $message->getHeaders();

    // Remove the Subject: header
    $headers->remove('Subject');

    // Remove all X-Foo headers
    $headers->removeAll('X-Foo');

    // Remove only the second X-Foo header
    $headers->remove('X-Foo', 1);

When calling ``remove()`` a single header will be removed. When calling
``removeAll()`` all headers with the given name will be removed. If no headers
exist with the given name, no errors will occur.

.. note::

    It's valid for some headers to appear more than once in a message (e.g.
    the Received header). For this reason ``remove()`` accepts an o