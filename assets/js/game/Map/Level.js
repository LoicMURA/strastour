import Room from "./Room";
import Item from "../GameObjects/Item";
import {fetcher} from '../Fetcher';

export default class Level{
    constructor(levelId, levelDatas) {
        this.id = levelId;
        this.datas = levelDatas;
        this.name = '';
        this.description = '';
        this.difficulty = 'easy';
        this.item = null ; // new Item
        this.places = [];
        this.rooms = [];
        this.isCleared = false;
    }

    async hydrateLevel(){
        let fetchBDD = await fetch(`/jeu/${this.id}`);
        const levelDatas = await fetchBDD.json();
        fetcher.hydrateData(levelDatas, this);
    }

    async hydrateRooms(datas){
        for (const place of this.places) {
            let placeShort = place.place;
            //room datas are empty
            let room = new Room(placeShort.id, this.difficulty, datas[placeShort.id]);
            fetcher.hydrateData(placeShort, room);
            this.rooms = [...this.rooms, room];
        }
    }
}