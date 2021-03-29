export default class HUD {
    pannel = document.querySelector('#hud');
    menu = document.querySelector('#hud-menu');
    equipement = document.querySelector('#hud-equipement');
    player = document.querySelector('#hud-player');
    healthBar = document.querySelector(".player__health--update");
    healthVal = document.querySelector(".player__health--value");
    lvlVal = document.querySelector(".player__level--value");
    xpBar = document.querySelector(".player__exp--update");
    xpVal = document.querySelector(".player__exp--value");
    level = document.querySelector("#hud-level");

    constructor(player, level) {
        this.hydrateEquipement(player);
        this.updateHpBar(player);
        this.updateXpBar(player);
        this.updateLvlIndicator(player);
        this.updateLevel(level)
        this.updateRoom(level.currentRoom)
        this.updateDifficulty(level.difficulty)
    }

    //must be called when user changes its equipement
    hydrateEquipement(player) {
        let index = 0;
        Array.from(this.equipement.children).forEach(equipement => {
            if (index < player.equipement.length) {
                let item = player.equipement[index];
                let itemImage = document.createElement("img");
                itemImage.alt = "item preview";
                itemImage.classList.add("items__preview");
                itemImage.src = item.image;
                let desc = document.createElement("div");
                desc.classList.add("items__tooltip");
                desc.innerHTML = `<p><span>Item:</span> ${item.name}</p>
                    <p><span>Type:</span> ${item.typeDesc}</p>
                    <p><span>Description:</span> ${item.description}</p>`;

                equipement.prepend(desc);
                equipement.prepend(itemImage);
                index++;
            }
        });
    }

    updateHpBar(player) {
        this.healthBar.style.right = Math.round(100 - (player.hp / player.current.maxHp * 100)) + "%";
        this.healthVal.innerHTML = `${player.hp} / ${player.current.maxHp} HP`;
    }

    updateXpBar(player) {
        this.xpBar.style.right = Math.round(100 - (player.current.xp / player.current.maxXp * 100)) + "%";
        this.xpVal.innerHTML = `${player.current.xp} / ${player.current.maxXp} XP`;
    }

    updateLevel(level) {
        this.level.querySelector('#course').innerHTML += ` <span class="hud__hot">${level.name}</span>`;
    }

    updateRoom(room) {
        this.level.querySelector('#place').innerHTML += ` <span class="hud__hot">${room.name}</span>`
    }

    updateDifficulty(difficulty) {
        this.level.querySelector('#difficulty').innerHTML += ` <span class="hud__hot">${difficulty}</span>`
    }

    updateLvlIndicator(player) {
        this.lvlVal.innerHTML = player.lvl;
    }

}