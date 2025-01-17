class SeatWrapper extends Phaser.GameObjects.Graphics {
    seats = new Array(4);
    constructor(scene, x, y, width, height, fontOptions) {
        super(scene, {
            lineStyle: {
                width: 2,
                color: 0xff0000,
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

        console.log('y', this.y, 'height', this.height)

        this.strokeRect(-this.width / 2, -this.height / 2, this.width, this.height)

        const seatWidth = this.width * 0.35;
        const seatHeight = this.height * 0.35;
        const seatOffsetX = this.width * 0.07 + seatWidth / 2;
        const seatOffsetY = this.height * 0.07 + seatHeight / 2;
        let xj = -1;
        let yj = 1;
        for (let j = 0; j < 4; j++) {
            if (j % 2) {
                xj *= -1;
            } else {
                yj *= -1;
            }
            this.seats[j] = new Seat(
                scene,
                this.x + (xj * seatOffsetX),
                this.y + (yj * seatOffsetY),
                seatWidth,
                seatHeight,
                j,
                this.fontOptions
            );
        }


    }
}