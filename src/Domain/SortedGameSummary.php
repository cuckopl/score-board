<?php declare(strict_types=1);

namespace App\Domain;

use App\Contract\Dto\Game;
use App\Contract\GameSummary;

class SortedGameSummary implements GameSummary
{
    private array $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    private function sort($data)
    {
        usort($data, function (Game $previousGame, Game $nextGame) {

            $previousSum = $previousGame->awayTeam()->score() + $previousGame->homeTeam()->score();
            $nextSum = $nextGame->awayTeam()->score() + $nextGame->homeTeam()->score();

            if ($nextSum == $previousSum) {
                return $previousGame->creationTime() > $nextGame->creationTime() ? -1 : 1;
            }
            return $previousSum > $nextSum ? -1 : 1;
        });

        return $data;
    }

    public function summary(): array
    {
        return array_map(function (Game $singleGame) {
            return sprintf("%s %d - %s %d",
                $singleGame->homeTeam()->teamName(),
                $singleGame->homeTeam()->score(),
                $singleGame->awayTeam()->teamName(),
                $singleGame->awayTeam()->score()
            );
        }, $this->sort($this->data));
    }
}