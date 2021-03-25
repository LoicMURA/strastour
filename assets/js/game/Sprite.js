export default class Sprite {
    constructor(src) {
        this.src = src;
    }

    drawSprite(srcX, srcY, srcW, srcH, destX, destY, destW, destH) {
        CTX.beginPath();
        CTX.drawImage(this.src, srcX, srcY, srcW, srcH, destX, destY, destW, destH)
        CTX.closePath();
    }

    animSprite(indexX, indexY, cellSize, tileSize, destX, destY){
        CTX.beginPath();
        CTX.drawImage(this.src, indexX * cellSize, indexY * cellSize, cellSize, cellSize, destX, destY, tileSize, tileSize)
        CTX.closePath()
    }

    /**
     * Draw the board
     */
    static drawBoard(Board) {
        if (!CANVAS.width && !CANVAS.height) {
            CANVAS.width = Board.cols * Board.tileSize;
            CANVAS.height = Board.rows * Board.tileSize;
        }

        CTX.beginPath();
        for (const tileKey in Board.tiles) {
            CTX.strokeRect(Board.tiles[tileKey].position.x, Board.tiles[tileKey].position.y, Board.tileSize, Board.tileSize);
        }
        CTX.closePath();
    }
}