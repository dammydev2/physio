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

use phpDocumentor\Reflection\DocBlock\Tags\Factory\StaticMethod;
use phpDocumentor\Reflection\DocBlock\Tags\Generic;
use phpDocumentor\Reflection\FqsenResolver;
use phpDocumentor\Reflection\Types\Context as TypeContext;
use Webmozart\Assert\Assert;

/**
 * Creates a Tag object given the contents of a tag.
 *
 * This Factory is capable of determining the appropriate class for a tag and instantiate it using its `create`
 * factory method. The `create` factory method of a Tag can have a variable number of arguments; this way you can
 * pass the dependencies that you need to construct a tag object.
 *
 * > Important: each parameter in a