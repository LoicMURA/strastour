export default class Character{
    constructor(cols, tileSize) {
        this.hp = 0;
        this.atk = 0;
        this.def = 0;
        this.moveSpeed = 0;
        this.sprite = null; // new Sprite
        this.position = {
            x : 160,
            y : 80,
        }
        this.currentSprite = 0;
        this.direction = 0;
        this.setIndex(cols, tileSize)
    }

    setIndex(cols, tileSize){
        let col = Math.round(this.position.x / tileSize)
        let row = Math.round(this.position.y / tileSize)
        this.position.index =  row * cols + col
    }

    move(cols, tileSize) {
        if (this.direction !== null) {
            if (this.direction === 0) this.position.y -= this.moveSpeed
            if (this.direction === 1) this.position.x += this.moveSpeed
            if (this.direction === 2) this.position.y += this.moveSpeed
            if (this.direction === 3) this.position.x -= this.moveSpeed
        }
        this.setIndex(cols, tileSize)
    }

    checkNextPosition(tileSize) {
        switch (this.direction) {
            case 0 :
                return [this.position.x, this.position.y - (tileSize / 4)];
            case 1 :
                return [this.position.x + (tileSize / 4), this.position.y];
            case 2 :
                return [this.position.x,  this.position.y + (tileSize / 4)];
            case 3 :
                return [this.position.x - (tileSize / 4),  this.position.y];
            case null :
                return [this.position.x, this.position.y];
        }
    }

    action() {
        switch (this.type) {
            case "player":
                console.log(this.getCurrentItem().item);
                //attack is instantiated in Weapon class
                switch (this.getCurrentItem().item.id) {
                    //logic for each Weapon attack
                }
                break;
            case "mob":
                //attack is direrctly related to a Mob (no Weapon class between)
                switch (this.info.atk) {
                    //logic for each mob atk
                    case 1:
                        this.voleurAtk();
                        break;
                    case 2:
                        this.cyclistAtk();
                        break;
                    case 3:
                        this.piegonAtk();
                        break;
                    case 4:
                        this.nuageAtk();
                        break;
                }
                break;
            case "boss":
                //same as mob
                switch (this.info.atk) {
                    //logic for each boss atk (assuming they are different from mobs
                    case 1:
                        break;
                    case 2:
                        break;
                    case 3:
                        break;
                    case 4:
                        break;
                    case 5:
                        break;
                }
                break;
        }
    }

    polynome2(exp0, exp1, exp2, x)
    {
        return exp2 * Math.pow(x, 2) + exp1 * x + exp0
    }
}