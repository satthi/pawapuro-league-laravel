<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class Position extends Enum
{
    const POSITION_P = 1;
    const POSITION_C = 2;
    const POSITION_1B = 3;
    const POSITION_2B = 4;
    const POSITION_3B = 5;
    const POSITION_SS = 6;
    const POSITION_LF = 7;
    const POSITION_CF = 8;
    const POSITION_RF = 9;
    const POSITION_DH = 10;

    /**
     * Get the description for an enum value
     *
     * @param $value
     * @return string
     */
    public static function getDescription($value): string
    {
        switch ($value){
            case self::POSITION_P:
                return '投';
            case self::POSITION_C:
                return '捕';
            case self::POSITION_1B:
                return '一';
            case self::POSITION_2B:
                return '二';
            case self::POSITION_3B:
                return '三';
            case self::POSITION_SS:
                return '遊';
            case self::POSITION_LF:
                return '左';
            case self::POSITION_CF:
                return '中';
            case self::POSITION_RF:
                return '右';
            case self::POSITION_DH:
                return 'DH';
            default:
                return self::getKey($value);
        }
    }
}
