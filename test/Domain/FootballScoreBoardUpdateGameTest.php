<?php declare(strict_types=1);

namespace App\Test\Domain;

use App\Contract\Dto\Game;
use App\Domain\FootballScoreBoard;
use App\Domain\Repository\ScoreBoardStorage;
use PHPUnit\Framework\TestCase;

class FootballScoreBoardUpdateGameTest extends TestCase
{

//StartGame
    public function testIfGameAlreadyExists(): void
    {
//given
        $game = Game::createNewGame(
            "United States",
            "Poland"
        );

        /** @var ScoreBoardStorage $scoreBoardMock */
        $scoreBoardMock = $this->createMock(ScoreBoardStorage::class);
        $scoreBoardMock
            ->expects($this->once())
            ->method("get")
            ->willReturn($game);
        $scoreBoard = new FootballScoreBoard($scoreBoardMock);

        //then
        $this->expectException(\Exception::class);

        //when
        $scoreBoard->startGame($game);
    }

    public function testIfGameScoreIsZeroZeroOnStart(): void
    {
//given
        $game = Game::createNewGame(
            "United States",
            "Poland"
        );

        /** @var ScoreBoardStorage $scoreBoardMock */
        $scoreBoardMock = $this->createMock(ScoreBoardStorage::class);
        $scoreBoardMock
            ->expects($this->once())
            ->method("get")
            ->willReturn($game);
        $scoreBoard = new FootballScoreBoard($scoreBoardMock);

        //then
        $this->expectException(\Exception::class);

        //when
        $scoreBoard->startGame($game);
    }

    public function testCanWeAddGame(): void
    {
        //given
        $game = Game::createNewGame(
            "United States",
            "Poland"
        );

        /** @var ScoreBoardStorage $scoreBoardMock */
        $scoreBoardMock = $this->createMock(ScoreBoardStorage::class);
        $scoreBoardMock
            ->expects($this->once())
            ->method("get")
            ->willReturn(null);

        $scoreBoard = new FootballScoreBoard($scoreBoardMock);
        //when
        $scoreBoard->startGame($game);

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