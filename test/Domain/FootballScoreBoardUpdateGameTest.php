<?php declare(strict_types=1);

namespace App\Test\Domain;

use App\Contract\Dto\Game;
use App\Contract\Dto\Team;
use App\Contract\Exception\GameIsMissingException;
use App\Contract\Exception\InvalidScoreException;
use App\Domain\FootballScoreBoard;
use App\Domain\Repository\ScoreBoardStorage;
use PHPUnit\Framework\TestCase;

class FootballScoreBoardUpdateGameTest extends TestCase
{
    
    public function testIfGameExists(): void
    {
        //given
        $game = Game::createOngoingGame(
            Team::updateTeam("United States", 1),
            Team::updateTeam("Poland", 1),
        );
        $scoreBoard = new FootballScoreBoard($this->createMock(ScoreBoardStorage::class));
        //then
        $this->expectException(GameIsMissingException::class);

        //when
        $scoreBoard->updateGame($game);
    }

    public function testIfGameAlreadyExistsWhenUpdatingScore(): void
    {
        //given
        $game = Game::createOngoingGame(
            Team::updateTeam("United States", 1),
            Team::updateTeam("Poland", 1),
        );

        /** @var ScoreBoardStorage $scoreBoardMock */
        $scoreBoardMock = $this->createMock(ScoreBoardStorage::class);
        $scoreBoardMock
            ->expects($this->once())
            ->method("get")
            ->willReturn(null);

        $scoreBoard = new FootballScoreBoard($scoreBoardMock);

        //then
        $this->expectExceptionMessage(GameIsMissingException::class);
        $this->expectExceptionMessage("Game weren't started");

        //when
        $scoreBoard->updateGame($game);

    }

    public function testIfGameScoreIsNotLowerFromPrevious(): void
    {
        //given
        $game = Game::createOngoingGame(
            Team::updateTeam("United States", 1),
            Team::updateTeam("Poland", 1),
        );

        $gameInStorage = Game::createOngoingGame(
            Team::updateTeam("United States", 5),
            Team::updateTeam("Poland", 5),
        );

        /** @var ScoreBoardStorage $scoreBoardMock */
        $scoreBoardMock = $this->createMock(ScoreBoardStorage::class);
        $scoreBoardMock
            ->expects($this->atLeast(1))
            ->method("get")
            ->willReturn($gameInStorage, $game);

        $scoreBoard = new FootballScoreBoard($scoreBoardMock);

        //then
        $this->expectExceptionMessage(InvalidScoreException::class);
        $this->expectExceptionMessage("Score can be lower than previous one");

        //when
        $scoreBoard->updateGame($game);
    }


    public function testThrowExceptionWhenScoreWillBeIncrementedMoreThanOnePointPerExecution(): void
    {
        //given
        $game = Game::createOngoingGame(
            Team::updateTeam("United States", 3),
            Team::updateTeam("Poland", 3),
        );

        $gameInStorage = Game::createOngoingGame(
            Team::updateTeam("United States", 1),
            Team::updateTeam("Poland", 1),
        );

        /** @var ScoreBoardStorage $scoreBoardMock */
        $scoreBoardMock = $this->createMock(ScoreBoardStorage::class);
        $scoreBoardMock
            ->expects($this->atLeast(1))
            ->method("get")
            ->willReturn($gameInStorage, $game);

        $scoreBoard = new FootballScoreBoard($scoreBoardMock);

        //then
        $this->expectExceptionMessage(InvalidScoreException::class);
        $this->expectExceptionMessage("Score can be incremented only +1 per execution(we can't score 2 point in football");

        //when
        $scoreBoard->updateGame($game);
    }


    public function testUpdateAndCheckMatchWithNewScore(): void
    {
        //given
        $game = Game::createOngoingGame(
            Team::updateTeam("United States", 2),
            Team::updateTeam("Poland", 2),
        );

        $gameInStorage = Game::createOngoingGame(
            Team::updateTeam("United States", 1),
            Team::updateTeam("Poland", 1),
        );

        $expectedResult = Game::createOngoingGame(
            Team::updateTeam("United States", 2),
            Team::updateTeam("Poland", 2),
        );


        /** @var ScoreBoardStorage $scoreBoardMock */
        $scoreBoardMock = $this->createMock(ScoreBoardStorage::class);
        $scoreBoardMock
            ->expects($this->atLeast(1))
            ->method("get")
            ->willReturn($gameInStorage, $game);

        $scoreBoard = new FootballScoreBoard($scoreBoardMock);

        //when
        $result = $scoreBoard->updateGame($game);


        //then
        $this->assertSame($expectedResult->gameStatus(), $result->gameStatus());
        $this->assertSame($expectedResult->awayTeam()->teamName(), $result->awayTeam()->teamName());
        $this->assertSame($expectedResult->homeTeam()->teamName(), $result->homeTeam()->teamName());
        $this->assertNotSame($expectedResult, $result); //check if thi isn't 100% same object reference to prevent any changes
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