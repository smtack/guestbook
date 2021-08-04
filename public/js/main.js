const menuButton = document.querySelector('.menu-button');
const menu = document.querySelector('.menu');

window.onload = () => {
  menu.style.display = "none";
}

menuButton.addEventListener('click', () => {
  if(menu.style.display == "none") {
    menu.style.display = "block";
  } else {
    menu.style.display = "none";
  }
});