<?php
namespace Hamcrest\Xml;

/*
 Copyright (c) 2009 hamcrest.org
 */
use Hamcrest\Core\IsEqual;
use Hamcrest\Description;
use Hamcrest\DiagnosingMatcher;
use Hamcrest\Matcher;

/**
 * Matches if XPath applied to XML/HTML/XHTML document either
 * evaluates to result matching the matcher or returns at least
 * one node, matching the matcher if present.
 */
class HasXPath extends DiagnosingMatcher
{

    /**
     * XPath to apply to the DOM.
     *
     * @var string
     */
    private $_xpath;

    /**
     * Optional matcher to apply to the XPath expression result
     * or the content of the returned nodes.
     *
     * @var Matcher
     */
    private $_matcher;

    public function __construct($xpath, Matcher $matcher = null)
    {
        $this->_xpath = $xpath;
        $this->_matcher = $matcher;
    }

    /**
     * Matches if the XPath matches against the DOM node and the matcher.
     *
     * @param string|\DOMNode $actual
     * @param Description $mismatchDescription
     * @return bool
     */
    protected function matchesWithDiagnosticDescription($actual, Description $mismatchDescription)
    {
        if (is_string($actual)) {
            $actual = $this->createDocument($actual);
        } elseif (!$actual instanceof \DOMNode) {
            $mismatchDescription->appendText('was ')->appendValue($actual);

            return false;
        }
        $result = $this->evaluate($actual);
        if ($result instanceof \DOMNodeList) {
            return $this->matchesContent($result, $mismatchDescription);
        } else {
            return $this->matchesExpression($result, $mismatchDescription);
        }
    }

    /**
     * Creates and return