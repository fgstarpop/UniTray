<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static NoActive()
 * @method static static Active()
 */
final class StoryType extends Enum
{
    const WRITTEN = 0;
    const EMBED = 1;
}
