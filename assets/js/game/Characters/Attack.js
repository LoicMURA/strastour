export default class Attack {
    constructor(characterType, atkType) {
        this.info = {type: characterType, atk: atkType};
    }

    atk() {
        switch (this.info.type) {
            case "player":
                //attack is instantiated in Weapon class (must take as param "player" manually)
                switch (this.info.atk) {
                    //logic for each Weapon attack
                }
                break;
            case "mob":
                //attack is direrctly related to a Mob (no Weapon class between)
                switch (this.info.atk) {
                    //logic for each mob atk
                }
                break;
            case "boss":
                //same as mob
                switch (this.info.atk) {
                    //logic for each boss atk (assuming they are different from mobs
                }
                break;
        }
    }
}