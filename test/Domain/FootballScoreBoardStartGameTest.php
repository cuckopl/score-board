<?php declare(strict_types=1);

namespace App\Test\Domain;

use App\Contract\Dto\Game;
use App\Contract\Dto\Team;
use App\Contract\Exception\GameException;
use App\Contract\Exception\GameExistsException;
use App\Domain\FootballScoreBoard;
use App\Domain\Repository\ScoreBoardStorage;
use App\Domain\Validator\ValidatorPipeline;
use DG\BypassFinals;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;

class FootballScoreBoardStartGameTest extends TestCase
{
    /**
     * @throws Exception
     * @throws GameException
     */
    public function testThrowExceptionIfGameIsAlreadyStarted(): void
    {
//given
        $game = Game::createNewGame(
            "United States",
            "Poland"
        );

        /** @var ScoreBoardStorage $scoreBoardMock */
        $scoreBoardMock = $this->createMock(ScoreBoardStorage::class);
        $scoreBoardMock
            ->expects($this->atLeastOnce())
            ->method("get")
            ->willReturn($game);
        $scoreBoard = new FootballScoreBoard($scoreBoardMock, new ValidatorPipeline());

//then
        $this->expectException(GameException::class);
        $this->expectExceptionMessage("Game is already started");

//when
        $scoreBoard->startGame($game);
    }

    public function testThrowExceptionIfScoreIsDifferentThanZeroToZero(): void
    {
        //I really want to have DTOs classes to be final and no one can extend them.
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

        $scoreBoard = new FootballScoreBoard($this->createMock(ScoreBoardStorage::class), new ValidatorPipeline());

//then
        $this->expectException(GameException::class);
        $this->expectExceptionMessage("Game can't be started because of score should be starting from 0:0");

//when
        $scoreBoard->startGame($game);
    }

    public function testThrowExceptionOnWrongGameStatus(): void
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
            ->method("get")
            ->willReturn(null);

        $scoreBoard = new FootballScoreBoard($scoreBoardMock, new ValidatorPipeline());

//then
        $this->expectException(GameException::class);
        $this->expectExceptionMessage("We can't create game with status other than NOT_STARTED");

//when
        $scoreBoard->startGame($game);

    }

    public function testGameCanBeAdded(): void
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

        $scoreBoardMock
            ->expects($this->once())
            ->method("add");

        $expectedResult = Game::createOngoingGame(
            Team::createNewTeam("United States"),
            Team::createNewTeam("Poland")
        );

        $scoreBoard = new FootballScoreBoard($scoreBoardMock, new ValidatorPipeline());
//when
        $result = $scoreBoard->startGame($game);

//then
        $this->assertSame($expectedResult->gameStatus(), $result->gameStatus());
        $this->assertSame($expectedResult->awayTeam()->teamName(), $result->awayTeam()->teamName());
        $this->assertSame($expectedResult->homeTeam()->teamName(), $result->homeTeam()->teamName());
        $this->assertNotSame($game, $result);
    }

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

        $scoreBoard = new FootballScoreBoard($scoreBoardMock, new ValidatorPipeline());
//then
        $this->expectException(GameException::class);

//when
        $scoreBoard->startGame($game);

    }

    /**
     * @throws GameException|Exception
     */
    public function testThrowExceptionIfAwayTeamIsAlreadyPlayingAsAwayTeam(): void
    {
//given
        $game = Game::createNewGame(
            "United States",
            "Poland"
        );

        $gameInStorage = Game::createNewGame(
            "Poland",
            "United States",
        );

        /** @var ScoreBoardStorage $scoreBoardMock */
        $scoreBoardMock = $this->createMock(ScoreBoardStorage::class);

        $scoreBoardMock
            ->expects($this->atLeast(1))
            ->method("get")
            ->with($this->anything())
            ->willReturnCallback(function (Game $callbackGame) use ($gameInStorage, $game) {
                if ($callbackGame->homeTeam()->teamName() == "United States" && $callbackGame->awayTeam()->teamName() == "Poland") {
                    return $game;
                }
                if ($callbackGame->homeTeam()->teamName() == "Poland" && $callbackGame->awayTeam()->teamName() == "United States") {
                    return $gameInStorage;
                }
            }
            );


        $scoreBoard = new FootballScoreBoard($scoreBoardMock, new ValidatorPipeline());
//then
        $this->expectException(GameExistsException::class);

//when
        $scoreBoard->startGame($game);

    }

    public function testThrowExceptionIfAnyTeamIsAlreadyPlayingWithOtherTeam(): void
    {
//given
        $game = Game::createNewGame(
            "United States",
            "Poland"
        );

        /** @var ScoreBoardStorage $scoreBoardMock */
        $scoreBoardMock = $this->createMock(ScoreBoardStorage::class);
        $scoreBoardMock
            ->method("get")
            ->willReturn(null);

        $scoreBoardMock
            ->method("isSingleTeamInGame")
            ->willReturn(true);

        $scoreBoard = new FootballScoreBoard($scoreBoardMock, new ValidatorPipeline());

        $this->expectException(GameException::class);
        $this->expectExceptionMessage("One of the teams in match is already playing game.");

        //when
        $scoreBoard->startGame($game);

    }

}