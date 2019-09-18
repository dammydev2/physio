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

namespace phpDocumentor\Reflection\Types;

/**
 * Provides information about the Context in which the DocBlock occurs that receives this context.
 *
 * A DocBlock does not know of its own accord in which namespace it occurs and which namespace aliases are applicable
 * for the block of code in which it is in. This information is however necessary to resolve Class names in tags since
 * you can provide a short form or make use of namespace aliases.
 *
 * The phpDocumentor Reflection component knows how to create this class but if you use the DocBlock parser from your
 * own application it is possible to generate a Context class using the ContextFactory; this will analyze the file in
 * which an associated class resides for its namespace and imports.
 *
 * @see ContextFactory::createFromClassReflector()
 * @see ContextFactory::createForNamespace()
 */
final class Context
{
    /** @var string The current namespace. */
    private $namespace;

    /** @var array List of namespace aliases => Fully 