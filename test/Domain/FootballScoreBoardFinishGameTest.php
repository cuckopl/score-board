<?php declare(strict_types=1);

namespace App\Test\Domain;

use App\Contract\Dto\Game;
use App\Contract\Dto\Team;
use App\Contract\Exception\GameIsMissingException;
use App\Contract\Exception\InvalidScoreException;
use App\Domain\FootballScoreBoard;
use App\Domain\Repository\ScoreBoardStorage;
use PHPUnit\Framework\TestCase;

class FootballScoreBoardFinishGameTest extends TestCase
{

}