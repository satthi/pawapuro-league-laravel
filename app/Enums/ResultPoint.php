<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class ResultPoint extends Enum
{
    const POINT_0 = 0;
    const POINT_1 = 1;
    const POINT_2 = 2;
    const POINT_3 = 3;
    const POINT_4 = 4;

    /**
     * Get the description for an enum value
     *
     * @param $value
     * @return string
     */
    public static function getDescription($value): string
    {
        return $value . 'ポイント';
    }
}
