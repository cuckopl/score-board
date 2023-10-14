<?php declare(strict_types=1);

namespace App\Contract;

use App\Contract\Dto\Game;

interface ScoreBoard
{
    public function startGame(Game $game): Game;

    public function updateGame(Game $game): Game;

    public function finishGame(Game $game): Game;

    public function summaryOfGames(): GameSummary;

}