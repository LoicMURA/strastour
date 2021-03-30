import {fetcher} from '../Fetcher';
export default class Item{
    constructor() {
        //defines which type of item it is
        this.id = '';
        this.name = '';
        this.typeDesc = '';
        this.image = '';
        this.description = '';
        this.sprite = null; // new Sprite
    }
}