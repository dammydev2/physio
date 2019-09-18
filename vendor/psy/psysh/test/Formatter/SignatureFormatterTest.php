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

use Ramsey\Uuid\Converter\TimeConverterInterface;
use Ramsey\Uuid\Generator\PeclUuidTimeGenerator;
use Ramsey\Uuid\Provider\Node\FallbackNodeProvider;
use Ramsey\Uuid\Provider\Node\RandomNodeProvider;
use Ramsey\Uuid\Provider\Node\SystemNodeProvider;
use Ramsey\Uuid\Converter\NumberConverterInterface;
use Ramsey\Uuid\Converter\Number\BigNumberConverter;
use Ramsey\Uuid\Converter\Number\DegradedNumberConverter;
use Ramsey\Uuid\Converter\Time\BigNumberTimeConverter;
use Ramsey\Uuid\Converter\Time\DegradedTimeConverter;
use Ramsey\Uuid\Converter\Time\PhpTimeConverter;
use Ramsey\Uuid\Provider\Time\SystemTimeProvider;
use Ramsey\Uuid\Builder\UuidBuilderInterface;
use Ramsey\Uuid\Builder\DefaultUuidBuilder;
use Ramsey\Uuid\Codec\CodecInterface;
use Ramsey\Uuid\Codec\StringCodec;
use Ramsey\Uuid\Codec\GuidStringCodec;
use Ramsey\Uuid\Builder\DegradedUuidBuilder;
use Ramsey\Uuid\Generator\RandomGeneratorFactory;
use Ramsey\Uuid\Generator\RandomGeneratorInterface;
use Ramsey\Uuid\Generator\TimeGeneratorFactory;
use Ramsey\Uuid\Generator\TimeGeneratorInterface;
use Ramsey\Uuid\Provider\TimeProviderInterface;
use Ramsey\Uuid\Provider\NodeProviderInterface;

/**
 * FeatureSet detects and exposes available features in the current environment
 * (32- or 64-bit, available dependencies, etc.)
 */
class FeatureSet
{
    /**
     * @var bool
     */
    private $disableBigNumber = false;

    /**
     * @var bool
     */
    private $disable64Bit = false;

    /**
     * @var bool
     */
    private $ignoreSystemNode = false;

    /**
     * @var bool
     */
    private $enablePecl = false;

    /**
     * @var UuidBuilderInterface
     */
    private $builder;

    /**
     * @var CodecInterface
     */
    private $codec;

    /**
     * @var NodeProviderInterface
     */
    private $nodeProvider;

    /**
     * @var NumberConverterInterface
     */
    private $numberConverter;

    /**
     * @var RandomGeneratorInterface
     */
    private $randomGenerator;

    /**
     * @var TimeGeneratorInterface
     */
    private $timeGenerator;

    /**
     * Constructs a `FeatureSet` for use by a `UuidFactory` to determine or set
     * features available to the environment
     *
     * @param bool $useGuids Whether to build UUIDs using the `GuidStringCodec`
     * @param bool $force32Bit Whether to force the use of 32-bit functionality
     *     (primarily for testing purposes)
     * @param bool $forceNoBigNumber Whether to disable the use of moontoast/math
     *     `BigNumber` (primarily for testing purposes)
     * @param bool $ignor