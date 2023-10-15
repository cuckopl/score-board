<?php declare(strict_types=1);

namespace App\Contract\Dto;

final class Game
{
    private GameStatus $gameStatus;
    private Team $homeTeam;
    private Team $awayTeam;
    private int $creationTime; //really simple timestamp just for example purpose.

    private function __construct(Team $homeTeam, Team $awayTeam, GameStatus $gameStatus, int $creationTime)
    {
        $this->homeTeam = $homeTeam;
        $this->awayTeam = $awayTeam;
        $this->gameStatus = $gameStatus;
        $this->creationTime = $creationTime;
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

    public function creationTime(): int
    {
        return $this->creationTime;
    }

    public function homeTeamScores(int $homeTeamScore = 1): Game
    {
        return self::createOngoingGame(
            Team::updateTeam($this->homeTeam->teamName(), $this->homeTeam->score() + $homeTeamScore),
            $this->awayTeam,
            $this->creationTime
        );
    }

    public function awayTeamScores(int $awayTeamScore = 1): Game
    {
        return self::createOngoingGame(
            $this->homeTeam,
            Team::updateTeam($this->awayTeam->teamName(), $this->awayTeam->score() + $awayTeamScore),
            $this->creationTime
        );
    }

    public static function createNewGame(string $homeTeam, string $awayTeam, int $timeStampStarted = 0): Game
    {
        return new Game(
            Team::createNewTeam($homeTeam),
            Team::createNewTeam($awayTeam),
            GameStatus::NOT_STARTED,
            $timeStampStarted
        );
    }

    public static function createOngoingGame(Team $homeTeam, Team $awayTeam, int $timeStampStarted = 0): Game
    {
        return new Game($homeTeam, $awayTeam, GameStatus::ON_GOING, $timeStampStarted);
    }

    public static function createFinishedGame(Team $homeTeam, Team $awayTeam, int $timeStampStarted = 0): Game
    {
        return new Game($homeTeam, $awayTeam, GameStatus::FINISHED, $timeStampStarted);
    }
}