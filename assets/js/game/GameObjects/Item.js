import {fetcher} from '../Fetcher';
export default class Item{
    constructor(item) {
        this.id = item.id;
        this.name = item.name;
        this.image = "";
        this.sprite = null; // new Sprite
        this.price = 0;
        this.description = "";
        this.timer = 0;
        this.effect = {};
        this.fetcher = fetcher;
        this.fetcher.fetchData(this, `Items.json`, this.id)
        console.log(this)
    }
}