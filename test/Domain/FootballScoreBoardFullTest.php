<?php declare(strict_types=1);

namespace App\Test\Domain;

use App\Contract\Dto\Game;
use App\Contract\Exception\GameException;
use App\Contract\Exception\GameIsMissingException;
use App\DataAccess\InMemoryStorage;
use App\Domain\FootballScoreBoard;
use App\Domain\Validator\ValidatorPipeline;
use PHPUnit\Framework\TestCase;

class FootballScoreBoardFullTest extends TestCase
{
    private const FOOTBALL_SINGLE_POINT = 1;

    /**
     * @throws GameIsMissingException
     * @throws GameException
     */
    public function testTestIfSummaryAddRemoveFinishWorksCorrect()
    {
        $footballScoreBoard = new FootballScoreBoard(new InMemoryStorage(), new ValidatorPipeline());
        $germanyFranceGame = Game::createNewGame("Germany", "France", 700);
        $spainBrazilGame = Game::createNewGame("Spain", "Brazil", 600);
        $mexicoCanadaGame = Game::createNewGame("Mexico", "Canada", 500);
        $uruguayItalyGame = Game::createNewGame("Uruguay", "Italy", 122);


        $mexicoCanadaGame = $footballScoreBoard->startGame($mexicoCanadaGame);
        $spainBrazilGame = $footballScoreBoard->startGame($spainBrazilGame);
        $germanyFranceGame = $footballScoreBoard->startGame($germanyFranceGame);
        $uruguayItalyGame = $footballScoreBoard->startGame($uruguayItalyGame);

        $summaryOfGameAfterAfter10Min = $footballScoreBoard->summaryOfGames();
//10 min summary
        $mexicoCanadaGame = $footballScoreBoard->updateGame(
            $mexicoCanadaGame
                ->homeTeamScores(self::FOOTBALL_SINGLE_POINT)
                ->awayTeamScores(self::FOOTBALL_SINGLE_POINT)
        );
        $spainBrazilGame = $footballScoreBoard->updateGame(
            $spainBrazilGame->awayTeamScores(self::FOOTBALL_SINGLE_POINT)
        );

        $germanyFranceGame = $footballScoreBoard->updateGame($germanyFranceGame);
        $uruguayItalyGame = $footballScoreBoard->updateGame($uruguayItalyGame);

        $summaryOfGameAfterAfter20Min = $footballScoreBoard->summaryOfGames();
//20 min summary


        $germanyFranceGame = $germanyFranceGame->homeTeamScores();
        $footballScoreBoard->updateGame($germanyFranceGame);

        $germanyFranceGame = $germanyFranceGame->homeTeamScores();
        $footballScoreBoard->updateGame($germanyFranceGame);

        $germanyFranceGame = $germanyFranceGame->homeTeamScores();
        $footballScoreBoard->updateGame($germanyFranceGame);

        $germanyFranceGame = $germanyFranceGame->homeTeamScores();
        $footballScoreBoard->updateGame($germanyFranceGame);

        $footballScoreBoard->finishGame($spainBrazilGame);

        //30min summary
        $summaryOfGameAfterAfter30Min = $footballScoreBoard->summaryOfGames();

        $expectedSummaryAfter10Min = [
            "Germany 0 - France 0",
            "Spain 0 - Brazil 0",
            "Mexico 0 - Canada 0",
            "Uruguay 0 - Italy 0",
        ];

        $expectedSummaryAfter20Min = [
            "Mexico 1 - Canada 1",
            "Spain 0 - Brazil 1",
            "Germany 0 - France 0",
            "Uruguay 0 - Italy 0",
        ];

        $expectedSummaryAfter30Min = [
            "Germany 4 - France 0",
            "Mexico 1 - Canada 1",
            "Uruguay 0 - Italy 0",
        ];


        $this->assertSame($expectedSummaryAfter10Min, $summaryOfGameAfterAfter10Min->summary());
        $this->assertSame($expectedSummaryAfter20Min, $summaryOfGameAfterAfter20Min->summary());
        $this->assertSame($expectedSummaryAfter30Min, $summaryOfGameAfterAfter30Min->summary());


    }
}