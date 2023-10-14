<?php

namespace App\Domain\Mapper;

use App\Contract\Dto\Game;

class GameMapper
{
    public static function toOngoingGame(Game $game): Game
    {
        return Game::createOngoingGame($game->homeTeam(), $game->awayTeam());
    }

    public static function toFinishedGame(Game $game): Game
    {
        return Game::createFinished($game->homeTeam(), $game->awayTeam());
    }
}