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


    public static function createTeam(string $teamName, int $score)
    {
        return new Team($teamName, $score);
    }
}


