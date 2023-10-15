<?php declare(strict_types=1);

namespace App\Domain\Validator;

use App\Contract\Dto\Game;
use App\Contract\Dto\GameStatus;
use App\Contract\Exception\GameException;
use App\Domain\Repository\ScoreBoardStorage;

class ValidatorPipeline
{
    /**
     * @throws GameException
     */
    public function validateStartGame(Game $game, ScoreBoardStorage $scoreBoardStorage): void
    {
        foreach (self::startGameValidators($scoreBoardStorage) as $singleValidator) {
            $singleValidator->validate($game);
        }
    }

    public function validateUpdateGame(Game $game, ScoreBoardStorage $scoreBoardStorage): void
    {
        foreach (self::updateGameValidators($scoreBoardStorage) as $singleValidator) {
            $singleValidator->validate($game);
        }
    }
    
    public function validateDeleteGame(Game $game): void
    {
        foreach (self::deleteGameValidators() as $singleValidator) {
            $singleValidator->validate($game);
        }
    }

    private static function deleteGameValidators(): array
    {
        return [
            new GameStatusValidator(GameStatus::ON_GOING, "We can't update game with other status than %s"),
        ];
    }

    private static function updateGameValidators(ScoreBoardStorage $scoreBoardStorage): array
    {

        return [
            new GameStatusValidator(GameStatus::ON_GOING, "We can't update game with other status than %s"),
            new GameScoreToHighValidator($scoreBoardStorage),
            new GameScoreToLowValidator($scoreBoardStorage)
        ];


    }

    private static function startGameValidators(ScoreBoardStorage $scoreBoardStorage): array
    {
        return [
            new GameSameTeamsPlayingValidator(),
            new GameSameScoreValidator(),
            new GameStatusValidator(),
            new GameAlreadyStartedValidator($scoreBoardStorage),
            new GameSingleTeamAlreadyInGame($scoreBoardStorage)
        ];
    }
}