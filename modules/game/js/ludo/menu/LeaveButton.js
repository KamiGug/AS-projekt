class LeaveButton extends Phaser.GameObjects.Graphics {
    modal = null;
    constructor(scene, x, y, width, height, fontOptions) {
        super(scene, {
            lineStyle: {
                width: 2,
                color: 0x000,
                alpha: 1,
            },
            fillStyle: {
                color: 0xffffff,
                alpha: 1,
            },
        });
        scene.add.existing(this);
        this.height = height;
        this.width = width ;
        this.x = x;
        this.y = y;
        this.fontOptions = fontOptions;
        this.fontOptions.fontSize = 20;


        this.fillRect(-this.width / 2, -this.height / 2, this.width, this.height)

        this.strokeRect(-this.width / 2, -this.height / 2, this.width, this.height)
        this.name = scene.add.text(
            this.x,
            this.y,
            'leave',
            this.fontOptions
        ).setOrigin(0.5, 0.5);

        this.setInteractive(new Phaser.Geom.Rectangle(-this.width / 2, -this.height / 2, this.width, this.height), Phaser.Geom.Rectangle.Contains);

        this.on('pointerdown', this.leaveModal);
    }

    leaveModal() {
        gameFunctions.quitGame(gameVars.gameId);
        // if (this.modal === null) {
        //     this.showLeaveModal();
        // }
        // console.log('leave');
    }

    showLeaveModal() {
        this.modal = this.scene.add.dom()
    }

    hideLeaveModal() {

    }
}