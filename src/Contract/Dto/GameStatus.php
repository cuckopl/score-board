<?php declare(strict_types=1);

namespace App\Contract\Dto;

enum GameStatus
{
    case ON_GOING;
    case NOT_STARTED;
    case FINISHED;
}
