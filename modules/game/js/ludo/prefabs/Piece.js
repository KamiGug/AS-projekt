class Piece extends Phaser.GameObjects.Sprite {
    initialX
    initialY
    id
    boardSpaceId = -1
    constructor(scene, x, y, scale, color, id) {
        super(scene, x, y, 'piece-' + color);
        this.initialX = x;
        this.initialY = y;
        this.id = id;

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
        this.x = dragX
        this.y = dragY

    }

    dragStartCallback(pointer, dragX, dragY) {
        this.scene.children.bringToTop(this)
        console.log('this.id', this.id);
    }

    dragEndCallback(pointer, dragX, dragY, dropped) {
        this.x = this.initialX;
        this.y = this.initialY;
        // console.log('dropped', dropped)
    }

    dropCallback(pointer, dropZone) {
        //todo: check if allowed to drop here
        this.initialX = dropZone.x;
        this.initialY = dropZone.y;
        dropZone.removeHoverTint();
        dropZone.collideWithPlayerPiece()
        this.resetSpaceUnderPiece();
        this.boardSpaceId = dropZone.id;

        dropZone.setPlayerPieceId(this.id);
    }

    resetSpaceUnderPiece() {
        if (this.boardSpaceId !== -1 && this.boardSpaceId < 100) {
            this.scene.board.gameSpaces[this.boardSpaceId].setPlayerPieceId();
        }
    }

    dragEnterCallback(pointer, dropZone) {
        dropZone.setHoverTint();
    }

    dragLeaveCallback(pointer, dropZone) {
        dropZone.removeHoverTint();
    }
}