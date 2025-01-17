class DiceTray extends Phaser.GameObjects.Graphics {
    constructor(scene, x, y, width, height) {
        super(scene, {
            lineStyle: {
                width: 2,
                color: 0x00ff00,
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


        this.strokeRect(-this.width / 2, -this.height / 2, this.width, this.height)
    }

    getRandomPositionInside(deadzone) {
        return {
            x: this.x + (Math.random() - 0.5) * (this.width - 2 * deadzone),
            y: this.y + (Math.random() - 0.5) * (this.height - 2 * deadzone),
        };
    }
}