<?php declare(strict_types=1);

namespace App\Domain\Validator;

use App\Contract\Dto\Game;
use App\Contract\Exception\GameException;
use App\Contract\Exception\InvalidScoreException;

class GameSameScoreValidator implements Validator
{
    private string $message;
    private int $startingScoresForTeams;

    public function __construct(int $startingScoreForTeams = 0, string $message = "Game can't be started because of score should be starting from %d:%d")
    {
        $this->message = $message;
        $this->startingScoresForTeams = $startingScoreForTeams;
    }

    /**
     * @throws GameException
     */
    public function validate(Game $game): void
    {
        if ($game->awayTeam()->score() != $this->startingScoresForTeams && $game->homeTeam()->score() != $this->startingScoresForTeams) {
            throw new InvalidScoreException(sprintf($this->message, $this->startingScoresForTeams, $this->startingScoresForTeams));
        }
    }
}