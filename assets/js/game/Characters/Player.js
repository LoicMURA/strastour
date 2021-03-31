import Character from "./Character";
import Bonus from "../GameObjects/Bonus";
import Weapon from "../GameObjects/Weapon";

export default class Player extends Character {
    constructor(itemDatas, weaponDatas, cols, tileSize) {
        super(cols, tileSize, `/assets/image/sprites/character/male/hand.png`);
        this.lvl = 1;
        this.type = "player";
        this.gender = USER.player.gender;
        this.pseudo = USER.username;
        this.xp = USER.player.xp;
        this.position = {
            x: CANVAS.width / 2,
            y: CANVAS.height / 2
        }
        //each values that needs to be selected based on classes properties
        this.current = {
            xp: 0,
            item: 0,
            maxHp: 0,
            maxXp: 0
        };
        // let gender = this.gender === 'f' ? 'female' : 'male';
        this.stucks = USER.player.stuck;
        this.inventory = [];
        //items that are part of the inventory, must be displayed in the HUD
        this.equipement = [];
        //store if a weapon or bonus is being used, their relative bonuses to each of those items
        this.activeBonus = [];
        this.hydrateInventory(itemDatas, weaponDatas);
        this.mouvementController(cols, tileSize, 64);

        this.setSpritesAttacks();
    }

    action(board) {
        if(this.current.item === 1) {
            this.spriteAttack.image = this.spriteAttack.dagger
            this.animationAttack(64, board.tileSize, 5)
        }else if(this.current.item === 2){
            this.spriteAttack.image = this.spriteAttack.spear
            this.animationAttack(64, board.tileSize, 7)
        }else if(this.current.item === 3){
            this.spriteAttack.image = this.spriteAttack.sword
            this.animationAttack(128, board.tileSize, 5)
        }else if(this.current.item === 4){
            this.spriteAttack.image = this.spriteAttack.bow
            this.animationAttack(128, board.tileSize, 12)
        }
    }

    // --- Hydrating class datas ---
    hydrateInventory(bonusDatas, weaponDatas) {
        let index = 0;
        for (const itemData of USER.player.inventory) {
        //     to delete later on
            if (index < 4) {
                (index > 5) ? itemData.equiped = false : itemData.equiped = true;
                // A mettre à jour w/ BDD
                let name;
                let id = index + 1;

                switch (index) {
                    case 0:
                        name = 'dagger';
                        break;
                    case 1:
                        name = 'spear';
                        break;
                    case 2:
                        name = 'sword';
                        break;
                    case 3:
                        name = 'bow';
                        break;
                }
                itemData.item.type.name = name;
                itemData.item.type.id = id;

                if (this.itemIsBonus(itemData.item)) {
                    itemData.item = new Bonus(bonusDatas[id]);
                } else if (this.itemIsWeapon(itemData.item)) {
                    itemData.item = new Weapon(weaponDatas[id]);
                }
                itemData.item.id = id;
                this.inventory.push(itemData);
                index++;
            }
        }
        this.updateEquipement();
    }

