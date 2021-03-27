import Tile from "./Tile";
import Sprite from "../Sprite";

export default class Board{
    constructor(rows, cols, tileSize){
        this.rows = rows;
        this.cols = cols;
        this.tileSize = tileSize;
        this.tiles = [];
        this.obstacles = [];
        this.isAvailable = [];
        this.doors = [];
        this.background = null;
        this.hydrateTiles();
    }

    /**
     * Create Tiles for each grid cell
     */
    async hydrateTiles(){
        for(let i = 0; i < this.cols; i++){
            for (let j = 0; j < this.rows; j++){
                this.tiles = [...this.tiles, new Tile(i * this.tileSize, j * this.tileSize, 0)];
            }
        }
    }

    drawBackground(){
        this.background = Sprite.drawBoard(this)
    }
}