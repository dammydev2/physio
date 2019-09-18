<?php
/**
 * This file is part of phpDocumentor.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @copyright 2010-2015 Mike van Riel<mike@phpdoc.org>
 * @license   http://www.opensource.org/licenses/mit-license.php MIT
 * @link      http://phpdoc.org
 */

namespace phpDocumentor\Reflection\DocBlock;

use phpDocumentor\Reflection\Types\Context as TypeContext;

/**
 * Creates a new Description object given a body of text.
 *
 * Descriptions in phpDocumentor are somewhat complex entities as they can contain one or more tags inside their
 * body that can be replaced with a readable output. The replacing is done by passing a Formatter object to the
 * Description object's `render` method.
 *
 * In addition to the above does a Description support two types of escape sequences:
 *
 * 1. `{@}` to escape the `@` character to prevent it from being interpreted as part of a tag, i.e. `{{@}link}`
 * 2. `{}` to escape the `}` character, this can be used if you want to use the `}` character in the description
 *    of an inline tag.
 *
 * If a body consists of multiple lines then this factory will also remove any superfluous whitespace at the beginning
 * of each line while maintaining any indentation that is used. This will prevent formatting parsers from tripping
 * over unexpected spaces as can be observed with tag descriptions.
 */
class DescriptionFactory
{
    /** @var TagFactory */
    private $tagFactory;

    /**
     * Initializes this factory with the means to construct (inline) tags.
     *
     * @param TagFactory $tagFactory
     */
    public function __construct(TagFactory $tagFactory)
    {
        $this->tagFactory = $tagFactory;
    }

    /**
     * Returns the parsed text of this description.
     *
     * @param string $contents
     * @param TypeContext $context
     *
     * @return Description
     */
    public function create($contents, TypeContext $context = null)
    {
        list($text, $tags) = $th