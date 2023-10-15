<?php declare(strict_types=1);

namespace App\Test\Domain;

use App\Domain\FootballScoreBoard;
use App\Domain\Repository\ScoreBoardStorage;
use App\Domain\Validator\ValidatorPipeline;
use App\Test\Domain\FakeTestData\GamesTestData;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;

class FootballScoreBoardSummaryGameTest extends TestCase
{
    /**
     * @throws Exception
     */
    public function testCheckSummaryIsReturnedInCorrectState(): void
    {
        /** @var ScoreBoardStorage $scoreBoardMock */
        $scoreBoardMock = $this->createMock(ScoreBoardStorage::class);
        $scoreBoardMock
            ->expects($this->once())
            ->method("fetchAll")
            ->willReturn(GamesTestData::games());
        //when
        $scoreBoard = new FootballScoreBoard($scoreBoardMock, new ValidatorPipeline());
        //then
        $resultArray = [
            "Germany 7 - France 4",
            "Uruguay 7 - Brazil 4",
            "Spain 4 - Canada 2",
            "Poland 1 - Mexico 2",
        ];


        $this->assertSame($resultArray, $scoreBoard->summaryOfGames()->summary());


    }

}