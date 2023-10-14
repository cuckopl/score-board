<?php

namespace App\DataAccess;

use App\Contract\Dto\Game;
use App\Domain\Repository\ScoreBoardStorage;

class InMemoryStorage implements ScoreBoardStorage
{
    private array $games;

    public function __construct()
    {
        $this->games = [];
    }


    public function get(Game $game): Game
    {
        return $this->games[$game->awayTeam()->teamName() . $game->homeTeam()->teamName()] = $game;
    }

    public function add(Game $game): void
    {
        $this->games[$game->awayTeam()->teamName() . $game->homeTeam()->teamName()] = $game;
    }

    public function delete(Game $game): void
    {
        unset($this->games[$game->awayTeam()->teamName() . $game->homeTeam()->teamName()]);
    }

    public function update(Game $game): void
    {
        // Check if game exists??
        $this->games[$game->awayTeam()->teamName() . $game->homeTeam()->teamName()] = $game;
    }

    public function fetchAll(): array
    {
        return $this->games;
    }

}