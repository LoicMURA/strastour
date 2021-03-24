import Board from "./Board";

export default class Room{
    constructor(id) {
        this.name = '';
        this.nbEnnemies = 0;
        this.ennemies = [] // [new Mob]
        this.board = new Board(id)
        this.hordes = 0;
        this.hasBoss = false;
        this.isActive = false;
        this.cleared = false;
    }
}