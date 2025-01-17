class Sidebar extends Phaser.GameObjects.Graphics {
    fontOptions = {
        fontFamily: 'serif',
        color: '#000',
        fontSize: 30,
    }

    constructor(scene) {
        super(scene, {
            lineStyle: {
                width: 10,
                color: 0x000000,
                alpha: 1,
            },
            fillStyle: {
                color: 0xffffff,
                alpha: 1,
            },
        });
        scene.add.existing(this);

        this.height = scene.sys.cameras.main.height;
        this.width = scene.sys.cameras.main.width - this.height;
        this.x = scene.sys.cameras.main.height + this.width / 2;
        this.y = this.height / 2;

        const nameBarHeight = -0.4 * this.height;
        const seatWrapperChatBarHeight = -0.15 * this.height;
        const chatDiceTrayBarHeight = 0.25 * this.height;


        console.log('width', scene.sys.cameras.main.width, 'width', scene.sys.cameras.main.height);
        console.log('x', this.x, 'y', this.y, 'height', this.height, 'width', this.width);

        this.fillRect(- this.width / 2, - this.height / 2, this.width, this.height);
        this.lineBetween(- this.width / 2, nameBarHeight, this.width / 2, nameBarHeight);
        let tmpHeight = (nameBarHeight + this.height / 2) / 2;

        this.name = scene.add.text(
            this.x - 0.48 * this.width,
            tmpHeight,
            gameVars.roomName === null || gameVars.roomName.length === 0 ? 'Room name :D' : gameVars.roomName,
            this.fontOptions
        ).setOrigin(0, 0.5);

        this.leaveButton = new LeaveButton(
            scene,
            this.x + 0.4 * this.width,
            tmpHeight,
            0.15 * this.width,
            0.7 * (nameBarHeight + this.y),
            this.fontOptions
        );

        this.lineBetween(- this.width / 2, seatWrapperChatBarHeight, this.width / 2, seatWrapperChatBarHeight);
        tmpHeight = seatWrapperChatBarHeight - nameBarHeight;
        this.seatWrapper = new SeatWrapper(
            scene,
            this.x,
            this.y + nameBarHeight + tmpHeight / 2,
            this.width,
            tmpHeight,
            this.fontOptions
        );

        this.lineBetween(- this.width / 2, chatDiceTrayBarHeight, this.width / 2, chatDiceTrayBarHeight);
        tmpHeight = chatDiceTrayBarHeight - seatWrapperChatBarHeight;
        this.chat = new Chat(
            scene,
            this.x,
            this.y + seatWrapperChatBarHeight  + tmpHeight / 2,
            this.width,
            tmpHeight,
            this.fontOptions
        )

        tmpHeight = 0.5 * this.height - chatDiceTrayBarHeight;
        this.diceTray = new DiceTray(
            scene,
            this.x,
            this.y + chatDiceTrayBarHeight + tmpHeight / 2,
            this.width,
            tmpHeight
        )
    }
}