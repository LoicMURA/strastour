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
        this.animationId = 0;
    }

    awake() {
        this.initDatas().then(() => {
            this.initGame(ID_LEVEL);
        })
    }

    async initDatas() {
        // get each data
        await this.datas.hydrateDatas(this.datas);
    }

    /**
     * Set up each element to start the game
     */
    initGame(idLevel, refresh = false) {
        if (!refresh) {
            // get data for current level
            this.datas.hydrateCurrentLevel(idLevel)
                .then(() => {
                    // instantiate level and hydrate datas
                    this.level = new Level(idLevel ?? 0, this.datas.Level);
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
                                            this.level.currentRoom.hydrateEnemies(this.level.difficulty, this.level.id, this.datas.Characters, this.player.lvl);
                                            // player in safeZone
                                            this.level.isSafe(this.datas.Characters, this.player.lvl);
                                            // set up hud
                                            this.hud = new HUD(this.player, this.level);
                                        })
                                        .then(() => {
                                            // start loop
                                            if (this.level.currentRoom.id === 0) {
                                                this.hud.panelInteractionsController(this.player, this);
                                            }
                                            this.animationId = requestAnimationFrame(this.anim.bind(this))
                                            console.log(this);
                                        })
                                })
                        })
                });
        } else {
            // get data for current level
            this.datas.hydrateCurrentLevel(idLevel)
                .then(() => {
                    // instantiate level and hydrate datas
                    this.level = new Level(idLevel ?? 0, this.datas.Level);
                    this.level.hydrateLevel()
                        .then(() => {
                            // hydrate each rooms in the level
                            this.level.hydrateRooms(this.datas.boardSizes, this.datas.Characters)
                                .then(() => {
                                    // set the current room for the level
                                    this.level.currentRoom = this.level.rooms[0];
                                    // set enemies for the current room
                                    this.level.currentRoom.hydrateEnemies(this.level.difficulty, this.level.id, this.datas.Characters, this.player.lvl);
                                    // player in safeZone
                                    this.level.isSafe(this.datas.Characters, this.player.lvl);

                                    if (this.level.currentRoom.id === 0) {
                                        this.hud.panelInteractionsController(this.player, this);
                                    }
                                    this.animationId = requestAnimationFrame(this.anim.bind(this))
                                    console.log(this);
                                })
                        })
                })
        }
    }

    /**
     * game animation loop
     * @param currentTime
     */
    anim(currentTime) {
        this.animationId = requestAnimationFrame(this.anim.bind(this))

        // clear canvas
        CTX.clearRect(0, 0, CANVAS.width, CANVAS.height);

        // background
        this.level.currentRoom.board.draw();

        // player
        this.player.checkDoorCollision(this.level.currentRoom.board, this.level);
        this.player.checkObstaclesCollision(this.level.currentRoom.board);
        this.player.checkBoundsCollision(this.level.currentRoom.board);
        this.player.move();
        this.player.animation(64, this.datas.boardSizes.tile);

        this.level.currentRoom.showEnnemies(this.player);
    }

    stopAnim() {
        CTX.clearRect(0, 0, CANVAS.width, CANVAS.height);
        cancelAnimationFrame(this.animationId);
    }

    switchLevel() {
        this.stopAnim();
        this.resetLvl();
        this.initGame(ID_LEVEL, true);
    }

    resetLvl(obj) {
        this.datas.Level = null;
        this.level = null;
    }
}