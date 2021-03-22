// Board of a Room

export default class Board{

    constructor(doors){

        this.rows = 11;
        this.cols = 20;
        this.tileSize = 64; // 64 px
        this.tiles = []; // [new Tile]
        this.obstacles = []; // depends on tile state
        this.isAvailable = []; // depends on tile state
        this.doors = [];  // depends on tile state
        this.background = null; // new Sprite
    }
}