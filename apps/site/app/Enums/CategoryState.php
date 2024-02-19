<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static NoActive()
 * @method static static Active()
 */
final class CategoryState extends Enum
{
    const NoActive = 0;
    const Active = 1;
}
