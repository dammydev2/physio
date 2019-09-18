<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\HttpFoundation\File\MimeType;

/**
 * Provides a best-guess mapping of mime type to file extension.
 */
class MimeTypeExtensionGuesser implements ExtensionGuesserInterface
{
    /**
     * A map of mime types and their default extensions.
     *
     * This list has been placed under the public domain by the Apache HTTPD project.
     * This list has been updated from upstream on 2019-01-14.
     *
     * @see https://svn.apache.org/repos/asf/httpd/httpd/trunk/docs/conf/mime.types
     */
    protected $defaultExtensions = [
        'application/andrew-inset' => 'ez',
        'application/applixware' => 'aw',
        'application/atom+xml' => 'atom',
        'application/atomcat+xml' => 'atomcat',
        'application/atomsvc+xml' => 'atomsvc',
        'application/ccxml+xml' => 'ccxml',
        'application/cdmi-capability' => 'cdmia',
        'application/cdmi-container' => 'cdmic',
        'application/cdmi-domain' => 'cdmid',
        'application/cdmi-object' => 'cdmio',
        'application/cdmi-queue' => 'cdmiq',
        'application/cu-seeme' => 'cu',
        'appl