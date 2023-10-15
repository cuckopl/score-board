<?php declare(strict_types=1);

namespace App\Domain\Validator;

use App\Contract\Dto\Game;
use App\Contract\Dto\GameStatus;
use App\Contract\Exception\GameException;

class GameStatusValidator implements Validator
{
    private string $message;
    private GameStatus $gameStatus;

    public function __construct(GameStatus $gameStatus = GameStatus::NOT_STARTED, string $message = "We can't create game with status other than %s")
    {
        $this->message = $message;
        $this->gameStatus = $gameStatus;
    }

    /**
     * @throws GameException
     */
    public function validate(Game $game): void
    {
        if ($game->gameStatus() != $this->gameStatus) {
            throw new GameException(sprintf($this->message, $this->gameStatus->name));
        }
    }
}