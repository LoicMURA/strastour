import '../scss/app.scss';
import Level from "./game/Map/Level.js";

const canvas = document.createElement('canvas');
const ctx = canvas.getContext('2d');

new Level(1);
// let course;
// let place;
// new Level(course.id ?? 0, place.id ?? 0);