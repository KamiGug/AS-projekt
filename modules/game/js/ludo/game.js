class Game extends Phaser.Game {
    constructor(config) {
        super(config);
        // this.scene.add('LoadingScene', LoadingScene, true);
        this.scene.add('MainScene', MainScene, true);
    }
}

let startGame = () => {
    if (gameVars.game === null) {
        gameVars.game =  new Game({
            type: Phaser.AUTO,
            width: 1130,
            height: 720,
            // physics: {
            //     default: 'arcade',
            //     arcade: {
            //         gravity: { y: 0 },
            //         setBounds: true,
            //         debug: true
            //     }
            // },
            scale: {
                mode: Phaser.Scale.FIT,
                // autoCenter: Phaser.Scale.CENTER_BOTH
            },
            audio: {
                disableWebAudio: false,
            },
            dom: {
                createContainer: true,
            },
            parent: 'game-board',
        })
    }
}
