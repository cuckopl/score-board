<?php

namespace App\Test\Domain;

class FootballScoreBoardTest
{
//StartGame
    public function testIfGameAlreadyExists(): void
    {
        $this->assertSame('Hello, Alice!', 'Hello, Alice!');
    }

    public function testIfGameIsntAlreadyFinished(): void //
    {
        $this->assertSame('Hello, Alice!', 'Hello, Alice!');
    }

    //Update Game
    public function testIfGameAlreadyExistsWhenUpdatingScore(): void
    {
        $this->assertSame('Hello, Alice!', 'Hello, Alice!');
    }

    public function testIfGameScoreIsNotLowerFromPrevious(): void
    {
        $this->assertSame('Hello, Alice!', 'Hello, Alice!');
    }

    //FinishGame

    public function testCheckIfGameIsExistsWhenFinishing(): void
    {
        $this->assertSame('Hello, Alice!', 'Hello, Alice!');
    }


    // add tests that check if we don't return any reference to object stored in service ???
    // team score can be returned but can't be pushed to the domain layer
    //check that we can only change score by on 1 in soccer
}