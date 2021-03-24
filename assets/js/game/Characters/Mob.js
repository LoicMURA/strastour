import Character from "./Character";
import {fetcher} from "../Fetcher";

export default class Mob extends Character{
    constructor() {
        super();
        this.fetcher = fetcher;
        this.dropXp = 0;
        this.dropGold = 0;
        this.dropItems = [];
        this.type = 'mob'; // 'mob' - 'boss'
        this.ennemy = null; // 'mob' => id_mob || 'boss' => id_boss
        this.attacks = []; // [new Attack]
        this.fetcher(this, "/datas/Character.json", this.id);
    }
}