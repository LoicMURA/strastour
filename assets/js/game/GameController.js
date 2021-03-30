import Level from "./Map/Level";
import Player from "./Characters/Player";
import Board from "./Map/Board";
import Datas from "./Datas";
import HUD from "./HUD";
import {fetcher} from "./Fetcher";

export default class GameController {
    constructor() {
        this.datas = new Datas();
    }

    initGame() {
        this.datas.hydrateDatas(this.datas)
            .then(() => {
                this.datas.hydrateCurrentLevel(ID_LEVEL)
                    .then(() => {
                        this.level = new Level(ID_LEVEL ?? 0, this.datas.Level);
                        this.level.hydrateLevel()
                            .then(() => {
                                this.level.hydrateRooms(this.datas.boardSizes, this.datas.Characters)
                                    .then(() => {
                                        this.level.currentRoom = this.level.rooms[0];
                                        this.player = new Player(this.datas.Items, this.datas.Weapons, this.datas.boardSizes.cols, this.datas.boardSizes.tile);
                                        fetcher.fetchData(this.player, '/assets/datas/Characters.json', ["player"])
                                            .then(() => {
                                                this.player.upgradeToCurrentStats();
                                                this.level.currentRoom.hydrateEnemies(this.level.difficulty,this.level.id, this.datas.Characters, this.player.lvl);
                                                this.hud = new HUD(this.player, this.level);
                                            })
                                            .then(() => {
                                                requestAnimationFrame(this.anim.bind(this))
                                                console.log(this);
                                            })
                                    })
                            })
                    })
            });
    }

    anim(currentTime) {
        requestAnimationFrame(this.anim.bind(this));
        CTX.clearRect(0, 0, CANVAS.width, CANVAS.height);
        this.level.currentRoom.board.draw();
        this.player.animation(64 ,this.datas.boardSizes.tile);
    }
}