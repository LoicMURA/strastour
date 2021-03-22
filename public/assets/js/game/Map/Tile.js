
// Tile of a Board

export default class Tile{
    constructor(x, y, state) {
        this.position = {
            x: x,
            y: y
        }
        this.state = state;
        this.background = null; // new Sprite
    }
}