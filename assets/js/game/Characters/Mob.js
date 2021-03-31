import Character from "./Character";
import {fetcher} from "../Fetcher";
import Attack from "./Attack";

export default class Mob extends Character {
    constructor(type, id, datas, diff, cols, tileSize, playerLvl, src) {
        super(cols, tileSize, src);
        this.name = '';
        this.drop = [];
        this.dropChance = 0;
        this.type = type; // 'mob' || 'boss'
        this.typeMob = id; // 'mob' => id_mob || 'boss' => id_boss
        this.attacks = []; // [new Attack]
        this.src = null;
        fetcher.hydrateData(datas[id], this);
        this.hydrateAttacks();
        this.setDiff(diff);
        this.direction = this.randomInt(0, 3);
        this.upgradeToCurrentStats(playerLvl);
        this.sprite.image.src = this.src;
        this.currentHp = this.hp;
    }

    hydrateAttacks() {
        if (this.type === "mob") {
            this.attacks[0] = new Attack(this.type, this.typeMob);
        } else if (this.type === "boss") {
            this.rngBossAttacks();
        }
    }

    rngBossAttacks() {
        let nbAttack = 5;
        let setAtk = [];
        for (let i = 0; i < nbAttack; i++) {
            setAtk[i] = i + 1;
        }
        for (let i = 0; i < 3; i++) {
            let rng = Math.floor(Math.random() * (setAtk.length));
            this.attacks[i] = new Attack(this.type, setAtk[rng]);
            setAtk.splice(rng, 1);
        }
    }

    diffToId(difficultyString) {
        let diff;
        switch (difficultyString) {
            case "easy":
                diff = 0;
                break;
            case "normal":
                diff = 1;
                break;
            case "hard":
                diff = 2;
                break;
        }
        return diff;
    }

    setDiff(difficulty) {
        let diff = this.diffToId(difficulty);
        let changes = ["hp"];
        for (let stat of changes) {
            if (Array.isArray(this[stat]) && this.typeMob !== "4") {
                this[stat] = this[stat][diff];
            }
        }
    }

    upgradeToCurrentStats(lvl) {
        lvl = 44;
        let mobLvl = Math.floor(lvl / 10);
        if (this.typeMob !== "4") {
            this.hp = Math.round((this.hp * (mobLvl+1) + this.getMobLife((mobLvl - 1) * 10)) / 2);
            this.def = Math.round((this.def * (mobLvl+1) + this.getMobDef((mobLvl - 1) * 10)) / 2);
        }
        this.atk = Math.round((this.atk * (mobLvl+1)  + this.getMobAtk((mobLvl - 1) * 10)) / 2);
    }

    // For the mobs
    getMobLife(lvl) {
        return Math.floor(this.polynome2(42.324, 7.9553, 0.1374, lvl))
    }

    getMobAtk(lvl) {
        return Math.floor(this.polynome2(15.409, 0.4456, 0.0069, lvl))
    }

    getMobDef(lvl) {
        return Math.floor(this.polynome2(15.409, 0.4456, 0.0069, lvl))
    }

}