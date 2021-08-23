<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class Days extends Enum
{
    const monday    = 0;
    const tuesday   = 1;
    const wednesday = 2;
    const thursday  = 3;
    const friday    = 4;
    const saturday  = 5;
    const sunday    = 6;
}
