class MainScene extends BaseScene {
    dice
    board
    constructor () {
        super('MainScene')
    }

    preload () {
        super.preload()
        for (const texture of [
            'board',
            'circle-space',
            'rect-space',
            // 'rect-space-red',
            // 'rect-space-blue',
            // 'rect-space-green',
            // 'rect-space-yellow',
            'piece-blue',
            'piece-red',
            'piece-green',
            'piece-yellow',
        ]) {
            this.load.image(
                texture,
                `/web-assets/game/ludo/${texture}.webp`
            );
        }
        this.load.spritesheet('dice', '/web-assets/game/ludo/dice.webp', {
            frameWidth: 57,
            frameHeight: 57,
            spacing: 0.25,
        })
        // this.load.image(
        //     'board',
        //     '/web-assets/game/ludo/board.webp'
        // );

    }

    create () {
        // super.create();
        const boardCenter = Math.min(
            this.sys.cameras.main.width / 2,
            this.sys.cameras.main.height / 2,
        )
        this.sidebar = new Sidebar(this)
        this.dice = new Dice(this, boardCenter * 3, boardCenter)
        this.board = new Board(this, boardCenter, boardCenter)
        this.dice.handleVisibility();
        // this.children.bringToTop(this.dice);
        gameVars.refreshCallback = Board.setGameStateFromGameVars;
        gameVars.board = this.board;
        roomFunctions.hideLoader();
    }
}
