<?php declare(strict_types=1);

namespace App\Domain\Validator;

use App\Contract\Dto\Game;
use App\Contract\Exception\GameException;
use App\Contract\Exception\InvalidScoreException;
use App\Domain\Repository\ScoreBoardStorage;

class GameScoreToHighValidator implements Validator
{
    private string $message;
    private int $maxScoreIncrement;

    private ScoreBoardStorage $scoreBoardStorage;

    public function __construct($scoreBoardStorage, int $maxScoreIncrement = 1, string $message = "Score can be incremented only +1 per execution(we can't score 2 point in football)")
    {
        $this->message = $message;
        $this->scoreBoardStorage = $scoreBoardStorage;
        $this->maxScoreIncrement = $maxScoreIncrement;
    }

    /**
     * @throws GameException
     */
    public function validate(Game $game): void
    {
        $previousGame = $this->scoreBoardStorage->get($game);
        if (
            $previousGame->awayTeam()->score() + $this->maxScoreIncrement < $game->awayTeam()->score() ||
            $previousGame->homeTeam()->score() + $this->maxScoreIncrement < $game->homeTeam()->score()
        ) {
            throw new InvalidScoreException($this->message);
        }
    }
}