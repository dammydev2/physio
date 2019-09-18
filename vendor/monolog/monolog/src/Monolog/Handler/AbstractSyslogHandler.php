<?php

/*
 * This file is part of the Monolog package.
 *
 * (c) Jordi Boggiano <j.boggiano@seld.be>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Monolog\Handler;

use Monolog\Formatter\FormatterInterface;
use Monolog\Logger;
use Monolog\Handler\Slack\SlackRecord;

/**
 * Sends notifications through Slack API
 *
 * @author Greg Kedzierski <greg@gregkedzierski.com>
 * @see    https://api.slack.com/
 */
class SlackHandler extends SocketHandler
{
    /**
     * Slack API token
     * @var string
     */
    private $token;

    /**
     * Instance of the SlackRecord util class preparing data for Slack API.
     * @var SlackRecord
     */
    private $slackRecord;

    /**
     * @param  string                    $token                  Slack API token
     * @param  string                    $channel                Slack channel (encoded ID or name)
     * @param  string|null               $username               Name of a bot
     * @param  bool                      $useAttachment          Whether the message should be added to Slack as attachment (plain text otherwise)
     * @param  string|null               $iconEmoji              The emoji name to use (or null)
     * @param  int                       $level                  The minimum logging level at which this handler will be triggered
     * @param  bool                      $bubble                 Whether the messages that are handled can bubble up the stack or not
     * @param  bool                      $useShortAttachment     Whether the the context/extra messages added to Slack as attachments are in a short style
     * @param  bool                      $includeContextAndExtra Whether the attachment should include context and extra data
     * @param  array                     $excludeFields          Dot separated list of fields to exclude from slack message. E.g. ['context.field1', 'extra.field2']
     * @throws MissingExtensionException If no OpenSSL PHP extension configured
     */
    public function __construct($token, $channel, $username = null, $useAttachment = true, $iconEmoji = null, $level = Logger::CRITICAL, $bubble = true, $useShortAttachment = false, $includeContextAndExtra = false, array $excludeFields = array())
    {
        if (!extension_loaded('openssl')) {
            throw new MissingExtensionException('The OpenSSL PHP extension is required to use the SlackHandler');
        }

        parent::__construct('ssl://slack.com:443', $level, $bubble);

        $this->slackRecord = new SlackRecord(
            $channel,
            $username,
            $useAttachment,
            $iconEmoji,
            $useShortAttachment,
            $includeContextAndExtra,
            $excludeFields,
            $this->formatter
        );

        $this->token = $token;
    }

    public function getSlackRecord()
    {
        return $this->slackRecord;
    }

    public function getToken()
    {
        return $this->token;
    }

    /**
     * {@inheritdoc}
     *
     * @param  array  $record
     * @return string
     */
    protected function generateDataStream($record)
    {
        $content = $this->buildContent($record);

        return $this->buildHeader($content) . $content;
    }

    /**
     * Builds the body of API call
     *
    