import {fetcher} from "./Fetcher";

export default class Datas {
    constructor() {
        this.Characters = null;
        this.Items = null;
        this.Weapons = null;
        this.Levels = null;
        this.boardSizes = {
            rows: 11,
            cols: 20,
            tile: 40,
        }
    }

    async hydrateDatas(object) {
        let dataJson = ["Characters", "Items", "Weapons", "Levels"];
        for (const dataFile of dataJson) {
            let query = await fetch("/assets/datas/" + dataFile + ".json");
            let datas = await query.json();
            for (const property in object) {
                if (property === dataFile) {
                    object[property] = datas;
                }
            }
        }
    }
}