<?php declare(strict_types=1);

namespace App\Contract;

use App\Contract\Dto\Game;
use App\Contract\Dto\SummaryOfGames;

interface ScoreBoard
{
    public function startGame(Game $game): string;

    public function updateGame(Game $game): string;

    public function finishGame(Game $game): string;

    public function summaryOfGames(): GameSummary;

}