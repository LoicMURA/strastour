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
                image: new Image(),
                indexX: 0,
                indexY: 0,
            },
            maxHp: 0,
            maxXp: 0
        };
        // let gender = this.gender === 'f' ? 'female' : 'male';
        this.current.sprite.image.src = `/assets/image/sprites/character/male/hand.png`;
        this.stucks = USER.player.stuck;
        this.inventory = [];
        //items that are part of the inventory, must be displayed in the HUD
        this.equipement = [];
        //store if a weapon or bonus is being used, their relative bonuses to each of those items
        this.activeBonus = [];
        this.oldPosition = [null, null];
        this.hydrateInventory(itemDatas, weaponDatas);

        this.mouvementController(cols, tileSize, 64);
    }

    // --- Hydrating class datas ---
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
            bonusDatas[id].id = id
            weaponDatas[id].id = id
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

    //store data that needs to be calculated based on player's level
    upgradeToCurrentStats(){
        this.lvl = this.getLevel(this.xp);
        this.current.maxXp = this.getMaxXp(this.lvl);
        this.hp = this.getPlayerLife(this.lvl);
        this.current.maxHp = this.hp;
        this.atk = this.getPlayerAtk(this.lvl);
        this.def = this.getPlayerDef(this.lvl);
        this.current.xp = this.xp % 2000; //val needed to go to next lvl
    }
    // -----------------------------------


    // --- Getting current class datas ---
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

    getPlayerLife(lvl)
    {
        lvl = lvl - 1;
        return Math.floor(this.polynome2(100, 5.6331, 0.0401, lvl ))
    }

    getPlayerAtk(lvl)
    {
        lvl = lvl - 1;
        return Math.floor(this.polynome2(20, 2.0704, 0.0146, lvl ))
    }

    getPlayerDef(lvl)
    {
        lvl = lvl - 1;
        return Math.floor(this.polynome2(20, 2.0704, 0.0146, lvl ))
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
    //-----------------------------

    // --- Updating class datas ---
    updateEquipement() {
        let index = 0;
        for(const itemData of this.inventory) {
            if(itemData.equiped){
                this.equipement[index] = itemData.item;
                index++;
            }
        }
    }

    earnMoney(stucks)
    {
        this.stucks += stucks * 1
    }

    spendMoney(stucks)
    {
        if (this.stucks - stucks > 0) {
            this.stucks -= stucks
            return true
        }
        return false
    }
    //----------------------------------


    updateStucks(stucks)
    {
        let path = window.location.href.replace('jeu', 'profile/stuck-update');
        let datas = new FormData();
        datas.append('stucks', stucks);
        fetch(path, {
            method: "POST",
            body: datas
        })
    }

    updateXp(xp)
    {
        let path = window.location.href.replace('jeu', 'profile/xp-update');
        let datas = new FormData();
        datas.append('xp', xp);
        fetch(path, {
            method: "POST",
            body: datas
        })
    }

    // precise each items that have been modified, even those who aren't possessed anymore
    updateInventory(inventory)
    {
        let path = window.location.href.replace('jeu', 'profile/inventory-update')
        let datas = new FormData()
        let i = 1;
        for (let item of inventory) {
            // item au format {id: 1, quantity: 1, quality:100}
            datas.append(`inventory-${i}`, JSON.stringify(item))
            i++
        }
        fetch(path, {
            method: "POST",
            body: datas
        })
    }
    //-----------------------------------

    // --- Movements & animations ---
    mouvementController(){
        window.addEventListener('keydown', (e) => {
            if (e.code === "KeyW") this.direction = this.current.sprite.indexY = 0;
            else if(e.code === "KeyD") this.direction = this.current.sprite.indexY = 1;
            else if (e.code === "KeyS") this.direction = this.current.sprite.indexY = 2;
            else if (e.code === "KeyA") this.direction = this.current.sprite.indexY = 3;
            else this.direction = null;
        })

        // window.addEventListener('keypress', (e) => {
        //     if (e.code === "KeyW" || e.code === "KeyD" || e.code === "KeyS" || e.code === "KeyA") this.move();
        // })

        window.addEventListener("keyup", (e) => {
            if (e.code === "KeyW" || e.code === "KeyD" || e.code === "KeyS" || e.code === "KeyA")
                this.direction = null;
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

    checkPosition(board){
        let nextPosition = this.checkNextPosition(board.tileSize)
        let playerX = Math.floor((nextPosition[0] + (board.tileSize / 2)) / board.tileSize);
        let playerY = Math.floor((nextPosition[1] + (board.tileSize / 2)) / board.tileSize);

        for (let i = 0; i < board.tiles.length; i++){
            let tileX = board.tiles[i].position.x;
            let tileY = board.tiles[i].position.y;

            if(playerX === tileX && playerY === tileY){
                if(board.tiles[i].state === 3){
                    console.log("salut la porte !")
                }else if(board.tiles[i].state === 1){
                    this.direction = null;
                }
            }
        }

    }

    animation(cellSize, tileSize) {
        CTX.beginPath()
        CTX.drawImage(
            this.current.sprite.image,
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

        if(this.oldPosition[0] !== this.position.x || this.oldPosition[1] !== this.position.y){
            this.current.sprite.indexX++;
        }
        if(this.current.sprite.indexX >= 9) this.current.sprite.indexX = 0;

        this.oldPosition[0] = this.position.x;
        this.oldPosition[1] = this.position.y;
    }

}