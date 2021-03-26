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
        this.setImages();
        fetcher.fetchData(this, '/assets/datas/Character.json', ["player"]);

        this.mouvementController(cols, tileSize, 64);
    }

    /**
     * Set images for sprite animations
     */
    setImages(){
        this.images = {
            hand : new Image(576,576),
            dagger: new Image(576,576)
        }
        this.images.hand.src = '/assets/image/sprites/male-void.png';
    }

    hydrateInventory() {
        for (const item of USER.player.inventory) {
            item.equiped = false;
            this.inventory.push(item);
        }
    }

    mouvementController(cols, tileSize, cellSize){
        window.addEventListener('keydown', (e) => {
            switch (e.code){
                case "KeyW":
                    this.direction = 0
                    break;
                case "KeyD":
                    this.direction = 1
                    break;
                case "KeyS":
                    this.direction = 2
                    break;
                case "KeyA":
                    this.direction = 3
                    break;
            }
            this.move(cols, tileSize);
            this.animation(cellSize, tileSize);
        })
    }

    control(){

    }

    animation(cellSize, tileSize){
        CTX.beginPath()
        CTX.drawImage(
            this.currentSprite,
            this.indexX * cellSize,
            this.direction * cellSize,
            cellSize,
            cellSize,
            this.position.x,
            this.position.y,
            tileSize,
            tileSize
            )
        CTX.closePath();
        this.indexX++;
    }

}