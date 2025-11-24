<?php

declare(strict_types=1);

namespace App\Enums;

enum TournamentTypes: string
{
    case RoundRobin = 'round_robin';
    case SingleElimination = 'single_elimination';
    case DoubleElimination = 'double_elimination';
    case League = 'league';
}
