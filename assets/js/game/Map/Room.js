import Board from "./Board";

export default class Room{
    constructor() {
        this.nbEnnemies = 0;
        this.ennemies = [] // [new Mob]
        this.board = new Board()
        this.hordes = 0;
        this.hasBoss = false;
        this.isActive = false;
        this.cleared = false;
    }
}