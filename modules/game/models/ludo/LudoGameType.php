<?php

namespace app\modules\game\models\ludo;

use app\models\exceptions\IllegalMoveException;
use app\models\exceptions\PlayerNumbersMissmatchException;
use app\models\exceptions\UnacceptableHistoryFormatException;
use app\models\exceptions\UnacceptableMoveFormatException;
use app\modules\game\models\base\BaseGameType;
use app\modules\game\models\GameTypes;
use app\modules\game\models\Room;

// yard - starting spot for game pieces
// spaces refer to either yard spaces, board spaces or home (row) spaces
// board spaces refer to spaces between yard and home (row) spaces
// home row refers to final 4 spaces for each player that if filled mean victory for that player

class LudoGameType extends BaseGameType
{
    public static string $name = GameTypes::TYPE_LUDO;
    public static int $maxPlayers = 4;
    public static int $pieceCount = 4;
    public static int $boardSpacesPerPlayer = 12;
    public static int $boardSpacesCount = 4 * 12;
    public static int $homeSpacesStart = 100;
    public static int $homeSpacesRowLength = 4;

    public function __construct(
        int          $currentPlayerId,
        array        $activePlayerIds,
        Room|null    $room = null,
    )
    {
        parent::__construct($currentPlayerId, $activePlayerIds, $room);
        if (in_array($this->room->current_player_number, $this->activePlayerIds) === false) {
            $this->room->current_player_number =
                $this->getNextPlayerNumber($this->room->current_player_number);
            $this->room->save();
        }
    }

    public static function initialBoardState(): array
    {
        return [
            [-1,-1,-1,-1],
            [-1,-1,-1,-1],
            [-1,-1,-1,-1],
            [-1,-1,-1,-1],
        ];
    }

    public static function decodeBoardState(string|null|array $boardState): array|false
    {
        if (isset($boardState) === false) {
            return false;
        }
        if (is_string($boardState)) {
            $boardState = json_decode($boardState, true);
        }
        if (is_array($boardState) === false || count($boardState) !== static::$maxPlayers) {
            return false;
        }
        foreach ($boardState as $player) {
            if (count($player) !== static::$pieceCount) {
                return false;
            }
            foreach ($player as $piece) {
                if (static::isInvalidSpaceId($piece)) {
                    return false;
                }
            }
        }
        return $boardState;
    }

    public function generatePossibleMoves() : void
    {
        if (count($this->activePlayerIds) <= 1 || $this->currentPlayerIsHavingTurn() === false) {
            $this->moveList = [];
            return;
        }
        srand($this->seed);
        $playerPiecePositionCount = array_count_values($this->boardState[$this->currentPlayerId]);
        $pieceCountOut = static::$pieceCount - (isset($playerPiecePositionCount[-1]) ? $playerPiecePositionCount[-1] : 0);
        $diceRolls = [rand(1,6)];
        $moves = [];

        if ($pieceCountOut === 0) {

            for ($i = 1; $i < 3; $i++) {
                if ($diceRolls[$i - 1] === 6) {
                    break;
                }
                $diceRolls[$i] = rand(1,6);
            }
            if ($diceRolls[count($diceRolls) - 1] === 6) {
                $entrySpaceId = $this->currentPlayerId * static::$boardSpacesPerPlayer;
                $pieceId = $this->currentPlayerId * static::$pieceCount;
                for ($i = 0; $i < static::$pieceCount; $i++) {
                    $moves[$pieceId + $i] = $entrySpaceId;
                }
            }
            $this->moveList = [
                'diceRolls' => $diceRolls,
                'moves' => $moves,
            ];
            return;
        }


        $pieceId = $this->currentPlayerId * static::$pieceCount;
        $startSpaceId = $this->currentPlayerId * static::$boardSpacesPerPlayer;
        foreach ($this->boardState[$this->currentPlayerId] as $key => $pieceSpaceId) {
            if ($pieceSpaceId === -1) {
                if ($diceRolls[0] === 6) {
                    if ($this->collidesWithOwnPiece($startSpaceId) === false) {
                        $moves[$pieceId + $key] = $startSpaceId;
                    }
                }
                continue;
            }
            if ($pieceSpaceId >= 0 && $pieceSpaceId < static::$boardSpacesCount) {
                //calculate absolute distance from start space
                $newSpaceId = $pieceSpaceId - $startSpaceId;
                if ($newSpaceId < 0) {
                    $newSpaceId += static::$boardSpacesCount;
                }
                $newSpaceId += $diceRolls[0];

                //piece enters home row
                if ($newSpaceId >= static::$boardSpacesCount) {
                    if ($newSpaceId - static::$boardSpacesCount < static::$homeSpacesRowLength) {
                        $newSpaceId = static::$homeSpacesStart + $newSpaceId - static::$boardSpacesCount;
                        if ($this->collidesWithOwnPiece($newSpaceId) === false) {
                            $moves[$pieceId + $key] = $newSpaceId;
                        }
                    }
                } else {
                    $newSpaceId = ($newSpaceId + $startSpaceId) % static::$boardSpacesCount;
                    if ($this->collidesWithOwnPiece($newSpaceId) === false) {
                        $moves[$pieceId + $key] = $newSpaceId;
                    }
                }
                continue;
            }

            $pieceSpaceId += $diceRolls[0];
            if (
                $pieceSpaceId >= static::$homeSpacesStart
                && $pieceSpaceId < static::$homeSpacesStart + static::$homeSpacesRowLength
            ) {
                if ($this->collidesWithOwnPiece($pieceSpaceId) === false) {
                    $moves[$pieceId + $key] = $pieceSpaceId;
                }
            }
        }
        $this->moveList = [
            'diceRolls' => $diceRolls,
            'moves' => $moves,
        ];
    }

