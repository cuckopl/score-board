<?php declare(strict_types=1);

namespace App\Domain;

use App\Contract\Dto\Game;
use App\Contract\Exception\GameException;
use App\Contract\Exception\GameIsMissingException;
use App\Contract\GameSummary;
use App\Contract\ScoreBoard;
use App\Domain\Mapper\GameMapper;
use App\Domain\Repository\ScoreBoardStorage;
use App\Domain\Validator\ValidatorPipeline;

class FootballScoreBoard implements ScoreBoard
{
    private ScoreBoardStorage $scoreBoardStorage;
    private ValidatorPipeline $validatorPipeline;

    public function __construct(ScoreBoardStorage $scoreBoardStorage, ValidatorPipeline $validatorPipeline)
    {
        $this->scoreBoardStorage = $scoreBoardStorage;
        $this->validatorPipeline = $validatorPipeline;
    }

    /**
     * @throws GameException
     */
    public function startGame(Game $game): Game
    {
        $this->validatorPipeline->validateStartGame($game, $this->scoreBoardStorage);

        $onGoingGame = GameMapper::toOngoingGame($game);

        $this->scoreBoardStorage->add($onGoingGame);
        return $onGoingGame;
    }

    public function updateGame(Game $game): Game
    {
        $previousGame = $this->scoreBoardStorage->get($game);
        if ($previousGame == null) {
            throw new GameIsMissingException("Game weren't started");
        }
        $this->validatorPipeline->validateUpdateGame($game, $this->scoreBoardStorage);

        $this->scoreBoardStorage->update(GameMapper::updateScore($game));

        return $game;
    }

    /**
     * @throws GameIsMissingException
     */
    public function finishGame(Game $game): Game
    {
        $previousGame = $this->scoreBoardStorage->get($game);
        if ($previousGame == null) {
            throw new GameIsMissingException("Game weren't started");
        }

        $this->validatorPipeline->validateDeleteGame($game);

        $this->scoreBoardStorage->delete($game);
        return GameMapper::toFinishedGame($game);

    }

    public function summaryOfGames(): GameSummary
    {
        return new SortedGameSummary($this->scoreBoardStorage->fetchAll());
    }
}