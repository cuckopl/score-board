<?php

namespace App\Domain;

use App\Contract\Dto\Game;
use App\Contract\Dto\GameStatus;
use App\Contract\Exception\GameException;
use App\Contract\Exception\GameExistsException;
use App\Contract\Exception\GameIsMissingException;
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

    public function startGame(Game $game): Game
    {

        if ($game->awayTeam()->teamName() == $game->homeTeam()->teamName()) {
            throw new GameException("Teams can't play versus each other");
        }

        if ($game->awayTeam()->score() != 0 && $game->homeTeam()->score() != 0) {
            throw new InvalidScoreException("Game can't be started because of score should be starting from 0:0");
        }
        if ($game->gameStatus() != GameStatus::NOT_STARTED) {
            throw new GameException("We can't create game with status other than NOT_STARTED");
        }

        if ($this->scoreBoardStorage->get($game) != null && $this->scoreBoardStorage->get(GameMapper::swapTeams($game)) != null) {
            throw new GameExistsException("Game is already started");
        }

        $onGoingGame = GameMapper::toOngoingGame($game);

        $this->scoreBoardStorage->add($onGoingGame);
        return $onGoingGame;
    }

    public function updateGame(Game $game): Game
    {

        $previousGame = $this->scoreBoardStorage->get($game);

        if ($game->gameStatus() != GameStatus::ON_GOING) {
            throw new GameException("We can't update game with other status than ON_GOING");
        }

        if ($previousGame == null) {
            throw new GameIsMissingException("Game weren't started");
        }

        if (
            $previousGame->awayTeam()->score() > $game->awayTeam()->score() ||
            $previousGame->homeTeam()->score() > $game->homeTeam()->score()
        ) {
            throw new InvalidScoreException("Score can't be lower than previous one");
        }

        if (
            $previousGame->awayTeam()->score() + 1 < $game->awayTeam()->score() ||
            $previousGame->homeTeam()->score() + 1 < $game->homeTeam()->score()
        ) {
            throw new InvalidScoreException("Score can be incremented only +1 per execution(we can't score 2 point in football)");
        }


        $this->scoreBoardStorage->update(GameMapper::updateScore($game));

        return $game;
    }

    public function finishGame(Game $game): Game
    {
        // TODO: Implement finishGame() method.
    }

    public function summaryOfGames(): GameSummary
    {
        // TODO: Implement finishGame() method.
    }
}