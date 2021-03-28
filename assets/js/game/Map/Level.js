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

    async hydrateRooms(boardDatas, characterDatas){
        for (const place of this.places) {
            let roomId = this.places.indexOf(place);
            let placeShort = place.place;
            let room;
            if(roomId !== this.places.length -1) {
                //room datas are empty
                room = new Room(placeShort.id, this.datas[placeShort.id], false, boardDatas, this.difficulty,  this.id, characterDatas);
            } else {
                //room hasBoss
                room = new Room(placeShort.id, this.datas[placeShort.id], true, boardDatas, this.difficulty, this.id, characterDatas);
            }
            fetcher.hydrateData(placeShort, room);
            this.rooms = [...this.rooms, room];
        }
        this.places = "hydrated";
    }
}