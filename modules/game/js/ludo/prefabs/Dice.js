class Dice extends Phaser.GameObjects.Sprite {
    result
    forcedResult
    forcedResultIterator
    forcedDiceRolls = [];
    constructor(scene, x, y) {
        super(scene, x, y, 'dice');
        this.roll(true, false);
        scene.add.existing(this);
        this.setRandomPosition();
        this.setInteractive();
        this.resetForcedResults();
        this.diceTrayDeadZone = 57 * 0.5;
        this.handleVisibility(false);

        this.on('pointerup', () => {
            if (this.roll() !== false) {
                this.handleVisibility();
            }
        })
    }

    roll(fast = false, forceResult = true) {
        if (fast ===  false
            && (
                this.forcedDiceRolls.length === 0
                || this.forcedDiceRolls.length <= this.forcedResultIterator
            )
        ) {
            return false;
        }
        let rolled;
        if (forceResult) {
            this.handleForcedResults();
        }
        rolled = this.forcedResult ?? Math.floor(Math.random() * 6)
        this.partialRoll(fast === false ? Math.floor(Math.random() * 10) + 10 : 0, rolled);

        this.result = ++rolled
        return rolled
    }

    partialRoll(max, finalResult, i = 0) {
        if (i < max) {
            setTimeout((self, i, max) => {
                this.setFrame(Math.floor(Math.random() * 6));
                self.partialRoll(max, finalResult, i + 1);
            }, Math.floor(Math.random() * 50) + 50, this, i, max)
        } else {
            this.setFrame(finalResult);
        }
    }

    handleForcedResults() {
        if (
            gameVars.moveList.diceRolls == null
            || gameVars.moveList.moves == null
            || gameVars.moveList.diceRolls.length <= this.forcedResultIterator
        ) {
            return;
        }
        if (
            gameVars.moveList.moves.length === 0
            && gameVars.moveList.diceRolls.length - 1 === this.forcedResultIterator
        ) {
            gameFunctions.makeMove(`${gameVars.playerNumber * 4}:-1`);
        }
        this.forcedResult = gameVars.moveList.diceRolls[this.forcedResultIterator] - 1;
        this.forcedResultIterator += 1;
    }

    resetForcedResults() {
        if (
            this.forcedDiceRolls.length === gameVars.moveList?.diceRolls?.length
            && this.forcedDiceRolls.every((diceRoll, i) => {
                return diceRoll === gameVars.moveList?.diceRolls[i];
            })
        ) { 
            return;
        }
        this.forcedResultIterator = 0;
        this.forcedDiceRolls = gameVars.moveList?.diceRolls ?? [];
        this.forcedResult = null;
        this.handleVisibility(false);
        if (this.forcedDiceRolls.length > 0) {
            this.visible = true;
        }
    }

    handleVisibility (randomizePosition = true) {
        if (this.forcedDiceRolls?.length > 0) {
            this.setVisible(randomizePosition)
        } else {
            this.visible = false;
        }
    }

    setVisible (randomizePosition = true) {
        this.visible = true;
        if (randomizePosition) {
            const position = this.scene.sidebar.diceTray.getRandomPositionInside(this.diceTrayDeadZone);
            this.x = position.x;
            this.y = position.y;
        }
        this.scene.children.bringToTop(this);
    }

    setRandomPosition () {
        const position = this.scene.sidebar.diceTray.getRandomPositionInside(this.diceTrayDeadZone);
        this.x = position.x;
        this.y = position.y;
    }
}
