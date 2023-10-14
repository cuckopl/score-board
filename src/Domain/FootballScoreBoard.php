<?php

namespace App\Domain;

use App\Contract\Dto\Game;
use App\Contract\Dto\GameStatus;
use App\Contract\Exception\GameException;
use App\Contract\Exception\GameExistsException;
use App\Contract\Exception\InvalidScoreException;
use App\Contract\GameSummary;
use App\Contract\ScoreBoard;
use App\Domain\Mapper\GameMapper;
use App\Domain\Repository\ScoreBoardStorage;

class FootballScoreBoard implements ScoreBoard
{
    private ScoreBoardStorage $scoreBoardStorage;

    public function __construct($scoreBoardStorage)
    {
        $this->scoreBoardStorage = $scoreBoardStorage;
    }

    /**
     * @throws \Exception
     */
    public function startGame(Game $game): Game
    {

        if ($game->awayTeam()->teamName() == $game->homeTeam()->teamName()) {
            throw new GameException("Teams can't play versus each other");
        }

        if ($game->awayTeam()->score() != 0 && $game->homeTeam()->score() != 0) {
            throw new InvalidScoreException("Game can't be started because of score should be starting from 0:0");
        }
        //todo: test this or refactor it
        if ($game->gameStatus() != GameStatus::NOT_STARTED) {
            throw new GameException("We can't create game with status other than NOT_STARTED");
        }

        if ($this->scoreBoardStorage->get($game) != null) {
            throw new GameExistsException("Game is already started");
        }

        $onGoingGame = GameMapper::toOngoingGame($game);

        $this->scoreBoardStorage->add($onGoingGame);
        return $onGoingGame;
    }

    public function updateGame(Game $game): string
    {
        if ($game->gameStatus() != GameStatus::ON_GOING) {
            throw new GameException("We can't update game with other status than ON_GOING");
        }

        if ($this->scoreBoardStorage->get($game) == null) {
            throw new GameExistsException("Game weren't started");
        }

    }

    public function finishGame(Game $game): string
    {
        // TODO: Implement finishGame() method.
    }

    public function summaryOfGames(): GameSummary
    {
        // TODO: Implement finishGame() method.
    }
}