<?php

namespace App\Domain\Repository;

use App\Contract\Dto\Game;

interface ScoreBoardStorage
{
    public function get(Game $game): Game;

    public function add(Game $game): void;

    public function update(Game $game): void;

    public function delete(Game $game): void;

    public function fetchAll(): array;

}