r two parameters. The
first being the email address and the second optional parameter being the name
of the recipient.

``To:`` recipients are visible in the message headers and will be seen by the
other recipients::

    // Using setTo() to set all recipients in one go
    $message->setTo([
      'person1@example.org',
      'person2@otherdomain.org' => 'Person 2 Name',
      'person3@example.org',
      'person4@example.org',
      'person5@example.org' => 'Person 5 Name'
    ]);

.. note::

    Multiple calls to ``setTo()`` will not add new recipients -- each
    call overrides the previous calls. If you want to iteratively add
    recipients, use the ``addTo()`` method::

        // Using addTo() to add recipients iteratively
        $message->addTo('person1@example.org');
        $message->addTo('person2@example.org', 'Person 2 Name');

Setting ``Cc:`` Recipients
~~~~~~~~~~~~~~~~~~~~~~~~~~

``Cc:`` recipients are set with the ``setCc()`` or ``addCc()`` methods of the
message.

To set ``Cc:`` recipients, create the message object using either ``new
Swift_Message( ... )`` or ``new Swift_Message( ... )``, then call the
``setCc()`` method with a complete array of addresses, or use the ``addCc()``
method to iteratively add recipients.

The ``setCc()`` method accepts input in various formats as described earlier in
this chapter. The ``addCc()`` method takes either one or two parameters. The
first being the email address and the second optional parameter being the name
of the recipient.

``Cc:`` recipients are visible in the message headers and will be seen by the
other recipients::

    // Using setTo() to set all recipients in one go
    $message->setTo([
      'person1@example.org',
      'person2@otherdomain.org' => 'Person 2 Name',
      'person3@example.org',
      'person4@example.org',
      'person5@example.org' => 'Person 5 Name'
    ]);

.. note::

    Multiple calls to ``setCc()`` will not add new recipients -- each call
    overrides the previous calls. If you want to iteratively add Cc:
    recipients, use the ``addCc()`` method::

        // Using addCc() to add recipients iteratively
        $message->addCc('person1@example.org');
        $message->addCc('person2@example.org', 'Person 2 Name');

Setting ``Bcc:`` Recipients
~~~~~~~~~~~~~~~~~~~~~~~~~~~

``Bcc:`` recipients receive a copy of the message without anybody else knowing
it, and are set with the ``setBcc()`` or ``addBcc()`` methods of the message.

To set ``Bcc:`` recipients, create the message object using either ``new
Swift_Message( ... )`` or ``new Swift_Message( ... )``, then call the
``setBcc()`` method with a complete array of addresses, or use the ``addBcc()``
method to iteratively add recipients.

The ``setBcc()`` method accepts input in various formats as described earlier
in this chapter. The ``addBcc()`` method takes either one or two parameters.
The first being the email address and the second optional parameter being the
name of the recipient.

Only the individual ``Bcc:`` recipient will see their address in the message
headers. Other recipients (including other ``Bcc:`` recipients) will not see
the address::

    // Using setBcc() to set all recipients in one go
    $message->setBcc([
      'person1@example.org',
      'person2@otherdomain.org' => 'Person 2 Name',
      'person3@example.org',
      'person4@example.org',
      'person5@example.org' => 'Person 5 Name'
    ]);

.. note::

    Multiple calls to ``setBcc()`` will not add new recipients -- each call
    overrides the previous calls. If you want to iteratively add Bcc:
    recipients, use the ``addBcc()`` method::

        // Using addBcc() to add recipients iteratively
        $message->addBcc('person1@example.org');
        $message->addBcc('person2@example.org', 'Person 2 Name');

.. sidebar:: Internationalized Email Addresses

    Traditionally only ASCII characters have been allowed in email addresses.
    With the introduction of internationalized domain names (IDNs), non-ASCII
    characters may appear in the domain name. By default, Swiftmailer encodes
    such domain names in Punycode (e.g. xn--xample-ova.invalid). This is
    compatible with all mail servers.

    RFC 6531 introduced an SMTP extension, SMTPUTF8, that allows non-ASCII
    characters in email addresses on both sides of the @ sign. To send to such
    addresses, your outbound SMTP server must support the SMTPUTF8 extension.
    You 