<?php

namespace App\Enum;

enum SaisonsEnum: int
{
    case HIVER = 1;
    case PRINTEMPS = 2;
    case ETE = 3;
    case AUTOMNE = 4;

    public static function getId(string $nom): ?int
    {
        $nom = strtoupper($nom);

        $saison = self::tryFrom($nom);

        return $saison?->value;
    }
}
