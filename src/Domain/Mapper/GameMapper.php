<?php

namespace App\Domain\Mapper;

use App\Contract\Dto\Game;
use App\Contract\Dto\Team;

class GameMapper
{
    public static function toOngoingGame(Game $game): Game
    {
        return Game::createOngoingGame(
            Team::updateTeam(
                $game->homeTeam()->teamName(),
                $game->homeTeam()->score()
            ),
            Team::updateTeam(
                $game->awayTeam()->teamName(),
                $game->awayTeam()->score()
            ), $game->creationTime()
        );
    }

    public static function swapTeams(Game $game): Game
    {
        return Game::createOngoingGame($game->awayTeam(), $game->homeTeam(), $game->creationTime());
    }

    public static function updateScore(Game $game): Game
    {
        return Game::createOngoingGame(
            Team::updateTeam(
                $game->homeTeam()->teamName(),
                $game->homeTeam()->score()
            ),
            Team::updateTeam(
                $game->awayTeam()->teamName(),
                $game->awayTeam()->score()
            ),
            $game->creationTime()
        );
    }

    public static function toFinishedGame(Game $game): Game
    {
        return Game::createFinishedGame(
            Team::updateTeam(
                $game->homeTeam()->teamName(),
                $game->homeTeam()->score()
            ),
            Team::updateTeam(
                $game->awayTeam()->teamName(),
                $game->awayTeam()->score()
            ), $game->creationTime()
        );
    }
}