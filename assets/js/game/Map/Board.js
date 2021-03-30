// Board of a Room

import Tile from "./Tile";

export default class Board{
    constructor(rows, cols, tileSize, img, states){
        this.rows = rows;
        this.cols = cols;
        this.tileSize = tileSize;
        this.tiles = [];
        this.obstacles = [];
        this.isAvailable = [];
        this.doors = [];
        this.background = new Image();
        this.background.src = img;
        this.hydrateTiles(states);
    }

    /**
     * Create Tiles for each grid cell
     */
    async hydrateTiles(states){
        for (let i = 0; i < this.rows;  i++){
            for (let j = 0; j < this.cols;  j++){
            this.tiles = [...this.tiles, new Tile(j * this.tileSize, i * this.tileSize)];
            }
        }
        for (let i = 0; i < this.tiles.length; i++){
            this.tiles[i].state = states[i];
        }
    }

    draw(){
        CANVAS.width = this.cols * this.tileSize;
        CANVAS.height = this.rows * this.tileSize;

        CTX.beginPath();
        CTX.drawImage(this.background, 0, 0, CANVAS.width, CANVAS.height);
        CTX.closePath();

            // this.rect = CANVAS.getBoundingClientRect();
            //
            // CANVAS.addEventListener("click", (e) => {
            //     let x = Math.floor((e.clientX - this.rect.left) / 40);
            //     let y = Math.floor((e.clientY - this.rect.top) / 40);
            //     console.log(x, y);
            //     this.getCellState(x, y);
            // });
            //
            // for (let i = 0; i <this.tiles.length; i++){
            //     CTX.fillStyle = 'white'
            //     CTX.fillText(this.tiles[i].state, this.tiles[i].position.x + 20, this.tiles[i].position.y + 20)
            // }
    }

    getCellState(row, col) {
        for (const cell in this.tiles) {
            let index = row * (this.cols - 1) + col;
            let tileIndex = (this.tiles[cell].position.x / 40) * (this.cols - 1) + (this.tiles[cell].position.y / 40);
            if (tileIndex === index) {
                console.log(this.tiles[cell].state);
            }
        }
    }

}