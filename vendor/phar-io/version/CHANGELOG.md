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

namespace phpDocumentor\Reflection\DocBlock\Tags;

use phpDocumentor\Reflection\DocBlock\Description;
use phpDocumentor\Reflection\DocBlock\DescriptionFactory;
use phpDocumentor\Reflection\Fqsen;
use phpDocumentor\Reflection\FqsenResolver;
use phpDocumentor\Reflection\Types\Context as TypeContext;
use Webmozart\Assert\Assert;

/**
 * Reflection class for a @covers tag in a Docblock.
 */
final class Covers extends BaseTag implements Factory\StaticMethod
{
    protected $name = 'covers';

    /** @var Fqsen */
    private $refers = null;

    /**
     * Initializes this tag.
     *
     * @param Fqsen $refers
     * @param Description $description
     */
    public function __construct(Fqsen $refers, Description $description = null)
    {
        $this->refers = $refers;
        $this->description = $description;
    }

    /**
     * {@inheritdoc}
     */
    public static function create(
        $body,
        DescriptionFactory $descriptionFactory = null,
        FqsenResolver $resolver = null,
        TypeContext $context 