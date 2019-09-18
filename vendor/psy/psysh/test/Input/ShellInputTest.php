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
use Ramsey\Uuid\Codec\CodecInterface;
use Ramsey\Uuid\Exception\UnsupportedOperationException;

/**
 * Represents a universally unique identifier (UUID), according to RFC 4122.
 *
 * This class provides immutable UUID objects (the Uuid class) and the static
 * methods `uuid1()`, `uuid3()`, `uuid4()`, and `uuid5()` for generating version
 * 1, 3, 4, and 5 UUIDs as specified in RFC 4122.
 *
 * If all you want is a unique ID, you should probably call `uuid1()` or `uuid4()`.
 * Note that `uuid1()` may compromise privacy since it creates a UUID containing
 * the computer’s network address. `uuid4()` creates a random UUID.
 *
 * @link http://tools.ietf.org/html/rfc4122
 * @link http://en.wikipedia.org/wiki/Universally_unique_identifier
 * @link http://docs.python.org/3/library/uuid.html
 * @link http://docs.oracle.com/javase/6/docs/api/java/util/UUID.html
 */
class Uuid implements UuidInterface
{
    /**
     * When this namespace is specified, the name string is a fully-qualified domain name.
     * @link http://tools.ietf.org/html/rfc4122#appendix-C
     */
    const NAMESPACE_DNS = '6ba7b810-9dad-11d1-80b4-00c04fd430c8';

    /**
     * When this namespace is specified, the name string is a URL.
     * @link http://tools.ietf.org/html/rfc4122#appendix-C
     */
    const NAMESPACE_URL = '6ba7b811-9dad-11d1-80b4-00c04fd430c8';

    /**
     * When this namespace is specified, the name string is an ISO OID.
     * @link http://tools.ietf.org/html/rfc4122#appendix-C
     */
    const NAMESPACE_OID = '6ba7b812-9dad-11d1-80b4-00c04fd430c8';

    /**
     * When this namespace is specified, the name string is an X.500 DN in DER or a text output format.
     * @link http://tools.ietf.org/html/rfc4122#appendix-C
     */
    const NAMESPACE_X500 = '6ba7b814-9dad-11d1-80b4-00c04fd430c8';

    /**
     * The nil UUID is special form of UUID that is specified to have all 128 bits set to zero.
     * @link http://tools.ietf.org/html/rfc4122#section-4.1.7
     */
    const NIL = '00000000-0000-0000-0000-000000000000';

    /**
     * Reserved for NCS compatibility.
     * @link http://tools.ietf.org/html/rfc4122#section-4.1.1
     */
    const RESERVED_NCS = 0;

    /**
     * Specifies the UUID layout given in RFC 4122.
     * @link http://tools.ietf.org/html/rfc4122#section-4.1.1
     */
    const RFC_4122 = 2;

    /**
     * Reserved for Microsoft compatibility.
     * @link http://tools.ietf.org/html/rfc4122#section-4.1.1
     */
    const RESERVED_MICROSOFT = 6;

    /**
     * Reserved for future definition.
     * @link http://tools.ietf.org/html/rfc4122#section-4.1.1
     */
    const RESERVED_FUTURE = 7;

    /**
     * Regular expression pattern for matching a valid UUID of any variant.
     */
    const VALID_PATTERN = '^[0-9A-Fa-f]{8}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{12}$';

    /**
     * Version 1 (time-based) UUID object constant identifier
     */
    const UUID_TYPE_TIME = 1;

    /**
     * Version 2 (identifier-based) UUID object constant identifier
     */
    const UUID_TYPE_IDENTIFIER = 2;

    /**
     * Version 3 (name-based and hashed with MD5) UUID object constant identifier
     */
    const UUID_TYPE_HASH_MD5 = 3;

    /**
     * Version 4 (random) UUID object constant identifier
     */
    const UUID_TYPE_RANDOM = 4;

    /**
     * Version 5 (name-based and hashed with SHA1) UUID object constant identifier
     */
    const UUID_TYPE_HASH_SHA1 = 5;

    /**
     * The factory to use when creating UUIDs.
     * @var UuidFactoryInterface
     */
    private static $factory = null;

    /**
     * The codec to use when encoding or decoding UUID strings.
     * @var CodecInterface
     */
    protected $codec;

