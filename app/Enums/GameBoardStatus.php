<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class GameBoardStatus extends Enum
{
    const STATUS_START = 1;
    const STATUS_GAME = 2;
    const STATUS_INNING_END = 3;
    const STATUS_GAMEEND = 4;
    const STATUS_GAMEENDED = 5;

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
