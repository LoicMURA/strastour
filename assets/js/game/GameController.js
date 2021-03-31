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
        this.timestamp = 0;
        this.gameSpeed = 30;
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
                            this.level.hydrateRooms(this.datas.boardSizes)
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
            document.querySelector('.loader__close').classList.replace('loader__close', 'loader__open')
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
                                    // player in safeZone
                                    this.level.isSafe(this.datas.Characters, this.player.lvl);

                                    if (this.level.currentRoom.id === 0) {
                                        this.hud.panelInteractionsController(this.player, this);
                                    }
                                    let doorsLength = this.level.currentRoom.board.doors.length - 1
                                    let doorPosition = this.level.currentRoom.board.doors[doorsLength];

                                    if(this.level.id === 0){
                                        this.player.position.y = (doorPosition.position.y * this.datas.boardSizes.tile) + this.datas.boardSizes.tile;
                                    }else{
                                        this.player.position.y = doorPosition.position.y * this.datas.boardSizes.tile;

                                    }
                                    this.player.position.x = doorPosition.position.x * this.datas.boardSizes.tile;
                                }).then(() => {
                                    this.animationId = requestAnimationFrame(this.anim.bind(this))
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

        const fpsSinceLastRender = (currentTime - this.timestamp) / 1000;
        if (fpsSinceLastRender < 1 / this.gameSpeed) return;
        this.timestamp = currentTime;

        // clear canvas
        CTX.clearRect(0, 0, CANVAS.width, CANVAS.height);

        // background
        this.level.currentRoom.board.draw();

        // player
        this.player.checkDoorCollision(this.level.currentRoom.board, this.level, this);
        if (this.level === null) console.log(this.level);
        this.player.checkObstaclesCollision(this.level.currentRoom.board);
        this.player.checkBoundsCollision(this.level.currentRoom.board);
        this.player.move();
        this.player.animation(64, this.datas.boardSizes.tile);


        if(this.level.id !== 0){
            this.level.currentRoom.showEnnemies(this.player, this);
        }
        document.querySelector('.loader__open')?.classList.replace('loader__open', 'loader__close')
    }

    stopAnim() {
        CTX.clearRect(0, 0, CANVAS.width, CANVAS.height);
        cancelAnimationFrame(this.animationId);
    }

    switchLevel(currentLevel = 0) {
        this.stopAnim();
        this.resetLvl();
        this.initGame(ID_LEVEL, true);
        if(currentLevel !== 0){
            ID_LEVEL = currentLevel;
        }
    }

    resetLvl(obj) {
        this.datas.Level = null;
        this.level = null;
    }
}