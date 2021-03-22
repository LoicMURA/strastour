
export default class Room{
    constructor() {
        this.id = 0;
        this.ennemies = [] // [new Mob]
        this.board = null // new Board
        this.hordes = 0;
        this.hasBoss = false;
        this.isActive = false;
        this.cleared = false;
    }
}