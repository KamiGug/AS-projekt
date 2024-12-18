<?php

namespace app\modules\game\models\ludo;

use app\modules\game\models\base\BaseGameType;

/*
 * BoardState - csv of game piece positions on the board; each row represents a player. 1-40 is actual gameboard
 * -1 is "shelve" (inactive gamepieces),
 * each player starts
 *
 * */

class LudoGameType extends BaseGameType
{

    public static function initialBoardState(?array $arg = null): string
    {
        // TODO: Implement initialBoardState() method.
        return '';
    }

    public static function validateBoardState(string $boardState): bool
    {
        // TODO: Implement validateBoardState() method.
        return true;
    }

    public function generatePossibleMoves(): string
    {
        // TODO: Implement generatePossibleMoves() method.
        return '';
    }

    public function validateMove(string $move): bool
    {
        // TODO: Implement validateMove() method.
        return true;
    }

    public function getBoardStateAfterMove(string $move): string
    {
        // TODO: Implement getBoardStateAfterMove() method.
        return '';
    }
}