    private function collidesWithOwnPiece($spaceId) : bool
    {
        foreach ($this->boardState[$this->currentPlayerId] as $pieceSpaceId) {
            if ($pieceSpaceId === $spaceId) {
                return true;
            }
        }
        return false;
    }

    private function currentPlayerIsHavingTurn() : bool
    {
        return $this->room->current_player_number === $this->currentPlayerId;
//        $gameHistoryLength = count($this->gameHistory);
//        if ($gameHistoryLength > 0) {
//            // 0 - player id, 1 - piece id, 2 - space id
//            $lastPlayerMove = explode(':', $this->gameHistory[count($this->gameHistory) - 1]);
//            $lastPlayerMove[0] = (int) $lastPlayerMove[0];
//            $lastPlayerMove[1] = (int) $lastPlayerMove[1];
//            $lastPlayerMove[2] = (int) $lastPlayerMove[2];
//            $beforeMoveSpaceId = $this->boardState[$lastPlayerMove[0]]
//                [$lastPlayerMove[1] - $lastPlayerMove[0] * static::$pieceCount];
//            // if 6 was rolled
//            if (
//                $lastPlayerMove[2] - $beforeMoveSpaceId === 6
//                || $lastPlayerMove[2] + static::$boardSpacesCount - $beforeMoveSpaceId === 6
//            ) {
//                return $lastPlayerMove[0] === $this->currentPlayerId;
//            }
//
//            for ($i = 0; $i < count($this->activePlayerIds); $i++) {
//                if ($this->activePlayerIds[$i] > $lastPlayerMove[0]) {
//                    return $this->activePlayerIds[$i] === $this->currentPlayerId;
//                }
//            }
//            return $this->activePlayerIds[0] === $this->currentPlayerId;
//        } else {
//            return $this->currentPlayerId === 0;
//        }
    }

    public function getNextPlayerNumber($lastPlayerNumber) : int
    {
        for ($i = 0; $i < count($this->activePlayerIds); $i++) {
            if ($this->activePlayerIds[$i] > $lastPlayerNumber) {
                return $this->activePlayerIds[$i];
            }
        }
        return count($this->activePlayerIds) > 0 ? $this->activePlayerIds[0] : -1;
    }

    //call after appending move to history!!
    public function shouldHaveAnotherMove() : bool
    {
        if (
            isset($this->moveList) === false
            || count($this->moveList) === 0
            || $this->moveList['diceRolls'][count($this->moveList['diceRolls']) - 1] !== 6 )
        {
                return false;
        }
        $checked = 1;
        for ($i = count($this->gameHistory) - 2; $i >= 0; $i--) {
            //check if it is a leaving/joining note
            $id = explode(':',$this->gameHistory[$i])[0];
            if (is_numeric($id) === false) {
                continue;
            }
            if ((int) $id !== $this->currentPlayerId) {
                return true;
            }
            $checked++;
            if ($checked >= 3) {
                return false;
            }
        }
        return true;
    }

    /**
     * @throws UnacceptableMoveFormatException
     * @throws IllegalMoveException
     */
    public function validateMove(string $move): true
    {
        $matches = [];

        if (preg_match('/^(\d{1,2}):(\d{1,3}|-1)$/', $move, $matches) === false) {
            throw new UnacceptableMoveFormatException();
        }
        $pieceId = $matches[1];
        $spaceId = (int) $matches[2];
        if (
            (
                $spaceId !== -1 || $pieceId !== $this->currentPlayerId * static::$pieceCount
            ) && (
                $pieceId < $this->currentPlayerId * static::$pieceCount
                || $pieceId >= ($this->currentPlayerId + 1) * static::$pieceCount
                || static::isInvalidSpaceId($spaceId)
            )
        ) {
            throw new IllegalMoveException();
        }
        return true;
    }

