<?php declare(strict_types=1);

namespace App\Domain\Validator;

use App\Contract\Dto\Game;
use App\Contract\Exception\GameException;
use App\Contract\Exception\InvalidScoreException;
use App\Domain\Repository\ScoreBoardStorage;

class GameScoreToLowValidator implements Validator
{
    private string $message;
    private ScoreBoardStorage $scoreBoardStorage;

    public function __construct($scoreBoardStorage, string $message = "Score can't be lower than previous one")
    {
        $this->message = $message;
        $this->scoreBoardStorage = $scoreBoardStorage;
    }

    /**
     * @throws GameException
     */
    public function validate(Game $game): void
    {
        $previousGame = $this->scoreBoardStorage->get($game);
        if (
            $previousGame->awayTeam()->score() > $game->awayTeam()->score() ||
            $previousGame->homeTeam()->score() > $game->homeTeam()->score()
        ) {
            throw new InvalidScoreException($this->message);
        }
    }
}