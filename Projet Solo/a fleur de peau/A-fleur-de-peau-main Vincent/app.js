const links = document.querySelectorAll("nav li");

icons.addEventListener("click", () => {
    nav.classList.toggle("active");
});

links.forEach((link) => {
    link.addEventListener("click", () => {
        nav.classList.remove("active");
    });
});

const cardWrapper = document.querySelector('.card-wrapper')
const widthToScroll = cardWrapper.children[0].offsetWidth
const arrowPrev = document.querySelector('.arrow.prev')
const arrowNext = document.querySelector('.arrow.next')
const cardBounding = cardWrapper.getBoundingClientRect()
const cardImageAndLink = cardWrapper.querySelectorAll('img, a')
let currScroll = 0
let initPos = 0
let clicked = false

cardImageAndLink.forEach(item=> {
  item.setAttribute('draggable', false)
})

arrowPrev.onclick = function() {
  cardWrapper.scrollLeft -= widthToScroll
}

arrowNext.onclick = function() {
  cardWrapper.scrollLeft += widthToScroll
}

cardWrapper.onmousedown = function(e) {
  cardWrapper.classList.add('grab')
  initPos = e.clientX - cardBounding.left
  currScroll = cardWrapper.scrollLeft
  clicked = true
}

cardWrapper.onmousemove = function(e) {
  if(clicked) {
    const xPos = e.clientX - cardBounding.left
    cardWrapper.scrollLeft = currScroll + -(xPos - initPos)
  }
}

cardWrapper.onmouseup = mouseUpAndLeave
cardWrapper.onmouseleave = mouseUpAndLeave

function mouseUpAndLeave() {
  cardWrapper.classList.remove('grab')
  clicked = false
}

window.onload = function() {filterPhotos('all');};

function filterPhotos(tag) {
    var photos = document.querySelectorAll('.photo');
    
    photos.forEach(function(photo) {
        if (tag === 'all' || photo.classList.contains(tag)) {
            photo.style.display = 'flex';
        } else {
            photo.style.display = 'none'; 
        }
    });
}

document.getElementById('toggleButton').addEventListener('click', function() {
  var hiddenParagraph = document.getElementById('hiddenParagraph');

  if (hiddenParagraph.style.display === 'none') {
    hiddenParagraph.style.display = 'block';
    this.innerHTML = '-';
  } else {
    hiddenParagraph.style.display = 'none';
    this.innerHTML = '+';
  }
});