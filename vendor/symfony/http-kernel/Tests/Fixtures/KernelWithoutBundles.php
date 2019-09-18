<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\Mime;

use Egulias\EmailValidator\EmailValidator;
use Egulias\EmailValidator\Validation\RFCValidation;
use Symfony\Component\Mime\Encoder\IdnAddressEncoder;
use Symfony\Component\Mime\Exception\InvalidArgumentException;
use Symfony\Component\Mime\Exception\LogicException;
use Symfony\Component\Mime\Exception\RfcComplianceException;

/**
 * @author Fabien Potencier <fabien@symfony.com>
 *
 * @experimental in 4.3
 */
class Address
{
    private static $validator;
    private static $encoder;

    private $address;

    public function __construct(string $address)
    {
        if (!c