import Board from "./Board";
import Mob from "../Characters/Mob";

export default class Room {
    constructor(id, roomDatas, boss, boardDatas, difficulty, levelId, characterDatas) {
        this.id = id;
        this.datas = roomDatas;
        this.name = '';
        this.address = '';
        this.description = '';
        this.enemies = [];
        this.board = new Board(boardDatas.rows, boardDatas.cols, boardDatas.tile, this.datas.image, this.datas.states);
        this.hordes = 0;
        this.hasBoss = boss;
        this.isActive = false;
        this.cleared = false;
        this.hydrateEnemies(difficulty, levelId, characterDatas);
    }

    hydrateEnemies(diff, levelId, datas) {
        let diffDatas = {
            easy: {
                mobMin: 4,
                mobMax: 8,
                hordesMin:1,
                hordesMax:3,
                chances: {
                    //defines each mob type chances to appear in room
                    //only multiples of 5 are allowed
                    1: 60,
                    2: 25,
                    3: 10,
                    4: 5
                }
            },
            normal: {},
            hard: {}
        }
        if(this.hasBoss) {
            this.enemies[0] = new Mob("boss", levelId, datas["boss"], diff, 0, 0);
        } else {
            let nbHordes = this.rng(diffDatas[diff], "hordes");
            this.hordes = nbHordes;
            for(let i = 0; i < nbHordes; i++) {
                let nbMob = this.rng(diffDatas[diff], "mobs");
                this.enemies[i] = [];
                for (let j = 0; j < nbMob; j++) {
                    let idMob = this.rng(diffDatas[diff], "id");
                    this.enemies[i] = [...this.enemies[i], new Mob("mob", idMob, datas["mob"], diff, this.board.cols, this.board.tileSize)];
                }
            }
        }
    }

    rng(data, which) {
        let val;
        switch (which) {
            case "hordes":
                val = this.randomInt(data.hordesMin, data.hordesMax);
                break;
            case "mobs":
                val = this.randomInt(data.mobMin, data.mobMax);
                break;
            case "id":
                let ids = [];
                for(const id in data.chances) {
                    let rep = data.chances[id]/5;
                    for(let i = 0; i < rep; i++) {
                        ids.push(id);
                    }
                }
                val = ids[this.randomInt(0, ids.length - 1)];
                break;
        }
        return val;
    }

    randomInt(min, max) {
        min = Math.ceil(min);
        max = Math.floor(max);
        return Math.floor(Math.random() * (max - min + 1)) + min;
    }
}