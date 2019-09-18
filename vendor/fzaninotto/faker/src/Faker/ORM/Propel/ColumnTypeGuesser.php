<?php

namespace Faker\Provider;

class UserAgent extends Base
{
    protected static $userAgents = array('firefox', 'chrome', 'internetExplorer', 'opera', 'safari');

    protected static $windowsPlatformTokens = array(
        'Windows NT 6.2', 'Windows NT 6.1', 'Windows NT 6.0', 'Windows NT 5.2', 'Windows NT 5.1',
        'Windows NT 5.01', 'Windows NT 5.0', 'Windows NT 4.0', 'Windows 98; Win 9x 4.90', 'Windows 98',
        'Windows 95', 'Windows CE'
    );

    /**
     * Possible processors on Linux
     */
    protected static $linuxProcessor = array('i686', 'x86_64');

    /**
     * Mac processors (it also added U;)
     */
    protected static $macProcessor = array('Intel', 'PPC', 'U; Intel', 'U; PPC');

    /**
     * Add as many languages as you like.
     */
    protected static $lang = array('en-US', 'sl-SI');

    /**
     * Generate mac processor
     *
     * @return string
     */
    public static function macProcessor()
    {
        return static::randomElement(static::$macProcessor);
    }

    /**
     * Generate linux processor
     *
     * @return string
     */
    public static function linuxProcessor()
    {
        return static::randomElement(static::$linuxProcessor);
    }

    /**
     * Generate a random user agent
     *
     * @example 'Mozilla/5.0 (Windows CE) AppleWebKit/5350 (KHTML, like Gecko) Chrome/13.0.888.0 Safari/5350'
     */
    public static function userAgent()
    {
        $userAgentName = static::randomElement(static::$userAgents);

        return static::$userAgentName();
    }

    /**
     * Generate Chrome user agent
     *
     * @example 'Mozilla/5.0 (Macintosh; PPC Mac OS X 10_6_5) AppleWebKit/5312 (KHTML, like Gecko) Chrome/14.0.894.0 Safari/5312'
     */
    public static function chrome()
    {
        $saf = mt_rand(531, 536) . mt_rand(0, 2);

        $platforms = array(
            '(' . static::linuxPlatformToken() . ") AppleWebKit/$saf (KHTML, like Gecko) Chrome/" . mt_rand(36, 40) . '.0.' . mt_rand(800, 899) . ".0 Mobile Safari/$saf",
            '(' . static::windowsPlatformToken() . ") AppleWebKit/$saf (KHTML, like Gecko) Chrome/" . mt_rand(36, 40) . '.0.' . mt_rand(800, 899) . ".0 Mobile Safari/$saf",
            '(' . static::macPlatformToken() . ") AppleWebKit/$saf (KHTML, like Gecko) Chrome/" . mt_rand(36, 40) . '.0.' . mt_rand(800, 899) . ".0 Mobile Safari/$saf"
        );

        return 'Mozilla/5.0 ' . static::randomElement($platforms);
    }

    /**
     * Generate Firefox user agent
     *
     * @example 'Mozilla/5.0 (X11; Linuxi686; rv:7.0) Gecko/20101231 Firefox/3.6'
     */
    public static function firefox()
    {
        $ver = 'Gecko/' . date('Ymd', mt_rand(strtotime('2010-1-1'), time())) . ' Firefox/' . mt_rand(35, 37) . '.0';

        $platforms = array(
            '(' . static::windowsPlatformToken() . '; ' . static::randomElement(static::$lang) . '; rv:1.9.' . mt_rand(0, 2) . '.20) ' . $ver,
            '(' . static::linuxPlatformToken() . '; rv:' . mt_rand(5, 7) . '.0) ' . $ver,
            '(' . static::macPlatformToken() . ' rv:' . mt_rand(2, 6) . '.0) ' . $ver
        );

        return "Mozilla/5.0 " . static::randomElement($platforms);
    }

    /**
     * Generate Safari user agent
     *
     * @example 'Mozilla/5.0 (Macintosh; U; PPC Mac OS X 10_7_1 rv:3.0; en-US) AppleWebKit/534.11.3 (KHTML, like Gecko) Version/4.0 Safari/534.11.3'
     */
    public static function safari()
    {
        $saf = mt_rand(531, 535) . '.' . mt_rand(1, 50) . '.' . mt_rand(1, 7);
        if (mt_rand(0, 1) == 0) {
            $ver = mt_rand(4, 5) . '.' . mt_rand(0, 1);
     