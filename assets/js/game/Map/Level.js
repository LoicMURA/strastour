export default class Level{
    constructor(id) {
        console.log('level');
        this.difficulty = 'easy';
        this.rewards = [];
        this.rooms = [];
        this.player = null;
        this.isCleared = false;
    }

    async hydrateLevel(id){
        let query = await fetch(`/datas/levels/${id}`);
        let datas = await query.json();
    }
}