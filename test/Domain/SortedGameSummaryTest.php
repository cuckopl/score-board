<?php declare(strict_types=1);

namespace App\Test\Domain;

use App\Domain\SortedGameSummary;
use App\Test\Domain\FakeTestData\GamesTestData;
use PHPUnit\Framework\TestCase;

class SortedGameSummaryTest extends TestCase
{

    public function testSorting()
    {
        $resultArray = [
            "Germany 7 - France 4",
            "Uruguay 7 - Brazil 4",
            "Spain 4 - Canada 2",
            "Poland 1 - Mexico 2",
        ];
        $sortedGameSummary = new SortedGameSummary(GamesTestData::games());

        $this->assertSame($resultArray, $sortedGameSummary->summary());

    }
}
