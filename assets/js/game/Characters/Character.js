// abstract class, only used to store common datas between player and mob
export default class Character {
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
        this.type = null;
        this.sprite.image.src = src;
        let x = this.randomInt(0, CANVAS.width)
        let y = this.randomInt(0, CANVAS.height)
        this.position = {
            x: x,
            y: y
        }
        this.direction = null;
        this.oldPosition = [null, null];
        this.attackDirection = null;
        this.setIndex(cols, tileSize)
        this.canFollow = 0;
    }

    /**
     * calculate and set the index of characters depends on his position;
     * @param cols // columns in the board
     * @param tileSize // size in px of each board cell
     */
    setIndex(cols, tileSize) {
        let col = Math.round(this.position.x / tileSize)
        let row = Math.round(this.position.y / tileSize)
        this.position.index = row * cols + col
    }

    /**
     * move the character and set the index
     * @param cols
     * @param tileSize
     */
    move(cols, tileSize) {
        if (this.direction !== null) {
            if (this.direction === 0) this.position.y -= this.moveSpeed
            if (this.direction === 1) this.position.x += this.moveSpeed
            if (this.direction === 2) this.position.y += this.moveSpeed
            if (this.direction === 3) this.position.x -= this.moveSpeed
        }
        this.setIndex(cols, tileSize)

        if (this.type === "mob" || this.type === "boss") {
            this.animateSprite();
        }
    }

    /**
     * Check collision with canvas borders
     * @param board
     */
    checkBoundsCollision(board) {

        let coordinates = this.getNextPosition(board);

        // borders bounds
        if (coordinates.col === -1 || coordinates.col === board.cols || coordinates.row === -1 || coordinates.row === board.rows) {
            if(this.type === "mob"){
                this.direction = this.randomInt(0,3)
            }else{
                this.direction = null;
            }
        }
    }

    followTarget(destX, destY, callback) {
        if (this.canFollow === 0){
            let distance = this.getDistance(destX, destY);
            if(distance <= 25 && this.canFollow === 0){
                this.direction = null;
                // callback();
            }
            else if(distance <= 120) {
                let posX = Math.round(this.position.x)
                if (!(posX <= destX + 5 && posX >= destX - 5)) {
                    if (this.position.x > destX) {
                        this.position.x--;
                        this.direction = 3;
                    } else if (this.position.x < destX) {
                        this.position.x++;
                        this.direction = 1;
                    }
                }

                let posY = Math.round(this.position.y)
                if (!(posY <= destY + 5 && posY >= destY - 5)) {
                    if (this.position.y > destY) {
                        this.position.y--;
                        this.direction = 0;
                    } else if (this.position.y < destY) {
                        this.position.y++;
                        this.direction = 2;
                    }
                }
            }
        } else {
            this.canFollow = this.canFollow - 1;
        }
    }

    getDistance(destX, destY) {
        let vector = [this.position.x - destX, this.position.y - destY]
        return Math.sqrt(Math.pow(vector[0], 2) + Math.pow(vector[1], 2));
    }

    showHp(currentHp) {
        let width;
        let rapport = (currentHp / this.hp) * 100;
        if (rapport < 30) {
            width = 5;
            CTX.fillStyle = 'red'
        } else if (rapport > 50) {
            width = 15;
            CTX.fillStyle = 'yellow';
        } else if (rapport > 75) {
            width = 30;
            CTX.fillStyle = 'green';
        }
        CTX.beginPath();
        CTX.rect(this.position.x + width / 2, this.position.y - 10, width, 5)

        CTX.fill();
    }

    animateSprite() {
        if (this.direction === 0) this.sprite.indexY = 0;
        if (this.direction === 1) this.sprite.indexY = 1;
        if (this.direction === 2) this.sprite.indexY = 2;
        if (this.direction === 3) this.sprite.indexY = 3;
    }

    /**
     * check collision with obstacles
     * @param board
     */
    checkObstaclesCollision(board) {
        let coordinates = this.getNextPosition(board);
        for (let i = 0; i < board.obstacles.length; i++){
            let obstacle = board.obstacles[i];
            if (coordinates.col === obstacle.position.x && coordinates.row === obstacle.position.y) {
                if (this.type === "mob") {
                    this.canFollow = 20;
                    let newDirection = this.randomInt(0, 3);
                    if (this.direction === newDirection) this.direction = (this.direction + 2) % 3;
                    else this.direction = newDirection;
                } else {
                    this.direction = null;
                }
            }
        }
    }

    /**
     * check character direction and return the nex position if the player keep going
     * @param tileSize
     * @returns {(number|*)[]|(*|number)[]|*[]}
     */
    checkNextPosition(tileSize) {
        switch (this.direction) {
            case 0 :
                return [this.position.x, this.position.y - (tileSize / 4)];
            case 1 :
                return [this.position.x + (tileSize / 4), this.position.y];
            case 2 :
                return [this.position.x, this.position.y + (tileSize / 4)];
            case 3 :
                return [this.position.x - (tileSize / 4), this.position.y];
            case null :
                return [this.position.x, this.position.y];
        }
    }

    /**
     * return the next position in coordinates col , row
     * @param board
     * @returns {{col: number, row: number}}
     */
    getNextPosition(board) {
        // get next cell where character is going on
        let nextPosition = this.checkNextPosition(board.tileSize)

        // get position like coordinates (col, row)
        let playerCol = Math.floor((nextPosition[0] + (board.tileSize / 2)) / board.tileSize);
        let playerRow = Math.floor((nextPosition[1] + (board.tileSize / 2)) / board.tileSize);

        return {col: playerCol, row: playerRow};
    }

    action() {
        switch (this.type) {
            case "player":
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

    voleurAtk() {

    }

    animation(cellSize, tileSize) {
        CTX.beginPath()
        CTX.drawImage(
            this.sprite.image,
            this.sprite.indexX * cellSize,
            this.sprite.indexY * cellSize,
            cellSize,
            cellSize,
            this.position.x,
            this.position.y,
            tileSize,
            tileSize
        )
        CTX.closePath();

        if (this.oldPosition[0] !== this.position.x || this.oldPosition[1] !== this.position.y) {
            this.sprite.indexX++;
        }
        if (this.sprite.indexX >= 9) this.sprite.indexX = 0;

        this.oldPosition[0] = this.position.x;
        this.oldPosition[1] = this.position.y;
    }

    randomInt(min, max) {
        min = Math.ceil(min);
        max = Math.floor(max);
        return Math.floor(Math.random() * (max - min + 1)) + min;
    }

    polynome2(exp0, exp1, exp2, x) {
        return exp2 * Math.pow(x, 2) + exp1 * x + exp0
    }
}