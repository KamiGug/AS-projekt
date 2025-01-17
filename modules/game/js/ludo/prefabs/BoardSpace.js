class BoardSpace extends Phaser.GameObjects.Zone {
    static TYPE_SQUARE = 'square'
    static TYPE_CIRCLE = 'circle'
    image
    type
    color = 'default';
    id
    playerPieceId = -1;

    //correspondingLength is either circleSpaceRadius or squareSpaceSide
    constructor(scene, x, y, scale, type, correspondingLength, id) {
        if (type !== BoardSpace.TYPE_SQUARE && type !== BoardSpace.TYPE_CIRCLE) {
            throw 'bad type!';
        }
        super(scene, x, y, correspondingLength, correspondingLength);
        this.image = scene.add.image(x, y, (type === BoardSpace.TYPE_SQUARE ? 'rect-space' : 'circle-space'));
        this.image.setScale(scale);
        this.type = type;
        this.setScale(scale);
        this.id = id;
        scene.add.existing(this);
        if (this.type === BoardSpace.TYPE_SQUARE) {
            this.setRectangleDropZone(correspondingLength, correspondingLength);
        }

        this.on('pointerdown', (pointer) => {
            console.log(this.id);
            console.log(gameVars)
        })
        // else {
        //     this.setCircleDropZone(correspondingLength);
        // }
    }

    setColor(color) {
        if (this.type !== BoardSpace.TYPE_SQUARE) {
            return;
        }
        this.color = color;
        this.applyColor();
    }

    applyColor() {
        switch (this.color) {
            case 'red':
                this.image.setTint(0xff0000);
                break;

            case 'blue':
                this.image.setTint(0x0000ff);
                break;

            case 'green':
                this.image.setTint(0x008000);
                break;

            case 'yellow':
                this.image.setTint(0xffff00);
                break;

            default:
                this.image.clearTint();
                break;
        }
    }

    setHoverTint() {
        this.image.setTint(0x0ac3e2);
    }

    removeHoverTint() {
        this.applyColor();
    }

    setPlayerPieceId(id = null) {
        this.playerPieceId = id ?? -1;
    }

    collideWithPlayerPiece() {
        if (this.playerPieceId !== -1) {
            this.scene.board.resetPlayerPieceById(this.playerPieceId);
        }
    }
}