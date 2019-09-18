<?php

namespace Faker\Provider;

class Internet extends Base
{
    protected static $freeEmailDomain = array('gmail.com', 'yahoo.com', 'hotmail.com');
    protected static $tld = array('com', 'com', 'com', 'com', 'com', 'com', 'biz', 'info', 'net', 'org');

    protected static $userNameFormats = array(
        '{{lastName}}.{{firstName}}',
        '{{firstName}}.{{lastName}}',
        '{{firstName}}##',
        '?{{lastName}}',
    );
    protected static $emailFormats = array(
        '{{userName}}@{{domainName}}',
        '{{userName}}@{{freeEmailDomain}}',
    );
    protected static $urlFormats = array(
        'http://www.{{domainName}}/',
        'http://{{domainName}}/',
        'http://www.{{domainName}}/{{slug}}',
        'http://www.{{domainName}}/{{slug}}',
        'https://www.{{domainName}}/{{slug}}',
        'http://www.{{domainName}}/{{slug}}.html',
        'http://{{domainName}}/{{slug}}',
        'http://{{domainName}}/{{slug}}',
        'http://{{domainName}}/{{slug}}.html',
        'https://{{domainName}}/{{slug}}.html',
    );

    /**
     * @example 'jdoe@acme.biz'
     */
    public function email()
    {
        $format = static::randomElement(static::$emailFormats);

        return $this->generator->parse($format);
    }

    /**
     * @example 'jdoe@example.com'
     */
    final public function safeEmail()
    {
        return preg_replace('/\s/u', '', $this->userName() . '@' . static::safeEmailDomain());
    }

    /**
     * @example 'jdoe@gmail.com'
     */
    public function freeEmail()
    {
        return preg_replace('/\s/u', '', $this->userName() . '@' . static::freeEmailDomain());
    }

    /**
     * @example 'jdoe@dawson.com'
     */
    public 