    /**
     * @throws IllegalMoveException
     * @throws UnacceptableMoveFormatException
     * @throws \yii\db\Exception
     */
    public function handleMove(string $move): void
    {
        $this->validateMove($move);
        if ($this->moveList === null) {
            $this->generatePossibleMoves();
            if (count($this->moveList) === 0) {
                return;
            }
            if (count($this->moveList['moves']) === 0) {
                $this->appendMoveToHistory(
                    $this->currentPlayerId,
                    -1
                );
                $this->moveList = null;
                $this->room->game_history = $this->getGameHistory();
                $this->room->seed = time();
                $this->room->current_player_number = $this->getNextPlayerNumber($this->room->current_player_number);
                $this->room->save();
            }
        }

        $moveSplit = explode(':', $move);
        $moveSplit[0] = (int) $moveSplit[0];
        $moveSplit[1] = (int) $moveSplit[1];
        if (
            isset($this->moveList['moves'][$moveSplit[0]])
            && $this->moveList['moves'][$moveSplit[0]] === $moveSplit[1]
        ) {
            $this->setBoardStateAfterMove($moveSplit[0], $moveSplit[1]);
            $this->appendMoveToHistory($moveSplit[0], $moveSplit[1]);
            if ($this->shouldHaveAnotherMove() === false) {
                $this->room->current_player_number = $this->getNextPlayerNumber($this->room->current_player_number);
            }
            $this->moveList = null;
            $this->room->current_gamestate = $this->getBoardState();
            $this->room->game_history = $this->getGameHistory();
            $this->room->seed = time();
            $this->room->save();
        }
    }

    private function setBoardStateAfterMove(int $pieceId, int $spaceId) : void
    {
        $playerPieceId = $pieceId % static::$pieceCount;
        $collision = $this->collidesWithOtherPlayersPieces($spaceId);
        if ($collision !== false) {
            $this->boardState[$collision['playerId']][$collision['pieceId']] = -1;
        }
        $this->boardState[$this->currentPlayerId][$playerPieceId] = $spaceId;
    }

    private function collidesWithOtherPlayersPieces($spaceId) : array|false
    {
        for ($i = 0; $i < $this->currentPlayerId; $i++) {
            $result = $this->collideWithSpecificPlayersPieces($spaceId, $i);
            if ($result !== false) {
                return $result;
            }
        }
        for ($i = $this->currentPlayerId + 1; $i < static::$maxPlayers; $i++) {
            $result = $this->collideWithSpecificPlayersPieces($spaceId, $i);
            if ($result !== false) {
                return $result;
            }
        }
        return false;
    }

    private function collideWithSpecificPlayersPieces($spaceId, $playerId) : array|false
    {
        foreach ($this->boardState[$playerId] as $piecePlayerId => $pieceSpaceId) {
            if ($pieceSpaceId === $spaceId && $spaceId < 100) {
                return [
                    'playerId' => $playerId,
                    'pieceId' => $piecePlayerId,
                ];
            }
        }
        return false;
    }

    private function appendMoveToHistory(int $pieceId, int $spaceId) : void
    {
        $seed = $this->room->seed;
        $this->gameHistory[] = "$this->currentPlayerId:$pieceId:$spaceId:$seed";
    }


    private static function isInvalidSpaceId(int $id): bool
    {
        // assume ids 100 - 103 are home spaces and -1 is yard space
        return $id !== -1 && (
            $id < 0 || (
                $id >= static::$boardSpacesCount && (
                    $id < static::$homeSpacesStart || $id > static::$homeSpacesStart + static::$homeSpacesRowLength - 1
                )
            )
        );
    }

    /**
     * @throws UnacceptableHistoryFormatException
     */
    public static function decodeGameHistory(string $gameHistory): array
    {
        $decoded = json_decode($gameHistory, true) ?? [];
        //the history is inaccessible for a player to write directly so there may be no need to validate it
//        $matches = [];
//        foreach ($decoded as $move) {
//            //typical move notation in the history
//            if (preg_match('/^(\d):(\d{1,2}):(-?\d{1,3})$/', $move, $matches)) {
//                $playerId = (int) $matches[1];
//                $pieceId = (int) $matches[2];
//                $spaceId = (int) $matches[3];
//                if ($playerId < 0 || $playerId >= static::$maxPlayers ) {
//                    throw new IllegalMoveException();
//                }
//                if ($pieceId < $playerId * 4 || $pieceId >= $playerId * 4 + static::$pieceCount) {
//                    throw new IllegalMoveException();
//                }
//                if (static::isInvalidSpaceId($spaceId)) {
//                    throw new IllegalMoveException();
//                }
//            }
//            //player left or joined format
//            elseif (preg_match('/^[lj](\d{1})$/')) {
//                $playerId = (int) $matches[1];
//                if ($playerId < 0 || $playerId >= static::$maxPlayers ) {
//                    throw new IllegalMoveException();
//                }
//            }
//            else {
//                throw new UnacceptableHistoryFormatException();
//            }
//        }
        return $decoded;
    }

    public function handlePlayerLeaveHistory($playerId, $changeRoomHistory = true): void
    {
        $this->gameHistory[] = 'l:' . $playerId;
        if ($changeRoomHistory) {
            $this->room->game_history = $this->getGameHistory();
        }
    }

    public function handlePlayerJoinHistory($playerId, $changeRoomHistory = true): void
    {
        $this->gameHistory[] = 'j:' . $playerId;
        if ($changeRoomHistory) {
            $this->room->game_history = $this->getGameHistory();
        }
    }
}
