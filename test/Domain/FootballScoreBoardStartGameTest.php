<?php declare(strict_types=1);

namespace App\Test\Domain;

use App\Contract\Dto\Game;
use App\Contract\Dto\Team;
use App\Contract\Exception\GameException;
use App\Domain\FootballScoreBoard;
use App\Domain\Repository\ScoreBoardStorage;
use DG\BypassFinals;
use PHPUnit\Framework\TestCase;

class FootballScoreBoardStartGameTest extends TestCase
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
        //I really want to have DTOs classes to be final and no one can extend.
        BypassFinals::enable();
//given
        $game = $this->getMockBuilder(Game::class)
            ->disableOriginalConstructor()
            ->getMock();

        $game
            ->method("awayTeam")
            ->willReturn(Team::updateTeam("USA", 5));

        $game
            ->method("homeTeam")
            ->willReturn(Team::updateTeam("Poland", 5));

        $scoreBoard = new FootballScoreBoard($this->createMock(ScoreBoardStorage::class));

        //then
        $this->expectException(GameException::class);

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

    public function testNewGameHasCorrectStatus(): void
    {
        //given
        $game =
            Game::createOngoingGame(
                Team::createNewTeam("United States"),
                Team::createNewTeam("Poland")
            );

        /** @var ScoreBoardStorage $scoreBoardMock */
        $scoreBoardMock = $this->createMock(ScoreBoardStorage::class);
        $scoreBoardMock
            ->expects($this->never())
            ->method("get")
            ->willReturn(null);

        $scoreBoard = new FootballScoreBoard($scoreBoardMock);

        //then
        $this->expectException(GameException::class);

        //when
        $scoreBoard->startGame($game);

    }

    public function testNewGameIsInOnGoingStatus(): void
    {
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


        $expectedResult = Game::createOngoingGame(
            Team::createNewTeam("United States"),
            Team::createNewTeam("Poland")
        );

        $scoreBoard = new FootballScoreBoard($scoreBoardMock);
        //when
        $result = $scoreBoard->startGame($game);

        //then
        $this->assertSame($expectedResult->gameStatus(), $result->gameStatus());
        $this->assertSame($expectedResult->awayTeam()->teamName(), $result->awayTeam()->teamName());
        $this->assertSame($expectedResult->homeTeam()->teamName(), $result->homeTeam()->teamName());
        $this->assertNotSame($game, $result); //check if thi isn't 100% same object reference
    }

//name from businees prespective: testIfTeamsCanPlayVsHimselfs
    public function testTheTeamsHaveDifferentNames(): void
    {
//given
        $game = Game::createNewGame(
            "United States",
            "United States"
        );

        /** @var ScoreBoardStorage $scoreBoardMock */
        $scoreBoardMock = $this->createMock(ScoreBoardStorage::class);
        $scoreBoardMock
            ->method("get")
            ->willReturn(null);

        $scoreBoard = new FootballScoreBoard($scoreBoardMock);

        $this->expectException(GameException::class);

        //when
        $scoreBoard->startGame($game);

    }

}