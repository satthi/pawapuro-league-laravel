<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class PlayType extends Enum
{
    const TYPE_STAMEN = 1;
    const TYPE_MEMBER_CHANGE = 2;
    const TYPE_DAGEKI_KEKKA = 3;
    const TYPE_STEAL = 4;
    const TYPE_POINT_ONLY = 5;
}
