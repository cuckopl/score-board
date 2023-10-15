<?php

namespace App\Test\Domain\FakeTestData;

use App\Contract\Dto\Game;
use App\Contract\Dto\Team;

class GamesTestData
{
    public static function games(): array
    {
        return [
            Game::createOngoingGame(
                Team::updateTeam("Uruguay", 7),
                Team::updateTeam("Brazil", 4),
                100
            ),
            Game::createOngoingGame(
                Team::updateTeam("Poland", 1),
                Team::updateTeam("Mexico", 2),
                500
            )
            ,
            Game::createOngoingGame(
                Team::updateTeam("Germany", 7),
                Team::updateTeam("France", 4),
                600
            ),
            Game::createOngoingGame(
                Team::updateTeam("Spain", 4),
                Team::updateTeam("Canada", 2),
                200
            ),
        ];
    }
}