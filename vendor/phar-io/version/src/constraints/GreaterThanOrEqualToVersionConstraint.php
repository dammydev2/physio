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
use phpDocumentor\Reflection\DocBlock\Tags\Reference\Fqsen as FqsenRef;
use phpDocumentor\Reflection\DocBlock\Tags\Reference\Reference;
use phpDocumentor\Reflection\DocBlock\Tags\Reference\Url;
use phpDocumentor\Reflection\FqsenResolver;
use phpDocumentor\Reflection\Types\Context as TypeContext;
use Webmozart\Assert\Assert;

/**
 * Reflection class for an {@}see tag in a Docblock.
 */
class See extends BaseTag implements Factory\StaticMethod
{
    protected $name = 'see';

    /** @var Reference */
    protected $refers = null;