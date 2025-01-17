<?php

namespace app\modules\game\models\base;

use app\models\exceptions\BadBoardStateException;
use app\modules\game\models\GameTypes;
use app\modules\game\models\Room;
use yii\web\HttpException;

/*
 * There are 4 different standards that need to be established per GameType
 * BoardState - represent the entire board state i.e. what someone might see if they look at a physical board
 * MoveList - in its simplest - a list of all possible moves,
 * otherwise carries also information needed for RNG (in client)
 * Move - how to represent a change in BoardState
 * History - how to represent a move in a repeatable manner
 * */

abstract class BaseGameType
{
    public static string $name = GameTypes::TYPE_BASE;
    public static int $maxPlayers = 0;
    public array $activePlayerIds = [];
    protected array $gameHistory;

    protected array $boardState;
    protected int $currentPlayerId;
    protected int $seed;
    protected Room|null $room;
    protected array|null $moveList = null;


    /**
     * @throws HttpException
     */
    public function __construct(
        int          $currentPlayerId,
        array        $activePlayerIds,
        Room|null    $room = null,
    )
    {
        $this->setBoardState($room->current_gamestate);
        $this->setGameHistory($room->game_history);
        $this->activePlayerIds = $activePlayerIds;
        $this->currentPlayerId = $currentPlayerId;
        $this->seed = $room->seed;
        $this->room = $room;
    }

    /**
     * @throws BadBoardStateException
     */
    public function setBoardState($boardState): void
    {
        if ($boardState === null || (is_string($boardState) && strlen($boardState) === 0) ) {
            $this->boardState = static::initialBoardState();
            return;
        }
        $boardState = static::decodeBoardState($boardState);
        if ($boardState === false) {
            throw new BadBoardStateException();
        }
        $this->boardState = $boardState;
    }

    public function getBoardState(): string
    {
        return json_encode($this->boardState);
    }

    public function setGameHistory(string|array $gameHistory): void
    {
        if (is_string($gameHistory)) {
            $gameHistory = static::decodeGameHistory($gameHistory);
        }
        $this->gameHistory = $gameHistory;
    }

    public function getGameHistory(): string
    {
        return json_encode($this->gameHistory);
    }

    public static abstract function initialBoardState(): array;

//    public static abstract function validateBoardState(string|array|null $boardState): bool;
    public static abstract function decodeBoardState(string|array|null $boardState) : array|false;

    public abstract function generatePossibleMoves(): void;

    public function getMoveList(): string
    {
        if ($this->moveList === null) {
            $this->generatePossibleMoves();
        }
        return json_encode($this->moveList);
    }

    public abstract function validateMove(string $move) : true;

    public abstract function handleMove(string $move) : void;

    public abstract static function decodeGameHistory(string $gameHistory) : array;

    public abstract function handlePlayerLeaveHistory($playerId, $changeRoomHistory = true) : void;

    public abstract function handlePlayerJoinHistory($playerId, $changeRoomHistory = true) : void;

    public function updateRoom() : bool
    {
        return $this->room->save();
    }
}
