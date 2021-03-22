import Character from "./Character";

export default class Mob extends Character{
    constructor() {
        super();
        this.dropXp = 0;
        this.dropGold = 0;
        this.dropItems = [];
        this.type = 'mob'; // 'mob' - 'boss'
        this.ennemy = null; // 'mob' => id_mob || 'boss' => id_boss
        this.attacks = []; // [new Attack]
    }
}