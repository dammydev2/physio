<?php
/**
 * This file is part of the ramsey/uuid library
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @copyright Copyright (c) Ben Ramsey <ben@benramsey.com>
 * @license http://opensource.org/licenses/MIT MIT
 * @link https://benramsey.com/projects/ramsey-uuid/ Documentation
 * @link https://packagist.org/packages/ramsey/uuid Packagist
 * @link https://github.com/ramsey/uuid GitHub
 */

namespace Ramsey\Uuid;

use Ramsey\Uuid\Converter\NumberConverterInterface;
use Ramsey\Uuid\Exception\UnsupportedOperationException;

/**
 * UuidInterface defines common functionality for all universally unique
 * identifiers (UUIDs)
 */
interface UuidInterface extends \JsonSerializable, \Serializable
{
    /**
     * Compares this UUID to the specified UUID.
     *
     * The first of two UUIDs is greater than the second if the most
     * significant field in which the UUIDs differ is greater for the first
     * UUID.
     *
     * * Q. What's the value of being able to sort UUIDs?
     * * A. Use them as keys in a B-Tree or similar mapping.
     *
     * @param UuidInterface $other UUID to which this UUID is compared
     * @return int -1, 0 or 1 as this UUID is less than, equal to, or greater than `$uuid`
     */
    public function compareTo(UuidInterface $other);

    /**
     * Compares this object to the specified object.
     *
     * The result is true if and only if the argument is not null, is a UUID
     * object, has the same variant, and contains the same value, bit for bit,
     * as this UUID.
     *
     * @param object $other
     * @return bool True if `$other` is equal to this UUID
     */
    public function equals($other);

    /**
     * Returns the UUID as a 16-byte string (containing the six integer fields
     * in big-endian byte order).
     *
     * @return string
     */
    public function getBytes();

    /**
     * Returns the number converter to use for converting hex values to/from integers.
     *
     * @return NumberConverterInterface
     */
    public function getNumberConverter();

    /**
     * Returns the hexadecimal value of the UUID.
     *
     * @return string
     */
    public function getHex();

    /**
     * Returns an array of the fields of this UUID, with keys named according
     * to the RFC 4122 names for the fields.
     *
     * * **time_low**: The low 