<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class GameBoardStatus extends Enum
{
    const STATUS_STAMEN_SETTING = 1;
    const STATUS_START = 2;
    const STATUS_GAME = 3;
    const STATUS_INNING_END = 4;
    const STATUS_GAMEEND = 5;
    const STATUS_GAMEENDED = 6;

    /**
     * Get the description for an enum value
     *
     * @param $value
     * @return string
     */
    public static function getDescription($value): string
    {
        return $value;
    }
}
