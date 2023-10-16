<?php

namespace App\Test\DataAccess;

use App\Contract\Dto\Game;
use App\DataAccess\InMemoryStorage;
use PHPUnit\Framework\TestCase;

class InMemoryStorageTest extends TestCase
{
    public function testFetchAll(): void
    {
        $inMemoryStorage = new InMemoryStorage();
        $inMemoryStorage->add(
            Game::createNewGame("Team 1", "Team 5")
        );
        $inMemoryStorage->add(
            Game::createNewGame("Team 2", "Team 4")
        );
        $inMemoryStorage->add(
            Game::createNewGame("Team 2", "Team 6")
        );

        $result = $inMemoryStorage->isSingleTeamInGame(Game::createNewGame("Team 2", "Team 3"));

        $this->assertSame(true, $result);
    }

    //Skipped other test to keep it shorter.
}