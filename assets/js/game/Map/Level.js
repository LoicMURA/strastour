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

    async hydrateLevel() {
        if (this.id !== 0) {
            let fetchBDD = await fetch(`/jeu/${this.id}`);
            const levelDatas = await fetchBDD.json();
            fetcher.hydrateData(levelDatas, this);
        } else {
            this.places[0] = {
                place: {
                    id: 0,
                    name: "Office du tourisme",
                    description: "Une safe zone pour les touristes en vadrouille, les armes ne sont pas autorisées ici",
                    address: "Le néant abyssal (Strasbourg)",
                }
            }
        }
    }

    async hydrateRooms(boardDatas, characterDatas){
        for (const place of this.places) {
            let roomId = this.places.indexOf(place);
            let placeShort = place.place;
            let hasBoss;
            //can be modified if needed to specify datas for room 0 (game home)
            hasBoss = roomId === this.places.length - 1;
            let room = new Room(placeShort.id, this.datas[placeShort.id], hasBoss, boardDatas);
            fetcher.hydrateData(placeShort, room);
            this.rooms = [...this.rooms, room];
        }
    }

    isSafe(characterDatas, playerLvl) {
        if (this.id !== 0) {
            this.currentRoom.hydrateEnemies(this.difficulty, this.id, characterDatas, playerLvl);
        }
    }

    switchRoom(door, player){
        // if(door.room === -1){
        //     this.currentRoom = controlleur.accueil
        // }else{
        // }
        console.log(door);
        this.currentRoom = this.rooms[door.room];
        let nextDoor = this.currentRoom.board.doors[door.door];
        player.position.x = nextDoor.position.x * this.currentRoom.board.tileSize
        player.position.y = nextDoor.position.y * this.currentRoom.board.tileSize
    }
}