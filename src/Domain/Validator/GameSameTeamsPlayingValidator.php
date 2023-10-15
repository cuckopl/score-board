<?php declare(strict_types=1);

namespace App\Domain\Validator;

use App\Contract\Dto\Game;
use App\Contract\Exception\GameException;

class GameSameTeamsPlayingValidator implements Validator
{
    private string $message;

    public function __construct(string $message = "Teams can't play versus each other")
    {
        $this->message = $message;
    }

    /**
     * @throws GameException
     */
    public function validate(Game $game): void
    {
        if ($game->awayTeam()->teamName() == $game->homeTeam()->teamName()) {
            throw new GameException($this->message);
        }
    }
}