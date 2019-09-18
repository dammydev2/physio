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

use phpDocumentor\Reflection\Types\Array_;
use phpDocumentor\Reflection\Types\Compound;
use phpDocumentor\Reflection\Types\Context;
use phpDocumentor\Reflection\Types\Iterable_;
use phpDocumentor\Reflection\Types\Nullable;
use phpDocumentor\Reflection\Types\Object_;

final class TypeResolver
{
    /** @var string Definition of the ARRAY operator for types */
    const OPERATOR_ARRAY = '[]';

    /** @var string Definition of the NAMESPACE operator in PHP */
    const OPERATOR_NAMESPACE = '\\';

    /** @var string[] List of recognized keywords and unto which Value Object they map */
    private $keywords = array(
        'string' => Types\String_::class,
        'int' => Types\Integer::class,
        'integer' => Types\Integer::class,
        'bool' => Types\Boolean::class,
        'boolean' => Types\Boolean::class,
        'float' => Types\Float_::class,
        'double' => Types\Float_::class,
        'object' => Object_::class,
     