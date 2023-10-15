<?php declare(strict_types=1);

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

    public function get(Game $game): ?Game
    {
        return $this->games[$game->awayTeam()->teamName() . $game->homeTeam()->teamName()];
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
        $this->games[$game->awayTeam()->teamName() . $game->homeTeam()->teamName()] = $game;
    }

    public function fetchAll(): array
    {
        //don't trust any one copy whole array
        return array_merge(array(), $this->games);
    }

    public function singleTeamIsPlaying(Game $game): bool
    {
        return !(count(array_filter($this->games, function ($key) use ($game) {
                return
                    str_contains($key, $game->homeTeam()->teamName())
                    ||
                    str_contains($key, $game->awayTeam()->teamName());
            }, ARRAY_FILTER_USE_KEY)) <= 0);
    }
}