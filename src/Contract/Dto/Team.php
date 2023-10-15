<?php declare(strict_types=1);

namespace App\Contract\Dto;

final class Team
{
    private string $teamName;
    private int $score;

    public function __construct(string $teamName, int $score)
    {
        $this->teamName = $teamName;
        $this->score = $score;
    }

    public function score(): int
    {
        return $this->score;
    }

    public function teamName(): string
    {
        return $this->teamName;
    }

    public static function createNewTeam(string $teamName): Team
    {
        return new Team($teamName, 0);
    }

    public static function updateTeam(string $teamName, int $score): Team
    {
        return new Team($teamName, $score);
    }
}


