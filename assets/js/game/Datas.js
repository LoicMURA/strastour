/**
 * This class is used to get all datas for the games and the use datas in the game controller
 */
export default class Datas {
    constructor() {
        this.Characters = null;
        this.Items = null;
        this.Weapons = null;
        this.Level = null;
        this.boardSizes = {
            rows: 11,
            cols: 20,
            tile: 40,
        }
    }

    /**
     * Get datas from json to set up current level
     * @param id
     * @returns {Promise<void>}
     */
    async hydrateCurrentLevel(id){
        let query = await fetch(`/assets/datas/levels/${id}.json`);
        let datas = await query.json();
        this.Level = await datas;
    }

    /**
     * Get all datas for each characters, items, weapons
     * @param object
     * @returns {Promise<void>}
     */
    async hydrateDatas(object) {
        let dataJson = ["Characters", "Items", "Weapons"];
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