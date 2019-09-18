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

namespace phpDocumentor\Reflection;

use phpDocumentor\Reflection\DocBlock\Tag;
use Webmozart\Assert\Assert;

final class DocBlock
{
    /** @var string The opening line for this docblock. */
    private $summary = '';

    /** @var DocBlock\Description The actual description for this docblock. */
    private $description = null;

    /** @var Tag[] An array containing all the tags in this docblock; except inline. */
    private $tags = [];

    /** @var Types\Context Information about the context of this DocBlock. */
    private $context = null;

    /** @var Location Information a