import Player from "../Characters/Player";
import Room from "./Room";
import Item from "../GameObjects/Item";
import {fetcher} from '../Fetcher';

export default class Level{
    constructor(id) {
        this.id = id;
        this.name = '';
        this.description = '';
        this.fetcher = fetcher;

        this.difficulty = 'easy';

        this.item = null ; // new Item
        this.places = {};
        this.rooms = [];
        this.player = new Player();
        this.isCleared = false;
        this.hydrateLevel()
            .then(() => this.hydrateRooms());
    }

    async hydrateLevel(){
        let fetchBDD = await fetch(`/jeu/${this.id}`);
        const levelDatas = await fetchBDD.json();
        this.fetcher.hydrateData(levelDatas, this);
    }

    async hydrateRooms(){
        console.log(this)
        for (const place in this.places) {
            let room = new Room(this.places[place].place.id);
            this.fetcher.hydrateData(this.places[place].place, room);
            this.rooms = [...this.rooms, room];
        }
    }
}