import Level from "./Map/Level";
import Player from "./Characters/Player";
import Datas from "./Datas";
import HUD from "./HUD";
import {fetcher} from "./Fetcher";

/**
 * The mother class, that contain all elements that we need for the game
 */
export default class GameController {
    constructor() {
        this.datas = new Datas();
    }

    /**
     * Set up each element to start the game
     */
    initGame() {
        // get each data
        this.datas.hydrateDatas(this.datas)
            .then(() => {
                // get data for current level
                this.datas.hydrateCurrentLevel(ID_LEVEL)
                    .then(() => {
                        // instantiate level and hydrate datas
                        this.level = new Level(ID_LEVEL ?? 0, this.datas.Level);
                        this.level.hydrateLevel()
                            .then(() => {
                                // hydrate each rooms in the level
                                this.level.hydrateRooms(this.datas.boardSizes, this.datas.Characters)
                                    .then(() => {
                                        // set the current room for the level
                                        this.level.currentRoom = this.level.rooms[0];
                                        // instatiate player
                                        this.player = new Player(this.datas.Items, this.datas.Weapons, this.datas.boardSizes.cols, this.datas.boardSizes.tile);
                                        fetcher.fetchData(this.player, '/assets/datas/Characters.json', ["player"])
                                            .then(() => {
                                                // set currents stats (each stats is computed depends on the experience that the user already collected
                                                this.player.upgradeToCurrentStats();
                                                // set enemies for the current room
                                                this.level.currentRoom.hydrateEnemies(this.level.difficulty,this.level.id, this.datas.Characters, this.player.lvl);
                                                // set up hud
                                                this.hud = new HUD(this.player, this.level);
                                            })
                                            .then(() => {
                                                // start loop
                                                requestAnimationFrame(this.anim.bind(this))
                                                console.log(this);
                                            })
                                    })
                            })
                    })
            });
    }

    /**
     * game animation loop
     * @param currentTime
     */
    anim(currentTime) {
        requestAnimationFrame(this.anim.bind(this))

        // clear canvas
        CTX.clearRect(0, 0, CANVAS.width, CANVAS.height);

        // background
        this.level.currentRoom.board.draw();

        // player
        this.player.checkDoorCollision(this.level.currentRoom.board, this.level);
        this.player.checkObstaclesCollision(this.level.currentRoom.board);
        this.player.checkBoundsCollision(this.level.currentRoom.board);
        this.player.move();
        this.player.animation(64 ,this.datas.boardSizes.tile);

        this.level.currentRoom.showEnnemies(this.player);
    }
}