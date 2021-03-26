export default class HUD{
    pannel = document.querySelector('#hud');
    menu = document.querySelector('#hud-menu');
    equipement = document.querySelector('#hud-equipement');
    player = document.querySelector('#hud-player');
    healthBar = document.querySelector(".player__health--update");
    healthVal = document.querySelector(".player__health--value");
    level = document.querySelector("#hud-level");
    constructor(player, level) {
        this.hydrateEquipement(player);
        this.updateHpBar(player);
        this.hydrateLevel(level)
    }

    //must be called when user changes its equipement
    hydrateEquipement(player) {
        let index = 0;
        Array.from(this.equipement.children).forEach(equipement => {
            if(index < player.equipement.length) {
                let item = player.equipement[index].item;
                let itemImage = document.createElement("img");
                itemImage.alt = "item preview";
                itemImage.classList.add("items__preview");
                itemImage.src = item.picture;
                let desc = document.createElement("div");
                desc.classList.add("items__tooltip");
                desc.innerHTML = `<p><span>Item:</span> ${item.name}</p><p><span>Type:</span> ${item.type.name}</p>`;
                equipement.prepend(desc);
                equipement.prepend(itemImage);
                index ++;
            }
        });
    }

    hydrateLevel(level){
        Array.from(this.level.children).forEach(element => {
            if(element.innerHTML === 'Parcours:') element.innerHTML += ` <span class="hud__hot">${level.name}</span>`;
            if(element.innerHTML === 'Étape:') element.innerHTML += ` <span class="hud__hot">${level.currentRoom.name}</span>`;
            if(element.innerHTML === 'Difficulté:') element.innerHTML += ` <span class="hud__hot">${level.difficulty}</span>`;
        })
    }

    updateHpBar(player){
        this.healthVal.innerHTML = `${player.hp} / ${player.current.maxHp} HP`;
    }
}