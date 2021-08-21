<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class PlayerPosition extends Enum
{
    const PITHCER = 1;
    const CATCHER = 2;
    const INFILDER = 3;
    const OUTFILDER = 4;

    /**
     * Get the description for an enum value
     *
     * @param $value
     * @return string
     */
    public static function getDescription($value): string
    {
        switch ($value){
            case self::PITHCER:
                return '投手';
            case self::CATCHER:
                return '捕手';
            case self::INFILDER:
                return '内野手';
            case self::OUTFILDER:
                return '外野手';
            default:
                return self::getKey($value);
        }
    }
}