    /**
     * The fields that make up this UUID.
     *
     * This is initialized to the nil value.
     *
     * @var array
     * @see UuidInterface::getFieldsHex()
     */
    protected $fields = array(
        'time_low' => '00000000',
        'time_mid' => '0000',
        'time_hi_and_version' => '0000',
        'clock_seq_hi_and_reserved' => '00',
        'clock_seq_low' => '00',
        'node' => '000000000000',
    );

    /**
     * The number converter to use for converting hex values to/from integers.
     * @var NumberConverterInterface
     */
    protected $converter;

    /**
     * Creates a universally unique identifier (UUID) from an array of fields.
     *
     * Unless you're making advanced use of this library to generate identifiers
     * that deviate from RFC 4122, you probably do not want to instantiate a
     * UUID directly. Use the static methods, instead:
     *
     * ```
     * use Ramsey\Uuid\Uuid;
     *
     * $timeBasedUuid     = Uuid::uuid1();
     * $namespaceMd5Uuid  = Uuid::uuid3(Uuid::NAMESPACE_URL, 'http://php.net/');
     * $randomUuid        = Uuid::uuid4();
     * $namespaceSha1Uuid = Uuid::uuid5(Uuid::NAMESPACE_URL, 'http://php.net/');
     * ```
     *
     * @param array $fields An array of fields from which to construct a UUID;
     *     see {@see \Ramsey\Uuid\UuidInterface::getFieldsHex()} for array structure.
     * @param NumberConverterInterface $converter The number converter to use
     *     for converting hex values to/from integers.
     * @param CodecInterface $codec The codec to use when encoding or decoding
     *     UUID strings.
     */
    public function __construct(
        array $fields,
        NumberConverterInterface $converter,
        CodecInterface $codec
    ) {
        $this->fields = $fields;
        $this->codec = $codec;
        $this->converter = $converter;
    }

    /**
     * Converts this UUID object to a string when the object is used in any
     * string context.
     *
     * @return string
     * @link http://www.php.net/manual/en/language.oop5.magic.php#object.tostring
     */
    public function __toString()
    {
        return $this->toString();
    }

    /**
     * Converts this UUID object to a string when the object is serialized
     * with `json_encode()`
     *
     * @return string
     * @link http://php.net/manual/en/class.jsonserializable.php
     */
    public function jsonSerialize()
    {
        return $this->toString();
    }

    /**
     * Converts this UUID object to a string when the object is serialized
     * with `serialize()`
     *
     * @return string
     * @link http://php.net/manual/en/class.serializable.php
     */
    public function serialize()
    {
        return $this->toString();
    }

    /**
     * Re-constructs the object from its serialized form.
     *
     * @param string $serialized
     * @link http://php.net/manual/en/class.serializable.php
     * @throws \Ramsey\Uuid\Exception\InvalidUuidStringException
     */
    public function unserialize($serialized)
    {
        $uuid = self::fromString($serialized);
        $this->codec = $uuid->codec;
        $this->converter = $uuid->converter;
        $this->fields = $uuid->fields;
    }

    public function compareTo(UuidInterface $other)
    {
        $comparison = 0;

        if ($this->getMostSignificantBitsHex() < $other->getMostSignificantBitsHex()) {
            $comparison = -1;
        } elseif ($this->getMostSignificantBitsHex() > $other->getMostSignificantBitsHex()) {
            $comparison = 1;
        } elseif ($this->getLeastSignificantBitsHex() < $other->getLeastSignificantBitsHex()) {
            $comparison = -1;
        } elseif ($this->getLeastSignificantBitsHex() > $other->getLeastSignificantBitsHex()) {
            $comparison = 1;
        }

        return $comparison;
    }

    public function equals($other)
    {
        if (!($other instanceof UuidInterface)) {
            return false;
        }

        return ($this->compareTo($other) == 0);
    }

    public function getBytes()
    {
        return $this->codec->encodeBinary($this);
    }

    /**
     * Returns the high field of the clock sequence multiplexed with the variant
     * (bits 65-72 of the UUID).
     *
     * @return int Unsigned 8-bit integer value of clock_seq_hi_and_reserved
     */
    public function getClockSeqHiAndReserved()
    {
        return hexdec($this->getClockSeqHiAndReservedHex());
    }

    public function getClockSeqHiAndReservedHex()
    {
        return $this->fields['clock_seq_hi_and_reserved'];
    }

    /**
     * Returns the low field of the clock sequence (bits 73-80 of the UUID).
 