<?php

declare(strict_types=1);

namespace App\Enums;

enum FixtureStatus: string
{
    case Scheduled = 'scheduled';
    case InProgress = 'in-progress';
    case Pending = 'pending';
    case Completed = 'completed';
    case Cancelled = 'cancelled';
    case Postponed = 'postponed';
}
