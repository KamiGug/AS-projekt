class Piece extends Phaser.GameObjects.Sprite {
    initialX
    initialY
    id
    boardSpaceId = -1
    playerNumber = null
    constructor(scene, x, y, scale, color, id) {
        super(scene, x, y, 'piece-' + color);
        this.initialX = x;
        this.initialY = y;
        this.id = id;
        this.playerNumber = Piece.getPlayerNumber(this.id)
        this.setScale(scale);

        this.setInteractive({ draggable: true });
        this.on('drag', this.dragCallback);
        this.on('dragstart', this.dragStartCallback);
        this.on('dragend', this.dragEndCallback);
        this.on('drop', this.dropCallback);
        this.on('dragenter', this.dragEnterCallback);
        this.on('dragleave', this.dragLeaveCallback);

        scene.add.existing(this);
    }

    dragCallback(pointer, dragX, dragY) {
        // todo: check if it is this players turn
        if (gameVars.playerNumber !== this.getPlayerNumber()) {
            return;
        }
        this.x = dragX
        this.y = dragY

    }

    dragStartCallback(pointer, dragX, dragY) {
        if (gameVars.playerNumber !== this.getPlayerNumber()) {
            return;
        }
        this.scene.children.bringToTop(this)
        // console.log('this.getPlayerNumber()', this.getPlayerNumber());
    }

    dragEndCallback(pointer, dragX, dragY, dropped) {
        if (gameVars.playerNumber !== this.getPlayerNumber()) {
            return;
        }
        this.x = this.initialX;
        this.y = this.initialY;
        // console.log('dropped', dropped)
    }

    dropCallback(pointer, dropZone) {
        dropZone.removeHoverTint();
        if (
            gameVars.playerNumber !== this.getPlayerNumber()
            || this.getPlayerNumber() === Piece.getPlayerNumber(dropZone.playerPieceId)
            // || isValidMove() === false
        ) {
            this.x = this.initialX;
            this.y = this.initialY;
            return;
        }
        // dropZone.collideWithPlayerPiece()
        // this.resetSpaceUnderPiece();
        // this.boardSpaceId = dropZone.id;
        //
        // dropZone.setPlayerPieceId(this.id);
        this.setOnSpace(dropZone, true);
    }

    setOnSpace(space, byPlayer) {
        this.resetSpaceUnderPiece();
        this.boardSpaceId = space.id;
        space.setPlayerPieceId(this.id);
        if (byPlayer) {
            gameFunctions.makeMove(`${this.id}:${space.id}`);
            setTimeout((dice) => {
                dice.handleVisibility();
            }, 1000, this.scene.dice)
        } else {
            this.x = space.x;
            this.y = space.y;
            this.initialX = space.x;
            this.initialY = space.y;
        }
    }

    resetSpaceUnderPiece() {
        if (this.boardSpaceId === -1) return;
        if (this.boardSpaceId < 100) {
            this.scene.board.gameSpaces[this.boardSpaceId].setPlayerPieceId();
        } else {
            this.scene.board.homeSpaces[this.getPlayerNumber()][this.boardSpaceId - 100].setPlayerPieceId();
        }
    }

    dragEnterCallback(pointer, dropZone) {
        if (gameVars.playerNumber !== this.getPlayerNumber()) {
            return;
        }
        if (
            gameVars.moveList?.moves != null
            &&
            (gameVars.moveList?.moves[this.id]) === dropZone.id
        ) {
            dropZone.setHoverTint();
        }
    }

    dragLeaveCallback(pointer, dropZone) {
        if (gameVars.playerNumber !== this.getPlayerNumber()) {
            return;
        }
        dropZone.removeHoverTint();
    }

    getPlayerNumber() {
        return this.playerNumber;
    }

    static getPlayerNumber(id) {
        return  Math.floor(id / 4);
    }
}