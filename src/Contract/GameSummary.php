<?php declare(strict_types=1);

namespace App\Contract;

interface GameSummary
{
    public function summary(): array;
}