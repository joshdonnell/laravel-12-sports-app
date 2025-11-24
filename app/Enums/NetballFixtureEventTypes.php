<?php

declare(strict_types=1);

namespace App\Enums;

enum NetballFixtureEventTypes: string
{
    case GameManagement = 'game-management';
    case Goal = 'goal';
    case Penalty = 'penalty';
    case CentrePassReceived = 'centre-pass-received';
    case Feed = 'feed';
    case UnforcedError = 'unforced-error';
    case SuperShot = 'super-shot';
    case Substitution = 'substitution';
    case Deflection = 'deflection';
    case Pickup = 'pickup';
    case Rebound = 'rebound';
    case Interception = 'interception';
    case Gain = 'gain';
    case Miss = 'miss';
}
