<?php

declare(strict_types=1);

use App\Enums\NetballRuleTypes;

it('has all expected cases', function (): void {
    expect(NetballRuleTypes::cases())->toHaveCount(23);
    expect(NetballRuleTypes::PointsForWin)->toBeInstanceOf(NetballRuleTypes::class);
    expect(NetballRuleTypes::PointsForDraw)->toBeInstanceOf(NetballRuleTypes::class);
    expect(NetballRuleTypes::PointsForLoss)->toBeInstanceOf(NetballRuleTypes::class);
    expect(NetballRuleTypes::PointsForForfeit)->toBeInstanceOf(NetballRuleTypes::class);
    expect(NetballRuleTypes::PointsForBye)->toBeInstanceOf(NetballRuleTypes::class);
    expect(NetballRuleTypes::BonusPointsWinMargin)->toBeInstanceOf(NetballRuleTypes::class);
    expect(NetballRuleTypes::BonusPointsWinMarginThreshold)->toBeInstanceOf(NetballRuleTypes::class);
    expect(NetballRuleTypes::BonusPointsWinMarginTier2)->toBeInstanceOf(NetballRuleTypes::class);
    expect(NetballRuleTypes::BonusPointsWinMarginThreshold2)->toBeInstanceOf(NetballRuleTypes::class);
    expect(NetballRuleTypes::BonusPointsGoalTotal)->toBeInstanceOf(NetballRuleTypes::class);
    expect(NetballRuleTypes::BonusPointsGoalTotalThreshold)->toBeInstanceOf(NetballRuleTypes::class);
    expect(NetballRuleTypes::BonusPointsLosingMargin)->toBeInstanceOf(NetballRuleTypes::class);
    expect(NetballRuleTypes::BonusPointsLosingMarginThreshold)->toBeInstanceOf(NetballRuleTypes::class);
    expect(NetballRuleTypes::BonusPointsWinAllQuarters)->toBeInstanceOf(NetballRuleTypes::class);
    expect(NetballRuleTypes::BonusPointsComebackWin)->toBeInstanceOf(NetballRuleTypes::class);
    expect(NetballRuleTypes::TiebreakerPrimary)->toBeInstanceOf(NetballRuleTypes::class);
    expect(NetballRuleTypes::TiebreakerSecondary)->toBeInstanceOf(NetballRuleTypes::class);
    expect(NetballRuleTypes::TiebreakerTertiary)->toBeInstanceOf(NetballRuleTypes::class);
    expect(NetballRuleTypes::TiebreakerQuaternary)->toBeInstanceOf(NetballRuleTypes::class);
    expect(NetballRuleTypes::NumberOfQuarters)->toBeInstanceOf(NetballRuleTypes::class);
    expect(NetballRuleTypes::QuarterLength)->toBeInstanceOf(NetballRuleTypes::class);
    expect(NetballRuleTypes::ExtraTimeEnabled)->toBeInstanceOf(NetballRuleTypes::class);
    expect(NetballRuleTypes::ExtraTimeLength)->toBeInstanceOf(NetballRuleTypes::class);
});

