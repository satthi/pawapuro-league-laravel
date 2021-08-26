<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class ResultOut extends Enum
{
    const OUT_0 = 0;
    const OUT_1 = 1;
    const OUT_2 = 2;
    const OUT_3 = 3;

    /**
     * Get the description for an enum value
     *
     * @param $value
     * @return string
     */
    public static function getDescription($value): string
    {
        return $value . 'アウト';
    }
}
