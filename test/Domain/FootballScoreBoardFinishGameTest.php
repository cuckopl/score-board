<?php declare(strict_types=1);

namespace App\Test\Domain;

use App\Contract\Dto\Game;
use App\Contract\Dto\Team;
use App\Contract\Exception\GameIsMissingException;
use App\Domain\FootballScoreBoard;
use App\Domain\Repository\ScoreBoardStorage;
use App\Domain\Validator\ValidatorPipeline;
use PHPUnit\Framework\TestCase;

class FootballScoreBoardFinishGameTest extends TestCase
{
    public function testCheckGameWasRemoved(): void
    {
//given
        $game = Game::createOngoingGame(
            Team::updateTeam("United States", 0),
            Team::updateTeam("Poland", 0),
        );

        $expectedResult = Game::createFinishedGame(
            Team::updateTeam("United States", 0),
            Team::updateTeam("Poland", 0),
        );

        /** @var ScoreBoardStorage $scoreBoardMock */
        $scoreBoardMock = $this->createMock(ScoreBoardStorage::class);
        $scoreBoardMock
            ->expects($this->once())
            ->method("delete");

        $scoreBoardMock
            ->method("get")
            ->willReturn($game);

        $scoreBoard = new FootballScoreBoard($scoreBoardMock, new ValidatorPipeline());

//when
        $result = $scoreBoard->finishGame($game);

//then
        $this->assertSame($expectedResult->gameStatus(), $result->gameStatus());
        $this->assertSame($expectedResult->awayTeam()->teamName(), $result->awayTeam()->teamName());
        $this->assertSame($expectedResult->homeTeam()->teamName(), $result->homeTeam()->teamName());
        $this->assertNotSame($game, $result);

    }

    public function testThrowExceptionWhenGameNotFoundInStorage(): void
    {
//given
        $game = Game::createOngoingGame(
            Team::updateTeam("United States", 0),
            Team::updateTeam("Poland", 0),
        );


        /** @var ScoreBoardStorage $scoreBoardMock */
        $scoreBoardMock = $this->createMock(ScoreBoardStorage::class);
        $scoreBoardMock
            ->expects($this->once())
            ->method("get")
            ->willReturn(null);
        $scoreBoard = new FootballScoreBoard($scoreBoardMock, new ValidatorPipeline());

//then
        $this->expectException(GameIsMissingException::class);
        $this->expectExceptionMessage("Game weren't started");

//when
        $scoreBoard->finishGame($game);
    }

    public function testThrowExceptionWhenGameWillHaveDifferentStatusThanOngoing(): void
    {
//given
        $game = Game::createOngoingGame(
            Team::updateTeam("United States", 0),
            Team::updateTeam("Poland", 0),
        );


        /** @var ScoreBoardStorage $scoreBoardMock */
        $scoreBoardMock = $this->createMock(ScoreBoardStorage::class);
        $scoreBoardMock
            ->expects($this->once())
            ->method("get")
            ->willReturn(null);
        $scoreBoard = new FootballScoreBoard($scoreBoardMock, new ValidatorPipeline());

//then
        $this->expectException(GameIsMissingException::class);
        $this->expectExceptionMessage("Game weren't started");

//when
        $scoreBoard->finishGame($game);
    }

}
