<?php declare(strict_types=1);

namespace App\Contract\Dto;

final class Game
{
    private GameStatus $gameStatus = GameStatus::NOT_STARTED;
    private Team $homeTeam;
    private Team $awayTeam;

    /**
     * @param Team $homeTeam
     * @param Team $awayTeam
     */
    private function __construct(Team $homeTeam, Team $awayTeam, GameStatus $gameStatus)
    {
        $this->homeTeam = $homeTeam;
        $this->awayTeam = $awayTeam;
        $this->gameStatus = $gameStatus;
    }

    public function homeTeam(): Team
    {
        return $this->homeTeam;
    }

    public function awayTeam(): Team
    {
        return $this->awayTeam;
    }

    public function gameStatus(): GameStatus
    {
        return $this->gameStatus;
    }

    public static function createNewGame(Team $homeTeam, Team $awayTeam): Game
    {
        return new Game($homeTeam, $awayTeam, GameStatus::NOT_STARTED);
    }

    public static function createOngoingGame(Team $homeTeam, Team $awayTeam): Game
    {
        return new Game($homeTeam, $awayTeam, GameStatus::ON_GOING);
    }
}