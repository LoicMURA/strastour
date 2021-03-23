// Board of a Room

import Tile from "./Tile";

export default class Board{
    rows = 11;
    cols = 20;
    tileSize = 64; // 64px
    constructor(id_level, id_room){
        this.tiles = []; // [new Tile]
        this.obstacles = []; // depends on tile state
        this.isAvailable = []; // depends on tile state
        this.doors = [];  // depends on tile state
        this.background = null; // new Sprite
    }
    async hydrateTiles(id_level, id_room){
        let query = await fetch(`/data/levels`);
        let datas = await query.json();
        for(let i = 0; i < this.rows; i++){
            for (let j = 0; j < this.cols; j++){
                this.tiles = [...this.tiles, new Tile(i * this.tileSize, j * this.tileSize, 0)];
            }
        }
    }
    draw(ctx){

    }
}