    //store data that needs to be calculated based on player's level
    upgradeToCurrentStats(){
        this.lvl = this.getLevel(this.xp);
        this.current.maxXp = (this.lvl !== 60) ? this.getMaxXp(this.lvl): this.getMaxXp(this.lvl - 1);this.current.xp = this.xp;
        this.hp = this.getPlayerLife(this.lvl);
        this.current.maxHp = this.hp;
        this.atk = this.getPlayerAtk(this.lvl);
        this.def = this.getPlayerDef(this.lvl);
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

    earnXp(xp, hud){
        this.current.xp += xp * 1;
        let currentLvl = this.lvl;
        this.lvl = this.getLevel(this.current.xp);
        if(currentLvl != this.lvl) {
            this.levelUp(hud);
        }
        hud.updateXpBar(this);
    }

    levelUp(hud) {
        this.xp = this.current.xp;
        this.upgradeToCurrentStats();
        hud.updateLvlIndicator(this);
        hud.updateHpBar(this);
        this.updateXp();
    }

    //----------------------------------


    updateStucks()
    {
        let path = window.location.href.replace('jeu', 'profile/stuck-update');
        let datas = new FormData();
        datas.append('stucks', this.stucks);
        fetch(path, {
            method: "POST",
            body: datas
        })
    }

    updateXp()
    {
        let path = window.location.href.replace('jeu', 'profile/xp-update');
        let datas = new FormData();
        datas.append('xp', this.current.xp);
        fetch(path, {
            method: "POST",
            body: datas
        })
    }

    // precise each items that have been modified, even those who aren't possessed anymore
    updateInventory()
    {
        let path = window.location.href.replace('jeu', 'profile/inventory-update')
        let datas = new FormData()
        let i = 1;
        for (let item of this.inventory) {
            // item au format {id: 1, quantity: 1, quality:100}
            datas.append(`inventory-${i}`, JSON.stringify(item))
            i++
        }
        fetch(path, {
            method: "POST",
            body: datas
        })
    }

    // user has cleared each level's room and so level is also cleared
    updateClearedLvl(levelId){
        let path = window.location.href.replace('jeu', 'profile')
        let datas = new FormData()
        datas.append('id',levelId);
        fetch(path, {
            method: "POST",
            body: datas
        })
    }
    //-----------------------------------

    // --- Movements & animations ---
    mouvementController(){
        window.addEventListener('keydown', (e) => {
            if (e.code === "KeyW") this.direction = this.sprite.indexY = 0;
            else if(e.code === "KeyD") this.direction = this.sprite.indexY = 1;
            else if (e.code === "KeyS") this.direction = this.sprite.indexY = 2;
            else if (e.code === "KeyA") this.direction = this.sprite.indexY = 3;
            else this.direction = null;
        })
        window.addEventListener("keyup", (e) => {
            if (e.code === "KeyW" || e.code === "KeyD" || e.code === "KeyS" || e.code === "KeyA")
                this.direction = null;

        })
    }

    controlActions(board) {
        window.addEventListener('keydown', (e) => {
            if (e.code === "ArrowUp") this.attackDirection = this.spriteAttack.indexY = 0;
            if (e.code === "ArrowRight") this.attackDirection = this.spriteAttack.indexY = 1;
            if (e.code === "ArrowDown") this.attackDirection = this.spriteAttack.indexY = 2;
            if (e.code === "ArrowRight") this.attackDirection = this.spriteAttack.indexY = 3;
        })

        window.addEventListener('keydown', (e => {
            if(e.code === "ArrowUp" || e.code === "ArrowDown" || e.code === "ArrowLeft" || e.code === "ArrowRight"){
                this.isAttacking = true;
                this.action(board);
            }
        }))
    }

    async setSpritesAttacks(){
        this.spriteAttack = {
            dagger: new Image(),
            bow: new Image(),
            spear: new Image(),
            sword: new Image(),
            indexX : 0,
            indexY : 0
        }
        this.spriteAttack.dagger.src = 'assets/image/sprites/character/male/attacks/dagger.png';
        this.spriteAttack.bow.src = 'assets/image/sprites/character/male/attacks/bow.png';
        this.spriteAttack.spear.src = 'assets/image/sprites/character/male/attacks/spear.png';
        this.spriteAttack.sword.src = 'assets/image/sprites/character/male/attacks/sword.png';
    }

    checkDoorCollision(board, level, controller){
        let coordinates = this.getNextPosition(board);
        for(let i = 0; i < board.doors.length; i++){
            let door = board.doors[i];
            if(coordinates.col === door.position.x && coordinates.row === door.position.y){
                if(coordinates.col === 0 && this.direction === 3 ||
                    coordinates.col === board.cols - 1 && this.direction === 1 ||
                    coordinates.row === board.rows - 1 && this.direction === 2 ||
                    coordinates.row === 0 && this.direction === 0
                ){
                    if((level.currentRoom.id === 1 || level.currentRoom.id === '1') && i === board.doors.length - 1){
                        ID_LEVEL = 0;
                        controller.switchLevel(level.id)
                    }else{
                        level.switchRoom(board.doors[i].door, this, controller.datas.Characters);
                    }
                }else if(level.id === 0 && ID_LEVEL !== 0){
                    controller.switchLevel();
                }
            }
        }
    }

    checkInteractionCollision(board, hud){
        let coordinates = this.getNextPosition(board);
        let requestInteraction = false;
        for(let i = 0; i < board.interactibles.length; i++) {
            let tile = board.interactibles[i];
            if(coordinates.col === tile.position.x && coordinates.row === tile.position.y){
                requestInteraction = tile.state;

            }
        }
        if(requestInteraction) {
            if(requestInteraction === 4){
                hud.commandInfosController("selectLevel");
            } else if (requestInteraction === 5) {
                hud.commandInfosController("buyItems");
            } else if (requestInteraction === 6) {
                hud.commandInfosController("askNarrator");
            }
        } else if (hud.interactionInfos) {
            hud.hideCommandInfos();
        }
    }
}