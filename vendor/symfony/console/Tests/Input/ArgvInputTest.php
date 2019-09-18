 bar', array('%count%' => $number)));
    }

    public function getInternal()
    {
        return array(
            array('foo', 3, '{1,2, 3 ,4}'),
            array('bar', 10, '{1,2, 3 ,4}'),
            array('bar', 3, '[1,2]'),
            array('foo', 1, '[1,2]'),
            array('foo', 2, '[1,2]'),
            array('bar', 1, ']1,2['),
            array('bar', 2, ']1,2['),
            array('foo', log(0), '[-Inf,2['),
            array('foo', -log(0), '[-2,+Inf]'),
        );
    }

    /**
     * @dataProvider getChooseTests
     */
    public function testChoose($expected, $id, $number)
    {
        $translator = $this->getTranslator();

        $this->assertEquals($expected, $translator->trans($id, array('%count%' => $number)));
    }

    public function testReturnMessageIfExactlyOneStandardRuleIsGiven()
    {
        $translator = $this->getTranslator();

        $this->assertEquals('There are two apples', $translator->trans('There are two apples', array('%count%' => 2)));
    }

    /**
     * @dataProvider getNonMatchingMessages
     * @expectedException \InvalidArgumentException
     */
    public function testThrowExceptionIfMatchingMessageCannotBeFound($id, $number)
    {
        $translator = $this->getTranslator();

        $translator->trans($id, array('%count%' => $number));
    }

    public function getNonMatchingMessages()
    {
        return array(
            array('{0} There are no apples|{1} There is one apple', 2),
            array('{1} There is one apple|]1,Inf] There are %count% apples', 0),
            array('{1} There is one apple|]2,Inf] There are %count% apples', 2),
            array('{0} There are no apples|There is one apple', 2),
        );
    }

    public function getChooseTests()
    {
        return array(
            array('There are no apples', '{0} There are no apples|{1} There is one apple|]1,Inf] There are %count% apples', 0),
            array('There are no apples', '{0}     There are no apples|{1} There is one apple|]1,Inf] There are %count% apples', 0),
            array('There are no apples', '{0}There are no apples|{1} There is one apple|]1,Inf] There are %count% apples', 0),

            array('There is one apple', '{0} There are no apples|{1} There is one apple|]1,Inf] There are %count% apples', 1),

            array('There are 10 apples', '{0} There are no apples|{1} There is one apple|]1,Inf] There are %count% apples', 10),
            array('There are 10 apples', '{0} There are no apples|{1} There is one apple|]1,Inf]There are %count% apples', 10),
            array('There are 10 apples', '{0} There are no apples|{1} There is one apple|]1,Inf]     There are %count% apples', 10),

            array('There are 0 apples', 'There is one apple|There are %count% apples', 0),
            array('There is one apple', 'There is one apple|There are %count% apples', 1),
            array('There are 10 apples', 'There is one apple|There are %count% apples', 10),

            array('There are 0 apples', 'one: There is one apple|more: There are %count% apples', 0),
            array('There is one apple', 'one: There is one apple|more: There are %count% apples', 1),
            array('There are 10 apples', 'one: There is one apple|more: There are %count% apples', 10),

            array('There are no apples', '{0} There are no apples|one: There is one apple|more: There are %count% apples', 0),
            array('There is one apple', '{0} There are no apples|one: There is one apple|more: There are %count% apples', 1),
            array('There are 10 apples', '{0} There are no apples|one: There is one apple|more: There are %count% apples', 10),

            array('', '{0}|{1} There is one apple|]1,Inf] There are %count% apples', 0),
            array('', '{0} There are no apples|{1}|]1,Inf] There are %count% apples', 1),

            // Indexed only tests which are Gettext PoFile* compatible strings.
            array('There are 0 apples', 'There is one apple|There are %count% apples', 0),
            array('There is one apple', 'There is one apple|There are %count% apples', 1),
            array('There are 2 apples', 'There is one apple|There are %count% apples', 2),

            // Tests for float numbers
            array('There is almost one apple', '{0} There are no apples|]0,1[ There is almost one apple|{1} There is one apple|[1,Inf] There is more than one apple', 0.7),
            array('There is one apple', '{0} There are no apples|]0,1[There are %count% apples|{1} There is one apple|[1,Inf] There is more than one apple', 1),
            array('There is more than one apple', '{0} There are no apples|]0,1[There are %count% apples|{1} There is one apple|[1,Inf] There is more than one apple', 1.7),
            array('There are no apples', '{0} There are no apples|]0,1[There are %count% apples|{1} There is one apple|[1,Inf] There is more than one apple', 0),
            array('There are no apples', '{0} There are no apples|]0,1[There are %count% apples|{1} There is one apple|[1,Inf] There is more than one apple', 0.0),
            array('There are no apples', '{0.0} There are no apples|]0,1[There are %count% apples|{1} There is one apple|[1,Inf] There is more than one apple', 0),

            // Test texts with new-lines
            // with double-quotes and \n in id & double-quotes and actual newlines in text
            array("This is a text with a\n            new-line in it. Selector = 0.", '{0}This is a text with a
            new-line in it. Selector = 0.|{1}This is a text with a
            new-line in it. Selector = 1.|[1,Inf]This is a text with a
            new-line in it. Selector > 1.', 0),
            // with double-quotes and \n in id and single-quotes and actual newlines in text
            array("This is a text with a\n            new-line in it. Selector = 1.", '{0}This is a text with a
            new-line in it. Selector = 0.|{1}This is a text with a
            new-line in it. Selector = 1.|[1,Inf]This is a text with a
            new-line in it. Selector > 1.', 1),
            array("This is a text with a\n            new-line in it. Selector > 1.", '{0}This is a text with a
            new-line in it. Selector = 0.|{1}This is a text with a
            new-line in it. Selector = 1.|[1,Inf]This is a text with a
            new-line in it. Selector > 1.', 5),
            // with double-quotes and id split accros lines
            array('This is a text with a
            new-line in it. Selector = 1.', '{0}This is a text with a
            new-line in it. Selector = 0.|{1}This is a text with a
            new-line in it. Selector = 1.|[1,Inf]This is a text with a
            new-line in it. Selector > 1.', 1),
            // with single-quotes and id split accros lines
            array('This is a text with a
            new-line in it. Selector > 1.', '{0}This is a text with a
            new-line in it. Selector = 0.|{1}This is a text with a
            new-line in it. Selector = 1.|[1,Inf]This is a text with a
            new-line in it. Selector > 1.', 5),
            // with single-quotes and \n in text
            array('This is a text with a\nnew-line in it. Selector = 0.', '{0}This is a text with a\nnew-line in it. Selector = 0.|{1}This is a text with a\nnew-line in it. Selector = 1.|[1,Inf]This is a text with a\nnew-line in it. Selector > 1.', 0),
            // with double-quotes and id split accros lines
            array("This is a text with a\nnew-line in it. Selector = 1.", "{0}This is a text with a\nnew-line in it. Selector = 0.|{1}This is a text with a\nnew-line in it. Selector = 1.|[1,Inf]This is a text with a\nnew-line in it. Selector > 1.", 1),
            // esacape pipe
            array('This is a text with | in it. Selector = 0.', '{0}This is a text with || in it. Selector = 0.|{1}This is a text with || in it. Selector = 1.', 0),
            // Empty plural set (2 plural forms) from a .PO file
            array('', '|', 1),
            // Empty plural set (3 plural forms) from a .PO file
            array('', '||', 1),
        );
    }

    /**
     * @dataProvider failingLangcodes
     */
    public function testFailedLangcodes($nplural, $langCodes)
    {
        $matrix = $this->generateTestData($langCodes);
        $this->validateMatrix($nplural, $matrix, false);
    }

    /**
     * @dataProvider successLangcodes
     */
    public function testLangcodes($nplural, $langCodes)
    {
        $matrix = $this->generateTestData($langCodes);
        $this->validateMatrix($nplural, $matrix);
    }

    /**
     * This array should contain all currently known langcodes.
     *
     * As it is impossible to have this ever complete we should try as hard as possible to have it almost complete.
     *
     * @return array
     */
    public function successLangcodes()
    {
        return array(
            array('1', array('ay', 'bo', 'cgg', 'dz', 'id', 'ja', 'jbo', 'ka', 'kk', 'km', 'ko', 'ky')),
            array('2', array('nl', 'fr', 'en', 'de', 'de_GE', 'hy', 'hy_AM')),
            array('3', array('be', 'bs', 'cs', 'hr')),
            array('4', array('cy', 'mt', 'sl')),
            array('6', array('ar')),
        );
    }

    /**
     * This array should be at least empty within the near future.
     *
     * This both depends on a complete list trying to add above as understanding
     * the plural rules of the current failing languages.
     *
     * @return array with nplural together with langcodes
     */
    public function failingLangcodes()
    {
        return array(
            array('1', array('fa')),
            array('2', array('jbo')),
            array('3', array('cbs')),
            array('4', array('gd', 'kw')),
            array('5', array('ga')),
        );
    }

    /**
     * We validate only on the plural coverage. Thus the real rules is not tested.
     *
     * @param string $nplural       Plural expected
     * @param array  $matrix        Containing langcodes and their plural index values
     * @param bool   $expectSuccess
     */
    protected function validateMatrix($nplural, $matrix, $expectSuccess = true)
    {
        foreach ($matrix as $langCode => $data) {
            $indexes = array_flip($data);
            if ($expectSuccess) {
                $this->assertEquals($nplural, \count($indexes), "Langcode '$langCode' has '$nplural' plural forms.");
            } else {
                $this->assertNotEquals((int) $nplural, \count($indexes), "Langcode '$langCode' has '$nplural' plural forms.");
            }
        }
    }

    protected function generateTestData($langCodes)
    {
        $translator = new class() {
            use TranslatorTrait {
                getPluralizationRule as public;
            }
        };

        $matrix = array();
        foreach ($langCodes as $langCode) {
            for ($count = 0; $count < 200; ++$count) {
                $plural = $translator->getPluralizationRule($count, $langCode);
                $matrix[$langCode][$count] = $plural;
            }
        }

        return $matrix;
    }
}
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                               <?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Contracts\Translation;

/**
 * @author Fabien Potencier <fabien@symfony.com>
 */
interface TranslatorInterface
{
    /**
     * Translates the given message.
     *
     * When a number is provided as a parameter named "%count%", the message is parsed for plural
     * forms and a translation is chosen according to this number using the following rules:
     *
     * Given a message with different plural translations separated by a
     * pipe (|), this method returns the correct portion of the message based
     * on the given number, locale and the pluralization rules in the message
     * itself.
     *
     * The message supports two different types of pluralization rules:
     *
     * interval: {0} There are no apples|{1} There is one apple|]1,Inf] There are %count% apples
     * indexed:  There is one apple|There are %count% apples
     *
     * The indexed solution can also contain labels (e.g. one: There is one apple).
     * This is purely for making the translations more clear - it does not
     * affect the functionality.
     *
     * The two methods can also be mixed:
     *     {0} There are no apples|one: There is one apple|more: There are %count% apples
     *
     * An interval can represent a finite set of numbers:
     *  {1,2,3,4}
     *
     * An interval can represent numbers between two numbers:
     *  [1, +Inf]
     *  ]-1,2[
     *
     * The left delimiter can be [ (inclusive) or ] (exclusive).
     * The right delimiter can be [ (exclusive) or ] (inclusive).
     * Beside numbers, you can use -Inf and +Inf for the infinite.
     *
     * @see https://en.wikipedia.org/wiki/ISO_31-11
     *
     * @param string      $id         The message id (may also be an object that can be cast to string)
     * @param array       $parameters An array of parameters for the message
     * @param string|null $domain     The domain for the message or null to use the default
     * @param string|null $locale     The locale or null to use the default
     *
     * @return string The translated string
     *
     * @throws \InvalidArgumentException If the locale contains invalid characters
     */
    public function trans($id, array $parameters = array(), $domain = null, $locale = null);
}
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                  <?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Contracts\Translation;

use Symfony\Component\Translation\Exception\InvalidArgumentException;

/**
 * A trait to help implement TranslatorInterface and LocaleAwareInterface.
 *
 * @author Fabien Potencier <fabien@symfony.com>
 */
trait TranslatorTrait
{
    private $locale;

    /**
     * {@inheritdoc}
     */
    public function setLocale($locale)
    {
        $this->locale = (string) $locale;
    }

    /**
     * {@inheritdoc}
     */
    public function getLocale()
    {
        return $this->locale ?: \Locale::getDefault();
    }

    /**
     * {@inheritdoc}
     */
    public function trans($id, array $parameters = array(), $domain = null, $locale = null)
    {
        $id = (string) $id;

        if (!isset($parameters['%count%']) || !is_numeric($parameters['%count%'])) {
            return strtr($id, $parameters);
        }

        $number = (float) $parameters['%count%'];
        $locale = (string) $locale ?: $this->getLocale();

        $parts = array();
        if (preg_match('/^\|++$/', $id)) {
            $parts = explode('|', $id);
        } elseif (preg_match_all('/(?:\|\||[^\|])++/', $id, $matches)) {
            $parts = $matches[0];
        }

        $intervalRegexp = <<<'EOF'
/^(?P<interval>
    ({\s*
        (\-?\d+(\.\d+)?[\s*,\s*\-?\d+(\.\d+)?]*)
    \s*})

        |

    (?P<left_delimiter>[\[\]])
        \s*
        (?P<left>-Inf|\-?\d+(\.\d+)?)
        \s*,\s*
        (?P<right>\+?Inf|\-?\d+(\.\d+)?)
        \s*
    (?P<right_delimiter>[\[\]])
)\s*(?P<message>.*?)$/xs
EOF;

        $standardRules = array();
        foreach ($parts as $part) {
            $part = trim(str_replace('||', '|', $part));

            // try to match an explicit rule, then fallback to the standard ones
            if (preg_match($intervalRegexp, $part, $matches)) {
                if ($matches[2]) {
                    foreach (explode(',', $matches[3]) as $n) {
                        if ($number == $n) {
                            return strtr($matches['message'], $parameters);
                        }
                    }
                } else {
                    $leftNumber = '-Inf' === $matches['left'] ? -INF : (float) $matches['left'];
                    $rightNumber = \is_numeric($matches['right']) ? (float) $matches['right'] : INF;

                    if (('[' === $matches['left_delimiter'] ? $number >= $leftNumber : $number > $leftNumber)
                        && (']' === $matches['right_delimiter'] ? $number <= $rightNumber : $number < $rightNumber)
                    ) {
                        return strtr($matches['message'], $parameters);
                    }
                }
            } elseif (preg_match('/^\w+\:\s*(.*?)$/', $part, $matches)) {
                $standardRules[] = $matches[1];
            } else {
                $standardRules[] = $part;
            }
        }

        $position = $this->getPluralizationRule($number, $locale);

        if (!isset($standardRules[$position])) {
            // when there's exactly one rule given, and that rule is a standard
            // rule, use this rule
            if (1 === \count($parts) && isset($standardRules[0])) {
                return strtr($standardRules[0], $parameters);
            }

            $message = sprintf('Unable to choose a translation for "%s" with locale "%s" for value "%d". Double check that this translation has the correct plural options (e.g. "There is one apple|There are %%count%% apples").', $id, $locale, $number);

            if (\class_exists(InvalidArgumentException::class)) {
                throw new InvalidArgumentException($message);
            }

            throw new \InvalidArgumentException($message);
        }

        return strtr($standardRules[$position], $parameters);
    }

    /**
     * Returns the plural position to use for the given locale and number.
     *
     * The plural rules are derived from code of the Zend Framework (2010-09-25),
     * which is subject to the new BSD license (http://framework.zend.com/license/new-bsd).
     * Copyright (c) 2005-2010 Zend Technologies USA Inc. (http://www.zend.com)
     */
    private function getPluralizationRule(int $number, string $locale): int
    {
        switch ('pt_BR' !== $locale && \strlen($locale) > 3 ? substr($locale, 0, strrpos($locale, '_')) : $locale) {
            case 'af':
            case 'bn':
            case 'bg':
            case 'ca':
            case 'da':
            case 'de':
            case 'el':
            case 'en':
            case 'eo':
            case 'es':
            case 'et':
            case 'eu':
            case 'fa':
            case 'fi':
            case 'fo':
            case 'fur':
            case 'fy':
            case 'gl':
            case 'gu':
            case 'ha':
            case 'he':
            case 'hu':
            case 'is':
            case 'it':
            case 'ku':
            case 'lb':
            case 'ml':
            case 'mn':
            case 'mr':
            case 'nah':
            case 'nb':
            case 'ne':
            case 'nl':
            case 'nn':
            case 'no':
            case 'oc':
            case 'om':
            case 'or':
            case 'pa':
            case 'pap':
            case 'ps':
            case 'pt':
            case 'so':
            case 'sq':
            case 'sv':
            case 'sw':
            case 'ta':
            case 'te':
            case 'tk':
            case 'ur':
            case 'zu':
                return (1 == $number) ? 0 : 1;

            case 'am':
            case 'bh':
            case 'fil':
            case 'fr':
            case 'gun':
            case 'hi':
            case 'hy':
            case 'ln':
            case 'mg':
            case 'nso':
            case 'pt_BR':
            case 'ti':
            case 'wa':
                return ((0 == $number) || (1 == $number)) ? 0 : 1;

            case 'be':
            case 'bs':
            case 'hr':
            case 'ru':
            case 'sh':
            case 'sr':
            case 'uk':
                return ((1 == $number % 10) && (11 != $number % 100)) ? 0 : ((($number % 10 >= 2) && ($number % 10 <= 4) && (($number % 100 < 10) || ($number % 100 >= 20))) ? 1 : 2);

            case 'cs':
            case 'sk':
                return (1 == $number) ? 0 : ((($number >= 2) && ($number <= 4)) ? 1 : 2);

            case 'ga':
                return (1 == $number) ? 0 : ((2 == $number) ? 1 : 2);

            case 'lt':
                return ((1 == $number % 10) && (11 != $number % 100)) ? 0 : ((($number % 10 >= 2) && (($number % 100 < 10) || ($number % 100 >= 20))) ? 1 : 2);

            case 'sl':
                return (1 == $number % 100) ? 0 : ((2 == $number % 100) ? 1 : (((3 == $number % 100) || (4 == $number % 100)) ? 2 : 3));

            case 'mk':
                return (1 == $number % 10) ? 0 : 1;

            case 'mt':
                return (1 == $number) ? 0 : (((0 == $number) || (($number % 100 > 1) && ($number % 100 < 11))) ? 1 : ((($number % 100 > 10) && ($number % 100 < 20)) ? 2 : 3));

            case 'lv':
                return (0 == $number) ? 0 : (((1 == $number % 10) && (11 != $number % 100)) ? 1 : 2);

            case 'pl':
                return (1 == $number) ? 0 : ((($number % 10 >= 2) && ($number % 10 <= 4) && (($number % 100 < 12) || ($number % 100 >