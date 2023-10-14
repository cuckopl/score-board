<?php declare(strict_types=1);

namespace App\Contract\Dto;

final class Game
{
    private GameStatus $gameStatus;
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

    public static function createNewGame(string $homeTeam, string $awayTeam): Game
    {
        return new Game(
            Team::createNewTeam($homeTeam),
            Team::createNewTeam($awayTeam),
            GameStatus::NOT_STARTED
        );
    }
//todo: create special enums that will allow to add score to team by some constant? Or validate
//todo: better inside service??
    public static function createOngoingGame(Team $homeTeam, Team $awayTeam): Game
    {
        return new Game($homeTeam, $awayTeam, GameStatus::ON_GOING);
    }

    public static function createFinished(Team $homeTeam, Team $awayTeam): Game
    {
        return new Game($homeTeam, $awayTeam, GameStatus::FINISHED);
    }
}