import Character from "./Character";
import {fetcher} from "../Fetcher";
import Sprite from "../Sprite";

export default class Player extends Character {
    constructor(cols, tileSize) {
        super(cols, tileSize);
        this.gender = USER.player.gender;
        this.pseudo = USER.username;
        this.currentXp = USER.player.xp;
        this.stucks = USER.player.stuck;
        this.currentAttack = 0;
        this.inventory = [];
        this.activeBonus = [];
        this.hydrateInventory();
        this.images = {
            noGun : new Image(512, 512)
        }
        this.sprites = {
            noGun : new Sprite(this.images.noGun)
        }
        this.images.noGun.src = '/assets/image/sprites/male-void.png';
        fetcher.fetchData(this, '/assets/datas/Character.json', ["player"]);
    }

    hydrateInventory() {
        for (const item of USER.player.inventory) {
            item.equiped = false;
            this.inventory.push(item);
        }
    }

    // move(){
    //     this.position.x += 1
    //     this.sprites.noGun.drawSprite()
    // }

}