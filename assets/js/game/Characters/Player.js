import Character from "./Character";
import {fetcher} from "../Fetcher";

export default class Player extends Character {
    constructor() {
        super();
        this.fetcher = fetcher;
        this.gender = USER.player.gender;
        this.pseudo = USER.username;
        this.inventory = []; // [new Inventory]
        this.currentAttack = 0; // id_attack
        this.currentXp = USER.player.xp;
        this.stucks = USER.player.stuck;
        this.activeBonus = []; // [new Item]

        this.hydrateInventory();
        this.fetcher.fetchData(this, "/assets/datas/Character.json", ["player"]);
        console.log(this);
    }

    hydrateInventory() {
        for (const item of USER.player.inventory) {
            item.equiped = false;
            this.inventory.push(item);
        }
    }
}