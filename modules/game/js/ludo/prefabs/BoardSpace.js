class BoardSpace extends Phaser.GameObjects.Sprite {
    static TYPE_SQUARE = 'square'
    static TYPE_CIRCLE = 'circle'
    type
    constructor(scene, x, y, scale, type) {
        if (type !== BoardSpace.TYPE_SQUARE && type !== BoardSpace.TYPE_CIRCLE) {
            throw 'bad type!';
        }
        super(scene, x, y, type === BoardSpace.TYPE_SQUARE ? 'rect-space' : 'circle-space');
        this.type = type;
        this.setScale(scale);
        scene.add.existing(this);

    }

    setColor(color) {
        if (this.type !== BoardSpace.TYPE_SQUARE) {
            return;
        }
        this.setTexture('rect-space-' + color);
    }
}