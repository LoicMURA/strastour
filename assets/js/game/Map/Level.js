import Player from "../Characters/Player";
import Room from "./Room";
import Item from "../GameObjects/Item";
import {fetcher} from '../Fetcher';

export default class Level{
    constructor(id) {
        this.id = id;
        this.fetcher = fetcher;
        this.name = '';
        this.difficulty = 'easy';
        this.rewards = null ; // new Item
        this.rooms = [];
        this.player = new Player();
        this.isCleared = false;

        this.hydrateLevel(id);
    }
    async hydrateLevel(id){
        let fetchBDD = await fetch(`/jeu/${id}`);
        const level = await fetchBDD.json();
        let fetchJSON = await fetch(`/assets/datas/levels/${id}.json`);
        await this.hydrateRooms(fetchJSON.json());
    }

    hydrateRooms(level){
        for (const rooms in level) {
            console.log(rooms);
            // let room = new Room();
            // room.ennemies = level[rooms].nbEnnemies
            // this.rooms = [...this.rooms, new Room(room)]
        }
    }
}