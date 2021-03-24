import '../scss/app.scss';
import Level from "./game/Map/Level.js";
import Item from "./game/GameObjects/Item";

const canvas = document.createElement('canvas');
const ctx = canvas.getContext('2d');

new Level(ID_LEVEL ?? 0);
let item = {
    id : "3",
    name : "salut"
}
new Item(item);
// let course;
// let place;
// new Level(course.id ?? 0, place.id ?? 0);