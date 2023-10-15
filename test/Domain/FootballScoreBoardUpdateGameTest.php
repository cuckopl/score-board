<?php declare(strict_types=1);

namespace App\Test\Domain;

use App\Contract\Dto\Game;
use App\Contract\Dto\Team;
use App\Contract\Exception\GameException;
use App\Contract\Exception\GameIsMissingException;
use App\Contract\Exception\InvalidScoreException;
use App\DataAccess\InMemoryStorage;
use App\Domain\FootballScoreBoard;
use App\Domain\Repository\ScoreBoardStorage;
use App\Domain\Validator\ValidatorPipeline;
use PHPUnit\Framework\TestCase;

class FootballScoreBoardUpdateGameTest extends TestCase
{

    public function testThrowExceptionIfGameNotFound(): void
    {
        //given
        $game = Game::createOngoingGame(
            Team::updateTeam("United States", 1),
            Team::updateTeam("Poland", 1),
        );
        $scoreBoard = new FootballScoreBoard($this->createMock(ScoreBoardStorage::class), new ValidatorPipeline());
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

        /** @var ScoreBoardStorage $scoreBoardStorage */
        $scoreBoardStorage = $this->createMock(ScoreBoardStorage::class);
        $scoreBoardStorage
            ->expects($this->once())
            ->method("get")
            ->willReturn(null);

        $scoreBoard = new FootballScoreBoard($scoreBoardStorage, new ValidatorPipeline());

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

        /** @var ScoreBoardStorage $scoreBoardStorage */
        $scoreBoardStorage = $this->createMock(ScoreBoardStorage::class);
        $scoreBoardStorage
            ->expects($this->atLeast(1))
            ->method("get")
            ->willReturn($gameInStorage, $game, $gameInStorage);

        $scoreBoard = new FootballScoreBoard($scoreBoardStorage, new ValidatorPipeline());

        //then
        $this->expectExceptionMessage(InvalidScoreException::class);
        $this->expectExceptionMessage("Score can't be lower than previous one");

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

        /** @var ScoreBoardStorage $scoreBoardStorage */
        $scoreBoardStorage = $this->createMock(ScoreBoardStorage::class);
        $scoreBoardStorage
            ->expects($this->atLeast(1))
            ->method("get")
            ->willReturn($gameInStorage, $gameInStorage, $game);

        $scoreBoard = new FootballScoreBoard($scoreBoardStorage, new ValidatorPipeline());

        //then
        $this->expectExceptionMessage(InvalidScoreException::class);
        $this->expectExceptionMessage("Score can be incremented only +1 per execution(we can't score 2 point in football)");

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

        $inMemoryStorage = new InMemoryStorage();
        $inMemoryStorage->add($gameInStorage);

        $scoreBoard = new FootballScoreBoard($inMemoryStorage, new ValidatorPipeline());

        //when
        $result = $scoreBoard->updateGame($game);

        //then
        $this->assertSame($expectedResult->gameStatus(), $result->gameStatus());
        $this->assertSame($expectedResult->awayTeam()->teamName(), $result->awayTeam()->teamName());
        $this->assertSame($expectedResult->homeTeam()->teamName(), $result->homeTeam()->teamName());
        $this->assertNotSame($expectedResult, $result); //check if thi isn't 100% same object reference to prevent any changes
    }

    public function testThrowExceptionOnWrongGameStatus(): void
    {
        //given
        $game =
            Game::createFinishedGame(
                Team::createNewTeam("United States"),
                Team::createNewTeam("Poland")
            );

        /** @var ScoreBoardStorage $scoreBoardStorage */
        $scoreBoardStorage = $this->createMock(ScoreBoardStorage::class);
        $scoreBoardStorage
            ->expects($this->atLeastOnce())
            ->method("get")
            ->willReturn($game);

        $scoreBoard = new FootballScoreBoard($scoreBoardStorage, new ValidatorPipeline());

        //then
        $this->expectException(GameException::class);
        $this->expectExceptionMessage("We can't update game with other status than ON_GOING");

        //when
        $scoreBoard->updateGame($game);

    }
}