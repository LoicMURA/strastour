import Character from "./Character";

export default class Player extends Character{
    constructor() {
        super();
        this.gender = '';
        this.pseudo = USER.username;
        this.inventory = [] ; // [new Inventory]
        this.currentAttack = 0; // id_attack
        this.currentXp = USER.player.xp;
        this.stucks = USER.player.stuck;
        this.activeBonus = []; // [new Item]
    }
}