import Level from "./Map/Level";
import Player from "./Characters/Player";
import Board from "./Map/Board";

export default class GameController {
    boardSizes = {
        rows: 11,
        cols: 20,
        tile: 40,
    }

    constructor() {
        this.level = new Level(ID_LEVEL ?? 0);

        window.addEventListener('keydown', (e) => {
            console.log(e.key)
            if (e.key === 's'){
                this.player.direction = 0
                this.player.position.y += 5;
            }
            if (e.key === 'z'){
                this.player.direction = 2
                this.player.position.y -= 5;
            }
            if (e.key === 'q'){
                this.player.direction = 3
                this.player.position.x -= 5;
            }
            if (e.key === 'd'){
                this.player.direction = 1
                this.player.position.x += 5;
            }
        })
    }

    initGame() {
        this.level.hydrateLevel()
            .then(() => {
                this.level.hydrateRooms()
                    .then(() => {
                    this.level.currentRoom = this.level.rooms[0]
                    this.level.currentRoom.board = new Board(this.boardSizes.rows, this.boardSizes.cols, this.boardSizes.tile)
                    this.level.currentRoom.board.drawBackground()
                })
            })
            .then(() => {
                this.player = new Player(this.boardSizes.cols, this.boardSizes.tile)
                this.player.sprites.noGun.drawSprite(
                    0,
                    0,
                    64,
                    64,
                    this.player.position.x,
                    this.player.position.y,
                    this.boardSizes.tile,
                    this.boardSizes.tile
                )
            })
            .then(() => requestAnimationFrame(this.anim.bind(this)))
    }

    anim(currentTime) {
        requestAnimationFrame(this.anim.bind(this))
        CTX.clearRect(0, 0, CANVAS.width, CANVAS.height);
        this.level.currentRoom.board.drawBackground();
        // this.player.sprites.noGun.drawSprite(
        //     0,
        //     0,
        //     64,
        //     64,
        //     this.player.position.x,
        //     this.player.position.y,
        //     this.boardSizes.tile,
        //     this.boardSizes.tile
        // )
        this.player.sprites.noGun.animSprite(
            this.player.indexSprite,
            this.player.direction,
            64,
            this.boardSizes.tile,
            this.player.position.x,
            this.player.position.y
        )
        this.player.indexSprite++
        if(this.player.indexSprite === 8) this.player.indexSprite = 0
    }
}