<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static Block()
 * @method static static Active()
 */
final class UserState extends Enum
{
    const Block = 1;
    const Active = 0;
    const Admin = ['mod'];

}
