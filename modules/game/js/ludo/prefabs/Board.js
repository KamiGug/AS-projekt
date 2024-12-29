class Board extends Phaser.GameObjects.Sprite {
    squareSpaceSide = 15;
    circleSpaceRadius = 8;
    circleSpaceOffset = 10;
    yardCircleRadius = 30;
    playerPieceRadius = 7;
    boardRim = 10;
    boardSide = 215;
    unit

    gameSpaces = new Array(12 * 4);
    yardSpaces = new Array(4 * 4);
    homeSpaces = [new Array(4), new Array(4), new Array(4), new Array(4)];
    playerPieces = new Array(4 * 4);

    constructor(scene, x, y) {
        super(scene, x, y, 'board');
        this.gameScale = Math.min(
            scene.sys.cameras.main.width / this.width,
            scene.sys.cameras.main.height / this.height
        );
        this.scene = scene;
        this.setScale(this.gameScale);
        this.unit = (this.width * this.gameScale / this.boardSide)
        this.squareSpaceSide *= this.unit;
        this.circleSpaceRadius *= this.unit;
        this.circleSpaceOffset *= this.unit;
        this.yardCircleRadius *= this.unit;
        this.playerPieceRadius *= this.unit;
        this.boardRim *= this.unit;
        this.boardSide *= this.unit;

        scene.add.existing(this);
        this.initBoardSpaces();
        this.initYardSpaces();
        this.initPlayerPieces();
    }

    initBoardSpaces() {
        let i = 0;
        for (let j = 0; j < 6; j++) {
            this.gameSpaces[i] = new BoardSpace(
                this.scene,
                this.x + (-6 + j) * this.squareSpaceSide,
                this.y - this.squareSpaceSide,
                this.gameScale,
                BoardSpace.TYPE_SQUARE,
                this.squareSpaceSide,
                i
            );
            i++;
        }
        for (let j = 0; j < 5; j++) {
            this.gameSpaces[i] = new BoardSpace(
                this.scene,
                this.x - this.squareSpaceSide,
                this.y + (-2 - j) * this.squareSpaceSide,
                this.gameScale,
                BoardSpace.TYPE_SQUARE,
                this.squareSpaceSide,
                i
            );
            i++;
        }
        this.gameSpaces[i++] = new BoardSpace(
            this.scene,
            this.x,
            this.y - 6 * this.squareSpaceSide,
            this.gameScale,
            BoardSpace.TYPE_SQUARE,
            this.squareSpaceSide,
            i
        );
        for (let j = 0; j < 6; j++) {
            this.gameSpaces[i] = new BoardSpace(
                this.scene,
                this.x + this.squareSpaceSide,
                this.y + (-6 + j) * this.squareSpaceSide,
                this.gameScale,
                BoardSpace.TYPE_SQUARE,
                this.squareSpaceSide,
                i
            );
            i++;
        }
        for (let j = 0; j < 5; j++) {
            this.gameSpaces[i] = new BoardSpace(
                this.scene,
                this.x + (2 + j) * this.squareSpaceSide,
                this.y - this.squareSpaceSide,
                this.gameScale,
                BoardSpace.TYPE_SQUARE,
                this.squareSpaceSide,
                i
            );
            i++;
        }
        this.gameSpaces[i++] = new BoardSpace(
            this.scene,
            this.x + 6 * this.squareSpaceSide,
            this.y,
            this.gameScale,
            BoardSpace.TYPE_SQUARE,
            this.squareSpaceSide,
            i
        );
        for (let j = 0; j < 6; j++) {
            this.gameSpaces[i] = new BoardSpace(
                this.scene,
                this.x + (6 - j) * this.squareSpaceSide,
                this.y + this.squareSpaceSide,
                this.gameScale,
                BoardSpace.TYPE_SQUARE,
                this.squareSpaceSide,
                i
            );
            i++;
        }
        for (let j = 0; j < 5; j++) {
            this.gameSpaces[i] = new BoardSpace(
                this.scene,
                this.x + this.squareSpaceSide,
                this.y + (2 + j) * this.squareSpaceSide,
                this.gameScale,
                BoardSpace.TYPE_SQUARE,
                this.squareSpaceSide,
                i
            );
            i++;
        }
        this.gameSpaces[i++] = new BoardSpace(
            this.scene,
            this.x,
            this.y + 6 * this.squareSpaceSide,
            this.gameScale,
            BoardSpace.TYPE_SQUARE,
            this.squareSpaceSide,
            i
        );
        for (let j = 0; j < 6; j++) {
            this.gameSpaces[i] = new BoardSpace(
                this.scene,
                this.x - this.squareSpaceSide,
                this.y + (6 - j) * this.squareSpaceSide,
                this.gameScale,
                BoardSpace.TYPE_SQUARE,
                this.squareSpaceSide,
                i
            );
            i++;
        }
        for (let j = 0; j < 5; j++) {
            this.gameSpaces[i] = new BoardSpace(
                this.scene,
                this.x - (2 + j) * this.squareSpaceSide,
                this.y + this.squareSpaceSide,
                this.gameScale,
                BoardSpace.TYPE_SQUARE,
                this.squareSpaceSide,
                i
            );
            i++;
        }
        this.gameSpaces[i] = new BoardSpace(
            this.scene,
            this.x - 6 * this.squareSpaceSide,
            this.y,
            this.gameScale,
            BoardSpace.TYPE_SQUARE,
            this.squareSpaceSide,
            i
        );

        this.gameSpaces[0].setColor('blue');
        this.gameSpaces[12].setColor('red');
        this.gameSpaces[24].setColor('green');
        this.gameSpaces[36].setColor('yellow');
    }

    initYardSpaces() {
        let currentYardSpace = 0;
        let xi = -1;
        let yi = 1;
        for (let i = 0; i < 4; i++) {
            if (i % 2) {
                xi *= -1;
            } else {
                yi *= -1;
            }
            let xj = -1;
            let yj = 1;
            for (let j = 0; j < 4; j++) {
                if (j % 2) {
                    xj *= -1;
                } else {
                    yj *= -1;
                }
                this.yardSpaces[currentYardSpace++] = new BoardSpace(
                    this.scene,
                    this.x + (xi * 4 * this.squareSpaceSide) + (xj * this.circleSpaceOffset),
                    this.y + (yi * 4 * this.squareSpaceSide) + (yj * this.circleSpaceOffset),
                    this.gameScale,
                    BoardSpace.TYPE_CIRCLE,
                    this.circleSpaceRadius,
                    -1
                )
            }
        }
    }

    initPlayerPieces() {
        for (let i = 0; i < 4; i++) {
            this.playerPieces[i] = new Piece(
                this.scene,
                this.yardSpaces[i].x,
                this.yardSpaces[i].y,
                this.gameScale,
                'blue',
                i
            );
            this.playerPieces[i + 4] = new Piece(
                this.scene,
                this.yardSpaces[i + 4].x,
                this.yardSpaces[i + 4].y,
                this.gameScale,
                'red',
                i + 4
            );
            this.playerPieces[i + 8] = new Piece(
                this.scene,
                this.yardSpaces[i + 8].x,
                this.yardSpaces[i + 8].y,
                this.gameScale,
                'green',
                i + 8
            );
            this.playerPieces[i + 12] = new Piece(
                this.scene,
                this.yardSpaces[i + 12].x,
                this.yardSpaces[i + 12].y,
                this.gameScale,
                'yellow',
                i + 12
            );
        }
    }

    resetPlayerPieceById(id) {
        this.playerPieces[id].resetSpaceUnderPiece();
        this.playerPieces[id].boardSpaceId = -1;
        this.playerPieces[id].setPosition(this.yardSpaces[id].x, this.yardSpaces[id].y);
    }
}