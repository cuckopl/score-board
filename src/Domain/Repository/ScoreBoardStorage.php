<?php declare(strict_types=1);

namespace App\Domain\Repository;

use App\Contract\Dto\Game;

interface ScoreBoardStorage
{
    public function singleTeamIsPlaying(Game $game): bool;

    public function get(Game $game): ?Game;

    public function add(Game $game): void;

    public function update(Game $game): void;

    public function delete(Game $game): void;

    public function fetchAll(): array;

}