<?php declare(strict_types=1);

namespace App\Domain\Validator;

use App\Contract\Dto\Game;
use App\Contract\Exception\GameException;
use App\Contract\Exception\GameExistsException;
use App\Domain\Repository\ScoreBoardStorage;

class GameSingleTeamAlreadyInGame implements Validator
{
    private string $message;
    private ScoreBoardStorage $scoreBoardStorage;

    public function __construct($scoreBoardStorage, string $message = "One of the teams in match is already playing game.")
    {
        $this->message = $message;
        $this->scoreBoardStorage = $scoreBoardStorage;
    }

    /**
     * @throws GameException
     */
    public function validate(Game $game): void
    {
        if ($this->scoreBoardStorage->isSingleTeamInGame($game)) {
            throw new GameExistsException($this->message);
        }
    }
}