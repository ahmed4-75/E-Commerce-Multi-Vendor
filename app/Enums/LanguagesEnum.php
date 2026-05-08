<?php

namespace App\Enums;

/**
 * @OA\Schema(
 *     schema="LanguagesEnum",
 *     type="string",
 *     enum={"ar", "en", "sp", "ur"}
 * )
 */
enum LanguagesEnum: string
{
    case Ar = 'ar';
    case En = 'en';
    case Sp = 'sp';
    case Ur = 'ur';

    public static function values(): array
    {
        return array_column(self::cases(),'value');
    }
}
