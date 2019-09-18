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

use Ramsey\Uuid\Exception\UnsatisfiedDependencyException;
use Ramsey\Uuid\Exception\UnsupportedOperationException;

/**
 * DegradedUuid represents an RFC 4122 UUID on 32-bit systems
 *
 * @see Uuid
 */
class DegradedUuid extends Uuid
{
    /**
     * @inheritdoc
     */
    public function getDateTime()
    {
        if ($this->getVersion() != 1) {
            throw new UnsupportedOperationException('Not a time-based UUID');
        }

        $time = $this->converter->fromHex($this->getTimestampHex());

        $ts = new \Moontoast\Math\BigNumber($time, 20);
        $ts->subtract('122192928000000000');
        $ts->divide('10000000.0');
        $ts->round();
        $unixTime = $ts->getValue();

        return new \DateTime("@{$unixTime}");
    }

    /**
     * For degraded UUIDs, throws an `UnsatisfiedDependencyException` when
     * called on a 32-bit system
     *
     * @throws UnsatisfiedDependencyException if called on a 32-bit system
     */
    public function getFields()
    {
        throw new UnsatisfiedDependen