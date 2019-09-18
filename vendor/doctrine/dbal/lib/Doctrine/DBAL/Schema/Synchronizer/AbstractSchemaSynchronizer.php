<?php

namespace Doctrine\DBAL\Types;

use DateTimeImmutable;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use function date_create_immutable;

/**
 * Immutable type of {@see VarDateTimeType}.
 */
class VarDateTimeImmutableType extends VarDateTimeType
{
    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return Type::DATETIME_IMMUTABLE;
    }

    /**
     * {@inheritdoc}
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        if ($value === null) {
            return $value;
        }

        if ($value instanceof DateTimeImmutable) {
            return $value->format($platform->getDateTimeFormatString());
        }

        throw ConversionException::conversionFailedInvalidType(
            $value,
            $this->getName(),
            ['null'