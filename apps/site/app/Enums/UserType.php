<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static Normal()
 * @method static static Mod()
 */
final class UserType extends Enum
{
    const Normal = 0;
    const Mod = 1;
}
