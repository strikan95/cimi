<?php

namespace App\Shared\ORM\Spatial\DoctrineType;

use App\Shared\ORM\Spatial\PhpObject\Point;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

class GeometryType extends Type
{
    const GEOMETRY = 'bla';

    public function getSQLDeclaration(
        array $column,
        AbstractPlatform $platform,
    ): string {
        return 'GEOMETRY';
    }

    public function getName(): string
    {
        return 'bla';
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): Point
    {
        [$longitude, $latitude] = sscanf($value, 'POINT(%f %f)');

        return new Point($latitude, $longitude);
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        if (!($value instanceof Point)) {
            throw new \Exception('value is not of type Point');
        }

        return sprintf(
            'POINT(%f %f)',
            $value->getLatitude(),
            $value->getLongitude(),
        );
    }

    public function canRequireSQLConversion(): bool
    {
        return true;
    }

    public function convertToDatabaseValueSQL(
        $sqlExpr,
        AbstractPlatform $platform,
    ): string {
        return sprintf('ST_GeomFromText(%s, 4326)', $sqlExpr);
    }

    public function convertToPHPValueSQL($sqlExpr, $platform): string
    {
        return sprintf('ST_AsWKT(%s)', $sqlExpr);
    }
}
