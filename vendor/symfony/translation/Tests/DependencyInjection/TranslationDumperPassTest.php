<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Contracts\Translation\Test;

use PHPUnit\Framework\TestCase;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Contracts\Translation\TranslatorTrait;

/**
 * Test should cover all languages mentioned on http://translate.sourceforge.net/wiki/l10n/pluralforms
 * and Plural forms mentioned on http://www.gnu.org/software/gettext/manual/gettext.html#Plural-forms.
 *
 * See also https://developer.mozilla.org/en/Localization_and_Plurals which mentions 15 rules having a maximum of 6 forms.
 * The mozilla code is also interesting to check for.
 *
 * As mentioned by chx http://drupal.org/node/1273968 we can cover all by testing number from 0 to 199
 *
 * The goal to cover all languages is to far fetched so this test case is smaller.
 *
 * @author Clemens Tolboom clemens@build2be.nl
 */
class TranslatorTest extends TestCase
{
    public function getTranslator()
    {
        return new class() implements TranslatorInterface {
            use TranslatorTrait;
        };
    }

    /**
     * @dataProvider getTransTests
     */
    public function testTrans($expected, $id, $parameters)
    {
        $translator = $this->getTranslator();

        $this->assertEquals($expected, $translator->trans($id, $parameters));
    }

    /**
     * @dataProvider getTransChoiceTests
     */
    public function testTransChoiceWithExplicitLocale($expected, $id, $number)
    {
        $translator = $this