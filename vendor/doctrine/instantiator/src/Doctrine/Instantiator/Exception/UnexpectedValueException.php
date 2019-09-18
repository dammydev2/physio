<?php

namespace Egulias\EmailValidator;

use Egulias\EmailValidator\Exception\ExpectingATEXT;
use Egulias\EmailValidator\Exception\NoLocalPart;
use Egulias\EmailValidator\Parser\DomainPart;
use Egulias\EmailValidator\Parser\LocalPart;
use Egulias\EmailValidator\Warning\EmailTooLong;

/**
 * EmailParser
 *
 * @author Eduardo Gulias Davis <me@egulias.com>
 */
class EmailParser
{
    const EMAIL_MAX_LENGTH = 254;

    protected $warnings;
    protected $domainPart = '';
    protected $localPart = '';
    protected $lexer;
    protected $localPartParser;
    protected $domainPartParser;

    public function __construct(EmailLexer $lexer)
    {
        $this->lexer = $lexer;
        $this->localPartParser = new LocalPart($this->lexer);
        $this->domainPartParser = new DomainPart($this->lexer);
        $this->warnings = new \SplObjectStorage();
    }

    /**
     * @param $str
     * @return array
     */
    public function parse($str)
    {
        $this->lexer->setInput($str);

        if (!$this->hasAtToken()) {
            throw new NoLocalPart();
        }


        $this->localPartParser->parse($str);
        $this->domainPartParser->parse($str);

        $this->setParts($str);

        if ($this->lexer->hasInvalidTokens()) {
            throw new ExpectingATEXT();
        }

        return array('local' => $this->localPart, 'domain' => $this->domainPart);
    }

    public