it('has correct string values', function (): void {
    expect(NetballRuleTypes::PointsForWin->value)->toBe('points-for-win');
    expect(NetballRuleTypes::PointsForDraw->value)->toBe('points-for-draw');
    expect(NetballRuleTypes::PointsForLoss->value)->toBe('points-for-loss');
    expect(NetballRuleTypes::PointsForForfeit->value)->toBe('points-for-forfeit');
    expect(NetballRuleTypes::PointsForBye->value)->toBe('points-for-bye');
    expect(NetballRuleTypes::BonusPointsWinMargin->value)->toBe('bonus-points-win-margin');
    expect(NetballRuleTypes::BonusPointsWinMarginThreshold->value)->toBe('bonus-points-win-margin-threshold');
    expect(NetballRuleTypes::BonusPointsWinMarginTier2->value)->toBe('bonus-points-win-margin-tier2');
    expect(NetballRuleTypes::BonusPointsWinMarginThreshold2->value)->toBe('bonus-points-win-margin-threshold2');
    expect(NetballRuleTypes::BonusPointsGoalTotal->value)->toBe('bonus-points-goal-total');
    expect(NetballRuleTypes::BonusPointsGoalTotalThreshold->value)->toBe('bonus-points-goal-total-threshold');
    expect(NetballRuleTypes::BonusPointsLosingMargin->value)->toBe('bonus-points-losing-margin');
    expect(NetballRuleTypes::BonusPointsLosingMarginThreshold->value)->toBe('bonus-points-losing-margin-threshold');
    expect(NetballRuleTypes::BonusPointsWinAllQuarters->value)->toBe('bonus-points-win-all-quarters');
    expect(NetballRuleTypes::BonusPointsComebackWin->value)->toBe('bonus-points-comeback-win');
    expect(NetballRuleTypes::TiebreakerPrimary->value)->toBe('tiebreaker-primary');
    expect(NetballRuleTypes::TiebreakerSecondary->value)->toBe('tiebreaker-secondary');
    expect(NetballRuleTypes::TiebreakerTertiary->value)->toBe('tiebreaker-tertiary');
    expect(NetballRuleTypes::TiebreakerQuaternary->value)->toBe('tiebreaker-quaternary');
    expect(NetballRuleTypes::NumberOfQuarters->value)->toBe('number-of-quarters');
    expect(NetballRuleTypes::QuarterLength->value)->toBe('quarter-length');
    expect(NetballRuleTypes::ExtraTimeEnabled->value)->toBe('extra-time-enabled');
    expect(NetballRuleTypes::ExtraTimeLength->value)->toBe('extra-time-length');
});

it('can be created from string value', function (): void {
    expect(NetballRuleTypes::from('points-for-win'))->toBe(NetballRuleTypes::PointsForWin);
    expect(NetballRuleTypes::from('points-for-draw'))->toBe(NetballRuleTypes::PointsForDraw);
    expect(NetballRuleTypes::from('points-for-loss'))->toBe(NetballRuleTypes::PointsForLoss);
    expect(NetballRuleTypes::from('points-for-forfeit'))->toBe(NetballRuleTypes::PointsForForfeit);
    expect(NetballRuleTypes::from('points-for-bye'))->toBe(NetballRuleTypes::PointsForBye);
    expect(NetballRuleTypes::from('bonus-points-win-margin'))->toBe(NetballRuleTypes::BonusPointsWinMargin);
    expect(NetballRuleTypes::from('bonus-points-win-margin-threshold'))->toBe(NetballRuleTypes::BonusPointsWinMarginThreshold);
    expect(NetballRuleTypes::from('bonus-points-win-margin-tier2'))->toBe(NetballRuleTypes::BonusPointsWinMarginTier2);
    expect(NetballRuleTypes::from('bonus-points-win-margin-threshold2'))->toBe(NetballRuleTypes::BonusPointsWinMarginThreshold2);
    expect(NetballRuleTypes::from('bonus-points-goal-total'))->toBe(NetballRuleTypes::BonusPointsGoalTotal);
    expect(NetballRuleTypes::from('bonus-points-goal-total-threshold'))->toBe(NetballRuleTypes::BonusPointsGoalTotalThreshold);
    expect(NetballRuleTypes::from('bonus-points-losing-margin'))->toBe(NetballRuleTypes::BonusPointsLosingMargin);
    expect(NetballRuleTypes::from('bonus-points-losing-margin-threshold'))->toBe(NetballRuleTypes::BonusPointsLosingMarginThreshold);
    expect(NetballRuleTypes::from('bonus-points-win-all-quarters'))->toBe(NetballRuleTypes::BonusPointsWinAllQuarters);
    expect(NetballRuleTypes::from('bonus-points-comeback-win'))->toBe(NetballRuleTypes::BonusPointsComebackWin);
    expect(NetballRuleTypes::from('tiebreaker-primary'))->toBe(NetballRuleTypes::TiebreakerPrimary);
    expect(NetballRuleTypes::from('tiebreaker-secondary'))->toBe(NetballRuleTypes::TiebreakerSecondary);
    expect(NetballRuleTypes::from('tiebreaker-tertiary'))->toBe(NetballRuleTypes::TiebreakerTertiary);
    expect(NetballRuleTypes::from('tiebreaker-quaternary'))->toBe(NetballRuleTypes::TiebreakerQuaternary);
    expect(NetballRuleTypes::from('number-of-quarters'))->toBe(NetballRuleTypes::NumberOfQuarters);
    expect(NetballRuleTypes::from('quarter-length'))->toBe(NetballRuleTypes::QuarterLength);
    expect(NetballRuleTypes::from('extra-time-enabled'))->toBe(NetballRuleTypes::ExtraTimeEnabled);
    expect(NetballRuleTypes::from('extra-time-length'))->toBe(NetballRuleTypes::ExtraTimeLength);
});

