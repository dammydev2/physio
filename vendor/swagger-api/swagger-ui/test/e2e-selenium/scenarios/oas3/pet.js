Creating Messages
=================

Creating messages in Swift Mailer is done by making use of the various MIME
entities provided with the library. Complex messages can be quickly created
with very little effort.

Quick Reference
---------------

You can think of creating a Message as being similar to the steps you perform
when you click the Compose button in your mail client. You give it a subject,
specify some recipients, add any attachments and write your message::

    // Create the message
    $message = (new Swift_Message())

      // Give the message a subject
      ->setSubject('Your subject')

      // Set the From address with an associative array
      ->setFrom(['john@doe.com' => 'John Doe'])

      // Set the To addresses with an associative array (setTo/setCc/setBcc)
      ->setTo(['receiver@domain.org', 'other@domain.org' => 'A name'])

      // Give it a body
      ->setBody('Here is the message itself')

      // And optionally an alternative body
      ->addPart('<q>Here is the message itself</q>', 'text/html')

      // Optionally add any attachments
      ->attach(Swift_Attachment::fromPath('my-document.pdf'))
      ;

Message Basics
--------------

A message is a container for anything you want to send to somebody else. There
are several basic aspects of a message that you should know.

An e-mail message is made up of several relatively simple entities that are
combined in different ways to achieve different results. All of these entities
have the same fundamental outline but serve a different purpose. The Message
itself can be defined as a MIME entity, an Attachment is a MIME entity, all
MIME parts are MIME entities -- and so on!

The basic units of each MIME entity -- be it the Message itself, or an
Attachment -- are its Headers and its body:

.. code-block:: text

    Header-Name: A header value
    Other-Header: Another value

    The body content itself

The Headers of a MIME entity, and its body must conform to some strict
standards defined by various RFC documents. Swift Mailer ensures that these
specifications are followed by using various types of object, including
Encoders and different Header types to generate the entity.

The Structure of a Message
~~~~~~~~~~~~~~~~~~~~~~~~~~

Of all of the MIME entities, a message -- ``Swift_Message`` is the largest and
most complex. It has many properties that can be updated and it can contain
other MIME entities -- attachments for example -- nested inside it.

A Message has a lot of different Headers which are there to present information
about the message to the recipients' mail client. Most of these headers will be
familiar to the majority of users, but we'll list the basic ones. Although it's
possible to work directly with the Headers of a Message (or other MIME entity),
the standard Headers have accessor methods provided to abstract away the