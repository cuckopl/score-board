<?php declare(strict_types=1);

namespace App\Domain\Validator;

use App\Contract\Dto\Game;

interface Validator
{
    public function validate(Game $game): void;
}