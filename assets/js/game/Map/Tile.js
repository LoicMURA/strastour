
// Tile of a Board

export default class Tile{
    constructor(x, y, state) {
        this.position = {
            x: x / 40,
            y: y / 40
        }
        this.state = state;
        this.door = null;
    }


}