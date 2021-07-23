const searchButton = document.querySelector('.search-button');
const search = document.querySelector('.search');
const menuButton = document.querySelector('.menu-button');
const menu = document.querySelector('.menu');

const move = document.querySelector('.container').childNodes[1];
const moveSecond = move.nextElementSibling;

window.onload = () => {
  search.style.display = "none";
  menu.style.display = "none";
}

searchButton.addEventListener('click', () => {
  if(search.style.display == "none") {
    search.style.display = "block";

    if(innerWidth < 800) {
      move.style.marginTop = "100px";
    } else {
      move.style.marginTop = "100px";

      if(moveSecond) {
        moveSecond.style.marginTop = "100px";
      }
    }
  } else {
    search.style.display = "none";

    if(innerWidth < 800) {
      move.style.marginTop = "50px";
    } else {
      move.style.marginTop = "50px";

      if(moveSecond) {
        moveSecond.style.marginTop = "50px";
      }
    }
  }
});

menuButton.addEventListener('click', () => {
  if(menu.style.display == "none") {
    menu.style.display = "block";
  } else {
    menu.style.display = "none";
  }
});