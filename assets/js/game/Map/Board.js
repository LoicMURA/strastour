// Board of a Room

import Tile from "./Tile";

export default class Board{
    constructor(rows, cols, tileSize, img, states, doors){
        this.rows = rows;
        this.cols = cols;
        this.tileSize = tileSize;
        this.tiles = [];
        this.obstacles = [];
        this.availables = [];
        this.doors = [];
        this.background = new Image();
        this.background.src = img;
        this.hydrateTiles(states, doors);
        CANVAS.width = this.cols * this.tileSize;
        CANVAS.height = this.rows * this.tileSize;
    }

    /**
     * Create Tiles for each grid cell
     */
    async hydrateTiles(states, doors){
        let index = 0;
        for (let i = 0; i < this.rows;  i++){
            for (let j = 0; j < this.cols;  j++){
                let tile = new Tile(j * this.tileSize, i * this.tileSize, states[i * this.cols + j])
                this.tiles = [...this.tiles, tile];
                if(tile.state === 3 ){
                    this.doors = [...this.doors, tile];
                    tile.door = doors[index]
                    index++
                }
                if(tile.state === 1){
                    this.obstacles = [...this.obstacles, tile];
                }
                if(tile.state === 0){
                    this.availables = [...this.availables, tile];
                }
            }
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
            let tileIndex = (this.tiles[cell].position.x / this.tileSize) * (this.cols - 1) + (this.tiles[cell].position.y / this.tileSize);
            if (tileIndex === index) {
                return this.tiles[cell].state;
            }
        }
    }

}