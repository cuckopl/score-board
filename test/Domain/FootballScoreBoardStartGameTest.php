<?php declare(strict_types=1);

namespace App\Test\Domain;

use App\Contract\Dto\Game;
use App\Contract\Dto\Team;
use App\Contract\Exception\GameException;
use App\Contract\Exception\GameExistsException;
use App\Domain\FootballScoreBoard;
use App\Domain\Repository\ScoreBoardStorage;
use DG\BypassFinals;
use PHPUnit\Framework\MockObject\Exception;
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

    public function testIfGameScoreIsZeroOnStart(): void
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

        $expectedResult = Game::createOngoingGame(
            Team::createNewTeam("United States"),
            Team::createNewTeam("Poland")
        );

        /** @var ScoreBoardStorage $scoreBoardMock */
        $scoreBoardMock = $this->createMock(ScoreBoardStorage::class);
        $scoreBoardMock
            ->expects($this->once())
            ->method("get")
            ->willReturn(null);

        $scoreBoard = new FootballScoreBoard($scoreBoardMock);
        //when
        $result = $scoreBoard->startGame($game);

        $this->assertSame($expectedResult->gameStatus(), $result->gameStatus());
        $this->assertSame($expectedResult->awayTeam()->teamName(), $result->awayTeam()->teamName());
        $this->assertSame($expectedResult->homeTeam()->teamName(), $result->homeTeam()->teamName());
        $this->assertNotSame($game, $result);


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

        $scoreBoardMock
            ->expects($this->once())
            ->method("add");

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
        $this->assertNotSame($game, $result);
        //check if thi isn't 100% same object reference to prevent any modification outside the service,
        // even if objets aren't mutable someone can store score history
        // from or store returned object, and he doesn't want to change his state from inside the service
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

    /**
     * @throws Exception
     */
    public function testTheIfTheTeamsHasSwappedNames(): void
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


        $scoreBoard = new FootballScoreBoard($scoreBoardMock);

        $this->expectException(GameExistsException::class);

        //when
        $scoreBoard->startGame($game);

    }
//Swapped Names test when u swap names
}