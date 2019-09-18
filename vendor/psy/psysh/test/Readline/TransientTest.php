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
use Ramsey\Uuid\Provider\NodeProviderInterface;
use Ramsey\Uuid\Generator\RandomGeneratorInterface;
use Ramsey\Uuid\Generator\TimeGeneratorInterface;
use Ramsey\Uuid\Codec\CodecInterface;
use Ramsey\Uuid\Builder\UuidBuilderInterface;

class UuidFactory implements UuidFactoryInterface
{
    /**
     * @var CodecInterface
     */
    private $codec = null;

    /**
     * @var NodeProviderInterface
     */
    private $nodeProvider = null;

    /**
     * @var NumberConverterInterface
     */
    private $numberConverter = null;

    /**
     * @var RandomGeneratorInterface
     */
    private $randomGenerator = null;

    /**
     * @var TimeGeneratorInterface
     */
    private $timeGenerator = null;

    /**
     * @var UuidBuilderInterface
     */
    private $uuidBuilder = null;

    /**
     * Constructs a `UuidFactory` for creating `Ramsey\Uuid\UuidInterface` instances
     *
     * @param FeatureSet $features A set of features for use when creating UUIDs
     */
    public function __construct(FeatureSet $features = null)
    {
        $features = $features ?: new FeatureSet();

        $this->codec = $features->getCodec();
        $this->nodeProvider = $features->getNodeProvider();
        $this->numberConverter = $features->getNumberConverter();
        $this->randomGenerator = $features->getRandomGenerator();
        $this->timeGenerator = $features->getTimeGenerator();
        $this->uuidBuilder = $features->getBuilder();
    }

    /**
     * Returns the UUID coder-decoder used by this factory
     *
     * @return CodecInterface
     */
    public function getCodec()
    {
        return $this->codec;
    }

    /**
     * Sets the UUID coder-decoder used by this factory
     *
     * @param CodecInterface $codec
     */
    public function setCodec(CodecInterface $codec)
    {
        $this->codec = $codec;
    }

    /**
     * Returns the system node ID