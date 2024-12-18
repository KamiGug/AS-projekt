<?php

namespace app\modules\game\models\base;

use app\modules\game\models\ludo\LudoGameType;
use yii\web\HttpException;

/*
 * There are 3 different standards that need to be established per GameType
 * BoardState - represent the entire board state i.e. what someone might see if they look at a physical board
 * MoveList - in it's simplest - a list of all possible moves,
 * otherwise carries also information needed for RNG (in client)
 * Move - how to represent a change in BoardState
 * */

abstract class BaseGameType
{
    final const TYPE_BASE = 'base';
    final const TYPE_LUDO = 'ludo';

    final const GAME_TYPE_MAP = [
        self::TYPE_BASE => self::class,
        self::TYPE_LUDO => LudoGameType::class,
    ];

    public static string $name = self::TYPE_BASE;

    private string $boardState;


    public function __construct($arg)
    {
        $this->boardState = self::initialBoardState($arg);
    }

    /**
     * @throws HttpException
     */
    public function setBoardState($boardState) : void {
        if (self::validateBoardState($boardState)) {
            $this->boardState = $boardState;
        } else {
            throw new HttpException(406);
        }
    }

    public function getBoardState() : string
    {
        return $this->boardState;
    }

    public static abstract function initialBoardState(array|null $arg = null) : string;

    public static abstract function validateBoardState(string $boardState) : bool;

    public abstract function generatePossibleMoves() : string;

    public abstract function validateMove(string $move) : bool;

    public abstract function getBoardStateAfterMove(string $move) : string;
}
