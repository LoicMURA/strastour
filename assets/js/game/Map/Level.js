import Room from "./Room";
import Item from "../GameObjects/Item";
import {fetcher} from '../Fetcher';

export default class Level{
    constructor(id) {
        this.id = id;
        this.name = '';
        this.description = '';
        this.difficulty = 'easy';
        this.item = null ; // new Item
        this.places = {};
        this.rooms = [];
        this.isCleared = false;
    }

    async hydrateLevel(){
        let fetchBDD = await fetch(`/jeu/${this.id}`);
        const levelDatas = await fetchBDD.json();
        fetcher.hydrateData(levelDatas, this);
    }

    async hydrateRooms(){
        for (const place in this.places) {
            let room = new Room(this.places[place].place.id);
            fetcher.hydrateData(this.places[place].place, room);
            this.rooms = [...this.rooms, room];
        }
    }
}