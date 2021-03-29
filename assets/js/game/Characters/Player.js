import Character from "./Character";
import {fetcher} from "../Fetcher";
import Bonus from "../GameObjects/Bonus";
import Weapon from "../GameObjects/Weapon";

export default class Player extends Character {
    constructor(itemDatas, weaponDatas, cols, tileSize) {
        super(cols, tileSize);
        this.lvl = 1;
        this.type = "player";
        this.gender = USER.player.gender;
        this.pseudo = USER.username;
        this.xp = USER.player.xp;
        //each values that needs to be selected based on classes properties
        this.current = {
            xp: 0,
            item: 0,
            sprite: {
                equiped: 0,
                indexX: 0,
                indexY: 0,
            },
            maxHp: 0,
            maxXp: 0
        };
        this.current.sprite.maxIndexX = this.current.sprite.equiped.limitX ?? 0
        this.stucks = USER.player.stuck;
        this.inventory = [];
        //items that are part of the inventory, must be displayed in the HUD
        this.equipement = [];
        //store if a weapon or bonus is being used, their relative bonuses to each of those items
        this.activeBonus = [];

        this.hydrateInventory(itemDatas, weaponDatas);
        this.setImages();

        this.mouvementController(cols, tileSize, 64);
    }

    //store data that needs to be calculated based on player's level
    upgradeToCurrentStats(){
        this.lvl = this.getLevel(this.xp);
        this.current.maxXp = this.getMaxXp(this.lvl);
        this.hp = this.getLife(this.lvl);
        this.current.maxHp = this.hp;
        this.atk = this.getAtk(this.lvl);
        this.def = this.getDef(this.lvl);
        this.current.xp = this.xp % 2000; //val needed to go to next lvl
    }

    getCurrentItem(){
        return this.equipement[this.current.item];
    }

    getLevel(xp)
    {
        let lvl = 1;
        while (92.32*Math.exp(0.0794*lvl) <= xp) {
            lvl++;
        }
        return lvl;
    }

    getMaxXp(lvl)
    {
        return Math.round(92.32 * Math.exp(0.0794*lvl));
    }

    getLife(lvl)
    {
        lvl = lvl - 1;
        return Math.floor(0.0401 * Math.pow(lvl, 2) + 5.6331 * lvl + 100);
    }

    getAtk(lvl)
    {
        lvl = lvl - 1;
        return Math.floor(0.0146 * Math.pow(lvl, 2) + 2.0704 * lvl + 20);
    }

    getDef(lvl)
    {
        lvl = lvl - 1;
        return Math.floor(0.0146 * Math.pow(lvl, 2) + 2.0704 * lvl + 20);
    }

    //use to update player's XP each time he wins some
    updateXp(gainXp) {
        this.xp += gainXp;
        this.current.xp = this.xp % this.current.maxXp;
    }

    /**
     * Set images for sprite animations
     */
    setImages(){
        this.images = {
            hand: {
                limitX: 9
            },
            dagger: {
                limitX: 9
            },
            sword: {
                limitX: 9
            },
            spear: {
                limitX: 9
            },
            bow: {
                limitX: 9
            },
        }
        this.images.hand.src = '/assets/image/sprites/character/male/hand.png';
        // let gender = this.gender === 'f' ? 'female' : 'male';
        for (const property in this.images) {
            if (this.images.hasOwnProperty(property)) {
                this.images[property].sprite = new Image();
                this.images[property].sprite.src = `/assets/image/sprites/character/male/${property}.png`;
            }
        }
    }

    hydrateInventory(bonusDatas, weaponDatas) {
        let index = 0;
        for (const itemData of USER.player.inventory) {
            // to delete later on
            itemData.equiped = true;
            if(index === 0) itemData.item.type.name = "health";
            if(index === 1) {
                // TRICHERIE MASSIVE
                itemData.item.type.name = "sword";
                itemData.item.type.id = 3;
            }
            if(index === 2) itemData.item.type.name = "speed";
            //
            let id = itemData.item.type.id;
            if(this.itemIsBonus(itemData.item)) {
                itemData.item = new Bonus(bonusDatas[id]);
            } else if (this.itemIsWeapon(itemData.item)) {
                itemData.item = new Weapon(weaponDatas[id]);
            }
            this.inventory.push(itemData);
            index++;
        }
        this.updateEquipement();
    }

    updateEquipement() {
        let index = 0;
        for(const itemData of this.inventory) {
            if(itemData.equiped){
                this.equipement[index] = itemData.item;
                index++;
            }
        }
    }

    itemIsBonus(item) {
        let names = ["health", "attack", "defense", "speed", "map"];
        let check = item.type.name;
        for(let i = 0; i < names.length; i++) {
            if(check === names[i]) {
                return true;
            }
        }
        return false;
    }

    itemIsWeapon(item) {
        // names stored in database for each weapon type
        let names = ["sword", "dagger", "spear", "bow"];
        let check = item.type.name;
        for(let i = 0; i < names.length; i++) {
            if(check === names[i]) {
                return true;
            }
        }
        return false;
    }

    mouvementController(cols, tileSize, cellSize){
        window.addEventListener('keydown', (e) => {
            if (e.code === "KeyW") this.direction = this.current.sprite.indexY = 0;
            if (e.code === "KeyD") this.direction = this.current.sprite.indexY = 1;
            if (e.code === "KeyS") this.direction = this.current.sprite.indexY = 2;
            if (e.code === "KeyA") this.direction = this.current.sprite.indexY = 3;
            this.move(cols, tileSize);
            this.animation(cellSize, tileSize);
        })
    }

    controlActions() {
        window.addEventListener('keydown', (e) => {
            if (e.code === "ArrowUp") {
            }
            if (e.code === "ArrowDown") {
            }
            if (e.code === "ArrowLeft") {
            }
            if (e.code === "ArrowRight") {
            }
        })
    }

    animation(cellSize, tileSize) {
        CTX.beginPath()
        CTX.drawImage(
            this.current.sprite.equiped,
            this.current.sprite.indexX * cellSize,
            this.current.sprite.indexY * cellSize,
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