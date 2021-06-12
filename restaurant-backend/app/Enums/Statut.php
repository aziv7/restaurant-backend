<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class Statut extends Enum
{
    const non_vue=0;
    const en_cours =   1;
    const pret = 2;
    const  traite=3;
    const annulee=4;
}
