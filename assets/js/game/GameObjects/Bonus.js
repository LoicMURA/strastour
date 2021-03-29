import {fetcher} from '../Fetcher';
import Item from "./Item";

export default class Bonus extends Item {
    constructor(datas) {
        super();
        this.type = "bonus";
        this.price = 0;
        this.timer = 0;
        this.effect = {};

        fetcher.hydrateData(datas, this);
    }
}