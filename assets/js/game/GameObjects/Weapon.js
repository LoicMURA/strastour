import {fetcher} from "../Fetcher";
import Item from "./Item";

export default class Weapon extends Item{
    constructor(datas) {
        super();
        this.type = "weapon";
        this.atk = 0;
        this.fireRate = 0;
        this.attack = null; // new Attack
        this.qualityMax = 100;
        this.quality = 100;
        fetcher.hydrateData(datas, this);
    }
}