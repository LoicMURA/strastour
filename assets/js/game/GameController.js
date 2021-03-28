import Level from "./Map/Level";
import Player from "./Characters/Player";
import Board from "./Map/Board";
import Datas from "./Datas";
import HUD from "./HUD";
import {fetcher} from "./Fetcher";

export default class GameController {
    constructor() {
        this.datas = new Datas();

        window.addEventListener('keydown', (e) => {
            if (e.key === 's') {
                this.player.direction = 0
                this.player.position.y += 5;
            }
            if (e.key === 'z') {
                this.player.direction = 2
                this.player.position.y -= 5;
            }
            if (e.key === 'q') {
                this.player.direction = 3
                this.player.position.x -= 5;
            }
            if (e.key === 'd') {
                this.player.direction = 1
                this.player.position.x += 5;
            }
        })
    }

    initGame() {
        this.datas.hydrateDatas(this.datas).then(() => {
            this.level = new Level(ID_LEVEL ?? 0, this.datas.Levels[ID_LEVEL ?? 0]);
            this.level.hydrateLevel()
                .then(() => {
                    this.level.hydrateRooms(this.datas.boardSizes, this.datas.Characters)
                        .then(() => {
                            this.level.currentRoom = this.level.rooms[0]
                            this.level.currentRoom.board.drawBackground()
                        })
                })
                .then(() => {
                    this.player = new Player(this.datas.boardSizes.cols, this.datas.boardSizes.tile);
                    fetcher.fetchData(this.player, '/assets/datas/Characters.json', ["player"]).then(()=>{
                        this.player.setCurrent();
                        this.hud = new HUD(this.player, this.level);
                    });
                })
                .then(() => {
                    // requestAnimationFrame(this.anim.bind(this))
                    console.log(this);
                })
        });
    }

    anim(currentTime) {
        requestAnimationFrame(this.anim.bind(this))
        CTX.clearRect(0, 0, CANVAS.width, CANVAS.height);
        this.level.currentRoom.board.drawBackground();
        // this.player.sprites.noGun.animSprite(
        //     this.player.indexSprite,
        //     this.player.direction,
        //     64,
        //     this.datas.boardSizes.tile,
        //     this.player.position.x,
        //     this.player.position.y
        // )
        this.player.indexSprite++
        if (this.player.indexSprite === 8) this.player.indexSprite = 0
    }
}