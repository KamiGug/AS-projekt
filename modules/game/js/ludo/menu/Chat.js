class Chat extends Phaser.GameObjects.DOMElement {
    constructor(scene, x, y, width, height, fontOptions) {
        super(scene, {
            lineStyle: {
                width: 2,
                color: 0x0000ff,
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

        this.createFromHTML(`
            <div style="width: ${this.width}px;height: ${this.height}px">
            chat placeholder
            todo: add chat!
</div>
        `)

        // this.fontOptions = fontOptions;
        // this.fontOptions.fontSize = 20;
    }
}