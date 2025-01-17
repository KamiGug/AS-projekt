class Seat extends Phaser.GameObjects.Graphics {
    taken

    static idToColorMap = {
        0: 'blue',
        1: 'red',
        2: 'green',
        3: 'yellow'
    }

    static colorToHexMap = {
        'blue': 0x0000ff,
        'red': 0xff0000,
        'green': 0x008000,
        'yellow': 0xffff00,
    }

    constructor(scene, x, y, width, height, number, fontOptions) {
        super(scene, {
            lineStyle: {
                width: 2,
                color: Seat.colorToHexMap[Seat.idToColorMap[number]],
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
        this.id = number
        this.fontOptions = fontOptions;
        this.fontOptions.fontSize = 20;

        this.strokeRect(-this.width / 2, -this.height / 2, this.width, this.height)
        this.name = scene.add.text(
            this.x,
            this.y,
            `${this.taken ? 'taken' : 'free'}: seat: ` + this.id,
            this.fontOptions
        ).setOrigin(0.5, 0.5);
        this.handleTaken();
        this.setInteractive(
            new Phaser.Geom.Rectangle(-this.width / 2, -this.height / 2, this.width, this.height),
            Phaser.Geom.Rectangle.Contains
        );
        this.on('pointerdown', ()=>{
            if (this.taken === false) {
                gameFunctions.changePlayerNumber(this.id);
            }
        })
    }

    handleTaken() {
        if (gameVars.takenSeats == null) {
            return;
        }
        this.taken = gameVars.takenSeats.some((el) => el === this.id);
        // console.log('seat.name', this.name)
        this.name.setText(`${this.taken ? 'taken' : 'free'}: seat: ` + this.id)
    }
}