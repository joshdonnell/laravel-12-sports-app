<?php

declare(strict_types=1);

namespace App\Enums;

enum NetballRuleTypes: string
{
    case PointsForWin = 'points-for-win';
    case PointsForDraw = 'points-for-draw';
    case PointsForLoss = 'points-for-loss';
    case PointsForForfeit = 'points-for-forfeit';
    case PointsForBye = 'points-for-bye';

    // Margin-based bonus points
    case BonusPointsWinMargin = 'bonus-points-win-margin';
    case BonusPointsWinMarginThreshold = 'bonus-points-win-margin-threshold';
    case BonusPointsWinMarginTier2 = 'bonus-points-win-margin-tier2';
    case BonusPointsWinMarginThreshold2 = 'bonus-points-win-margin-threshold2';

    // Goal total bonus points
    case BonusPointsGoalTotal = 'bonus-points-goal-total';
    case BonusPointsGoalTotalThreshold = 'bonus-points-goal-total-threshold';

    // Losing bonus points
    case BonusPointsLosingMargin = 'bonus-points-losing-margin';
    case BonusPointsLosingMarginThreshold = 'bonus-points-losing-margin-threshold';

    // Performance-based bonus points
    case BonusPointsWinAllQuarters = 'bonus-points-win-all-quarters';
    case BonusPointsComebackWin = 'bonus-points-comeback-win';

    // Tiebreaker rules (order of priority)
    case TiebreakerPrimary = 'tiebreaker-primary';
    case TiebreakerSecondary = 'tiebreaker-secondary';
    case TiebreakerTertiary = 'tiebreaker-tertiary';
    case TiebreakerQuaternary = 'tiebreaker-quaternary';

    // Match format
    case NumberOfQuarters = 'number-of-quarters';
    case QuarterLength = 'quarter-length';
    case ExtraTimeEnabled = 'extra-time-enabled';
    case ExtraTimeLength = 'extra-time-length';

    public function exampleRulesByType(): int
    {
        return match ($this) {
            NetballRuleTypes::PointsForWin => 3,
            NetballRuleTypes::PointsForDraw => 0,
            NetballRuleTypes::PointsForLoss => 0,
            NetballRuleTypes::PointsForForfeit => 0,
            NetballRuleTypes::PointsForBye => 0,
            NetballRuleTypes::BonusPointsWinMargin => 0,
            NetballRuleTypes::BonusPointsWinMarginThreshold => 0,
            NetballRuleTypes::BonusPointsWinMarginTier2 => 0,
            NetballRuleTypes::BonusPointsWinMarginThreshold2 => 0,
            NetballRuleTypes::BonusPointsGoalTotal => 0,
            NetballRuleTypes::BonusPointsGoalTotalThreshold => 0,
            NetballRuleTypes::BonusPointsLosingMargin => 1,
            NetballRuleTypes::BonusPointsLosingMarginThreshold => 5,
            NetballRuleTypes::BonusPointsWinAllQuarters => 0,
            NetballRuleTypes::BonusPointsComebackWin => 0,
            NetballRuleTypes::TiebreakerPrimary => 1,
            NetballRuleTypes::TiebreakerSecondary => 2,
            NetballRuleTypes::TiebreakerTertiary => 3,
            NetballRuleTypes::TiebreakerQuaternary => 4,
            NetballRuleTypes::NumberOfQuarters => 4,
            NetballRuleTypes::QuarterLength => 15,
            NetballRuleTypes::ExtraTimeEnabled => 1,
            NetballRuleTypes::ExtraTimeLength => 5,
        };
    }
}
