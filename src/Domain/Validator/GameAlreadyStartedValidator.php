<?php declare(strict_types=1);

namespace App\Domain\Validator;

use App\Contract\Dto\Game;
use App\Contract\Exception\GameException;
use App\Contract\Exception\GameExistsException;
use App\Domain\Mapper\GameMapper;
use App\Domain\Repository\ScoreBoardStorage;

class GameAlreadyStartedValidator implements Validator
{
    private string $message;
    private ScoreBoardStorage $scoreBoardStorage;

    public function __construct($scoreBoardStorage, string $message = "Game is already started")
    {
        $this->message = $message;
        $this->scoreBoardStorage = $scoreBoardStorage;
    }

    /**
     * @throws GameException
     */
    public function validate(Game $game): void
    {
        if ($this->scoreBoardStorage->get($game) != null && $this->scoreBoardStorage->get(GameMapper::swapTeams($game)) != null) {
            throw new GameExistsException($this->message);
        }
    }
}