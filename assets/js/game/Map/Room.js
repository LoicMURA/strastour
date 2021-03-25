export default class Room{
    constructor(id, difficulty, roomDatas) {
        this.id = id;
        this.datas = roomDatas;
        this.name = '';
        this.address = '';
        this.description = '';
        this.nbEnnemies = 0;
        this.ennemies = [] // [new Mob]
        this.board = null;
        this.hordes = 0;
        this.hasBoss = false;
        this.isActive = false;
        this.cleared = false;
    }


}