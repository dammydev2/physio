<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\VarDumper\Caster;

use Symfony\Component\VarDumper\Cloner\Stub;

/**
 * Casts DateTimeInterface related classes to array representation.
 *
 * @author Dany Maillard <danymaillard93b@gmail.com>
 */
class DateCaster
{
    private const PERIOD_LIMIT = 3;

    public static function castDateTime(\DateTimeInterface $d, array $a, Stub $stub, $isNested, $filter)
    {
        $prefix = Caster::PREFIX_VIRTUAL;
        $location = $d->getTimezone()->getLocation();
        $fromNow = (new \DateTime())->diff($d);

        $title = $d->format('l, F j, Y')
            ."\n".self::formatInterval($fromNow).' from now'
            .($location ? ($d->format('I') ? "\nDST On" : "\nDST Off") : '')
        ;

        $a = [];
        $a[$prefix.'date'] = new ConstStub(self::formatDateTime($d, $location ? ' e (P)' : ' P'), $title);

        $stub->class .= $d->format(' @U');

        return $a;
    }

    public static function castInterval(\DateInterval $interval, array $a, Stub $stub, $isNested, $filter)
    {
        $now = new \DateTi