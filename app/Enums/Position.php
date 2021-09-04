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
    const POSITION_PH = 21;
    const POSITION_PR = 22;

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
            case self::POSITION_PH:
                return '打';
            case self::POSITION_PR:
                return '走';
            default:
                return self::getKey($value);
        }
    }

    /**
     * Get the description for an enum value
     *
     * @param $value
     * @return string
     */
    public static function getTextFull($value): string
    {
        switch ($value){
            case self::POSITION_P:
                return '投手';
            case self::POSITION_C:
                return '捕手';
            case self::POSITION_1B:
                return '一塁手';
            case self::POSITION_2B:
                return '二塁手';
            case self::POSITION_3B:
                return '三塁手';
            case self::POSITION_SS:
                return '遊撃手';
            case self::POSITION_LF:
                return '左翼手';
            case self::POSITION_CF:
                return '中堅手';
            case self::POSITION_RF:
                return '右翼手';
            case self::POSITION_DH:
                return '指名打者';
            default:
                return self::getKey($value);
        }
    }

    /**
     * Get the description for an enum value
     *
     * @param $value
     * @return string
     */
    public static function getNumberStamen($value): string
    {
        switch ($value){
            case self::POSITION_P:
                return '①';
            case self::POSITION_C:
                return '②';
            case self::POSITION_1B:
                return '③';
            case self::POSITION_2B:
                return '④';
            case self::POSITION_3B:
                return '⑤';
            case self::POSITION_SS:
                return '⑥';
            case self::POSITION_LF:
                return '⑦';
            case self::POSITION_CF:
                return '⑧';
            case self::POSITION_RF:
                return '⑨';
            case self::POSITION_DH:
                return 'D';
            default:
                return self::getKey($value);
        }
    }

    /**
     * Get the description for an enum value
     *
     * @param $value
     * @return string
     */
    public static function getNumberChange($value): string
    {
        switch ($value){
            case self::POSITION_P:
                return '1';
            case self::POSITION_C:
                return '2';
            case self::POSITION_1B:
                return '3';
            case self::POSITION_2B:
                return '4';
            case self::POSITION_3B:
                return '5';
            case self::POSITION_SS:
                return '6';
            case self::POSITION_LF:
                return '7';
            case self::POSITION_CF:
                return '8';
            case self::POSITION_RF:
                return '9';
            case self::POSITION_DH:
                return 'D';
            case self::POSITION_PH:
                return 'H';
            case self::POSITION_PR:
                return 'R';
            default:
                return self::getKey($value);
        }
    }
}
