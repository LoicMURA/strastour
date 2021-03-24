import Player from "../Characters/Player";
import Room from "./Room";

export default class Level{
    constructor(id) {
        this.difficulty = 'easy';
        this.rewards = null ; // new Item
        this.rooms = [];
        this.player = new Player();
        this.isCleared = false;

        this.hydrateLevel(id);
    }
    async hydrateLevel(id){
        let query = await fetch(`/assets/gameDatas/Levels.json`);
        let levels = await query.json();
        for (const level in levels) {
            if(level == id){
                this.hydrateRooms(levels[level]);
            }
        }
    }
    hydrateRooms(level){
        for (const rooms in level) {
            console.log(rooms)
            // let room = new Room();
            // room.ennemies = rooms.nbEnnemies
            // this.rooms = [...this.rooms, new Room(room)]
        }
    }
}