import Character from "./Character";

export default class Player extends Character{
    constructor() {
        super();
        this.gender = '';
        this.pseudo = '';
        this.inventory = [] ; // [new Inventory]
        this.currentAttack = 0; // id_attack
        this.currentXp = 0;
        this.activeBonus = []; // [new Item]
    }
}