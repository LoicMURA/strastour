export default class Character{
    constructor(cols, tileSize) {
        this.hp = 0;
        this.atk = 0;
        this.def = 0;
        this.moveSpeed = 0;
        this.sprite = null; // new Sprite
        this.position = {
            x : 120,
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

    move(cols, tileSize){
        if(this.direction === 0) this.position.y -= this.moveSpeed
        if(this.direction === 1) this.position.x += this.moveSpeed
        if(this.direction === 2) this.position.y += this.moveSpeed
        if(this.direction === 3) this.position.x -= this.moveSpeed
        this.setIndex(cols, tileSize)
    }
}