it('throws exception when creating from invalid string value', function (): void {
    expect(fn () => NetballRuleTypes::from('Invalid'))->toThrow(ValueError::class);
});

it('returns correct example values from exampleRulesByType method', function (): void {
    expect(NetballRuleTypes::PointsForWin->exampleRulesByType())->toBe(3);
    expect(NetballRuleTypes::PointsForDraw->exampleRulesByType())->toBe(0);
    expect(NetballRuleTypes::PointsForLoss->exampleRulesByType())->toBe(0);
    expect(NetballRuleTypes::PointsForForfeit->exampleRulesByType())->toBe(0);
    expect(NetballRuleTypes::PointsForBye->exampleRulesByType())->toBe(0);
    expect(NetballRuleTypes::BonusPointsWinMargin->exampleRulesByType())->toBe(0);
    expect(NetballRuleTypes::BonusPointsWinMarginThreshold->exampleRulesByType())->toBe(0);
    expect(NetballRuleTypes::BonusPointsWinMarginTier2->exampleRulesByType())->toBe(0);
    expect(NetballRuleTypes::BonusPointsWinMarginThreshold2->exampleRulesByType())->toBe(0);
    expect(NetballRuleTypes::BonusPointsGoalTotal->exampleRulesByType())->toBe(0);
    expect(NetballRuleTypes::BonusPointsGoalTotalThreshold->exampleRulesByType())->toBe(0);
    expect(NetballRuleTypes::BonusPointsLosingMargin->exampleRulesByType())->toBe(1);
    expect(NetballRuleTypes::BonusPointsLosingMarginThreshold->exampleRulesByType())->toBe(5);
    expect(NetballRuleTypes::BonusPointsWinAllQuarters->exampleRulesByType())->toBe(0);
    expect(NetballRuleTypes::BonusPointsComebackWin->exampleRulesByType())->toBe(0);
    expect(NetballRuleTypes::TiebreakerPrimary->exampleRulesByType())->toBe(1);
    expect(NetballRuleTypes::TiebreakerSecondary->exampleRulesByType())->toBe(2);
    expect(NetballRuleTypes::TiebreakerTertiary->exampleRulesByType())->toBe(3);
    expect(NetballRuleTypes::TiebreakerQuaternary->exampleRulesByType())->toBe(4);
    expect(NetballRuleTypes::NumberOfQuarters->exampleRulesByType())->toBe(4);
    expect(NetballRuleTypes::QuarterLength->exampleRulesByType())->toBe(15);
    expect(NetballRuleTypes::ExtraTimeEnabled->exampleRulesByType())->toBe(1);
    expect(NetballRuleTypes::ExtraTimeLength->exampleRulesByType())->toBe(5);
});
