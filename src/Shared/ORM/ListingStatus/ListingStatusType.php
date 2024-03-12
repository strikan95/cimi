<?php

namespace App\Shared\ORM\ListingStatus;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

class ListingStatusType extends Type
{
    const ENUM_STATUS = 'listing_status_enum';
    const STATUS_DRAFT = 'draft';
    const STATUS_PENDING = 'pending';
    const STATUS_APPROVED = 'approved';

    public function getSQLDeclaration(
        array $column,
        AbstractPlatform $platform,
    ): string {
        $decl = sprintf(
            "ENUM('%s', '%s', '%s')",
            self::STATUS_DRAFT,
            self::STATUS_PENDING,
            self::STATUS_APPROVED,
        );

        return $decl;
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): mixed
    {
        return $value;
    }

    public function convertToDatabaseValue(
        $value,
        AbstractPlatform $platform,
    ): mixed {
        if (
            !in_array($value, [
                self::STATUS_DRAFT,
                self::STATUS_PENDING,
                self::STATUS_APPROVED,
            ])
        ) {
            throw new \InvalidArgumentException('Invalid status');
        }
        return $value;
    }

    public function getName(): string
    {
        return self::ENUM_STATUS;
    }

    public function requiresSQLCommentHint(AbstractPlatform $platform): bool
    {
        return true;
    }
}
