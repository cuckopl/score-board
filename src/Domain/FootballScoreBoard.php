<?php

namespace App\Domain;

use App\Contract\Dto\Game;
use App\Contract\GameSummary;
use App\Contract\ScoreBoard;
use App\Domain\Repository\ScoreBoardStorage;

class FootballScoreBoard implements ScoreBoard
{

    private ScoreBoardStorage $scoreBoardStorage;

    public function __construct($scoreBoardStorage)
    {
        // TODO: Implement finishGame() method.
    }

    public function startGame(Game $game): string
    {

    }

    public function updateGame(Game $game): string
    {
        // TODO: Implement updateGame() method.
    }

    public function finishGame(Game $game): string
    {
        // TODO: Implement finishGame() method.
    }

    public function summaryOfGames(): GameSummary
    {
        // TODO: Implement finishGame() method.
    }
}