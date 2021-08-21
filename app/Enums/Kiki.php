<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class Kiki extends Enum
{
    const HAND_RIGHT = 1;
    const HAND_LEFT = 2;
    const HAND_RIGHT_LEFT = 3;

    /**
     * Get the description for an enum value
     *
     * @param $value
     * @return string
     */
    public static function getDescription($value): string
    {
        switch ($value){
            case self::HAND_RIGHT:
                return '右';
            case self::HAND_LEFT:
                return '左';
            case self::HAND_RIGHT_LEFT:
                return '両';
            default:
                return self::getKey($value);
        }
    }
}
