export default class Character{
    constructor(cols, tileSize, src) {
        this.hp = 0;
        this.atk = 0;
        this.def = 0;
        this.moveSpeed = 0;
        this.sprite = {
            image: new Image(),
            indexX: 0,
            indexY: 0
        }
        this.sprite.image.src = src;
        this.position = {
            x : 160,
            y : 80,
        }
        this.direction = null;
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

    checkPosition(board, level){
        let nextPosition = this.checkNextPosition(board.tileSize)
        let playerX = Math.floor((nextPosition[0] + (board.tileSize / 2)) / board.tileSize);
        let playerY = Math.floor((nextPosition[1] + (board.tileSize / 2)) / board.tileSize);

        for (let i = 0; i < board.tiles.length; i++){
            let tileX = board.tiles[i].position.x;
            let tileY = board.tiles[i].position.y;

            if(playerX === tileX && playerY === tileY){
                if(board.tiles[i].state === 3){
                    let playerCoordinateX = Math.floor(this.position.x / board.tileSize)
                    let playerCoordinateY = Math.floor(this.position.y / board.tileSize)
                    if(playerCoordinateX === 0 && this.direction === 3 ||
                        playerCoordinateX === board.cols - 1 && this.direction === 1 ||
                        playerCoordinateY === board.rows - 1 && this.direction === 2 ||
                        playerCoordinateY === 0 && this.direction === 0
                    ){
                        level.switchRoom(board.tiles[i].door, this)
                    }
                }else if(board.tiles[i].state === 1){
                    this.direction = null;
                }
            }
        }
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