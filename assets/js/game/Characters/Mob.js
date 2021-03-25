import Character from "./Character";
import {fetcher} from "../Fetcher";
import Attack from "./Attack";

export default class Mob extends Character {
    constructor(type, id) {
        super();
        this.name = '';
        this.drop = [];
        this.dropChance = 0;
        this.type = type; // 'mob' || 'boss'
        this.typeMob = id; // 'mob' => id_mob || 'boss' => id_boss
        this.attacks = []; // [new Attack]

        fetcher.fetchData(this, '/assets/datas/Characters.json', [this.type, this.typeMob])
            .then(() => {
                this.hydrateAttacks();
                console.log(this);
            });
    }

    hydrateAttacks() {
        if (this.type === "mob") {
            this.attacks[0] = new Attack(this.type, this.typeMob);
        } else if (this.type === "boss") {
            //list of available attack names for bosses, must correspond to atk name in Attack class switch
            let setAtk = ["laserBeam", "shockWave", "closeRangeHit", "jump"];
            for (let i = 0; i < 3; i++) {
                let rng = Math.floor(Math.random() * (setAtk.length));
                this.attacks[i] = new Attack(this.typeMob, setAtk[rng]);
                setAtk.splice(rng, 1);
            }
        }
    }
}