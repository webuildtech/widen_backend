<?php

namespace App\Enums;

enum AvailabilitySlotType: string
{
    case SPOT = 'spot';

    case SEASON = 'season';

    case ACADEMY = 'academy';

    case TOURNAMENT = 'tournament';

    case GAME = 'game';
}
