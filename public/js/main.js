const searchButton = document.querySelector('.search-button');
const search = document.querySelector('.search');
const submit = document.querySelector('.submit');

window.onload = () => {
  search.style.display = "none";
}

searchButton.addEventListener('click', () => {
  if(search.style.display == "none") {
    search.style.display = "block";

    if(window.innerWidth < 800) {
      submit.style.marginTop = "100px";
    }
  } else {
    search.style.display = "none";
    
    if(window.innerWidth < 800) {
      submit.style.marginTop = "50px";
    }
  }
});