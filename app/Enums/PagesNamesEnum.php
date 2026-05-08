<?php

namespace App\Enums;

/**
 * @OA\Schema(
 *     schema="PagesNamesEnum",
 *     type="string",
 *     enum={"page_a", "page_b", "page_c", "page_d", "page_f"}
 * )
 */
enum PagesNamesEnum: string
{
    case PageA = 'page_a';
    case PageB = 'page_b';
    case PageC = 'page_c';
    case PageD = 'page_d';
    case PageF = 'page_f';

    public static function values(): array
    {
        return array_column(self::cases(),'value');
    }
}