import Board from "./Board";
import {fetcher} from "../Fetcher";

export default class Room{
    constructor(id, difficulty) {
        this.name = '';
        this.address = '';
        this.description = '';
        this.nbEnnemies = 0;
        this.ennemies = [] // [new Mob]
        this.board = new Board(id)
        this.hordes = 0;
        this.hasBoss = false;
        this.isActive = false;
        this.cleared = false;
    }
}