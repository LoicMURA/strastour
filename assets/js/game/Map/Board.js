// Board of a Room

import Tile from "./Tile";

export default class Board{
    rows = 11;
    cols = 20;
    tileSize = 40; // 64px
    constructor(id){
        this.tiles = [];
        this.obstacles = [];
        this.isAvailable = [];
        this.doors = [];
        this.background = null;
        this.hydrateTiles();
        this.draw();
    }

    /**
     * Create Tiles for each grid cell
     */
    hydrateTiles(){
        for(let i = 0; i < this.cols; i++){
            for (let j = 0; j < this.rows; j++){
                this.tiles = [...this.tiles, new Tile(i * this.tileSize, j * this.tileSize, 0)];
            }
        }
    }

    /**
     * Draw the board
     */
    draw(){
        CANVAS.width = this.cols * this.tileSize;
        CANVAS.height = this.rows * this.tileSize;

        CTX.beginPath();
        for (const tileKey in this.tiles) {
            CTX.strokeRect(this.tiles[tileKey].position.x, this.tiles[tileKey].position.y, this.tileSize, this.tileSize);
        }
        CTX.closePath();
    }
}