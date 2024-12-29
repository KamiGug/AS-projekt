class MainScene extends BaseScene {
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
        this.board = new Board(this, boardCenter, boardCenter)
        roomFunctions.hideLoader();
        // this.board = this.add.image(0,0, );
        // this.board.setOrigin(0,0);
        // console.log(this.board);
        // console.log('MainScene');
        // this.game.scene.start('MainScene')
    }
}
