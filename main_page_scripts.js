
 function callsearc(){
    alert('это будет позже. зуб даю')
 }
 function callcart(){
    alert('это будет позже. зуб даю')
 }
 function callprod(){
    alert('это будет позже')
 }



const dopMenuButton = document.querySelector('.dopMenuButton');
const menuButton = document.querySelector('.menu-btn'); 
const sideMenu = document.getElementById('side-menu'); 
const dopSideMenu = document.getElementById('DopSide-menu'); 
const overlay = document.getElementById('overlay');

function openMenu() {
    sideMenu.classList.add('active'); 
    overlay.classList.add('active'); 
}


function closeMenu() {
    sideMenu.classList.remove('active'); 
    overlay.classList.remove('active'); 
}

menuButton.addEventListener('click', openMenu); 
overlay.addEventListener('click', closeMenu); 


function openDopMenu(){
    dopSideMenuMenu.classList.remove('active');
    overlay.classList.remove('active');
}
function closeDopMenu() {
    dopSideMenu.classList.remove('active'); 
    overlay.classList.remove('active'); 
}

const images = [
    'pic1.jpg', 
    'pic2.jpg', 
    'pic3.jpg', 
    'pic4.jpg'  
];

let currentImageIndex = 0;
const backgroundSlider = document.getElementById('background-slider');
const thumbnailsContainer = document.getElementById('thumbnails');


function changeBackgroundImage(index) {
    backgroundSlider.style.backgroundImage = `url(${images[index]})`;
    currentImageIndex = index;


    document.querySelectorAll('.thumbnails img').forEach((thumbnail, i) => {
        thumbnail.classList.toggle('active', i === index);
    });
}


function autoChangeImage() {
    currentImageIndex = (currentImageIndex + 1) % images.length;
    changeBackgroundImage(currentImageIndex);
}


images.forEach((image, index) => {
    const imgElement = document.createElement('img');
    imgElement.src = image;
    imgElement.alt = `Thumbnail ${index + 1}`;

 
    imgElement.addEventListener('click', () => {
        changeBackgroundImage(index);
    });

    thumbnailsContainer.appendChild(imgElement);
});


changeBackgroundImage(currentImageIndex);


setInterval(autoChangeImage, 5000); 
document.querySelectorAll('.product-card').forEach(card => {
    card.addEventListener('mouseenter', () => {
     
    });
    card.addEventListener('mouseleave', () => {
      
    });
  });

  
  document.getElementById('editButton').addEventListener('click', function() {
    var form = document.getElementById('editForm');
    if (form.style.display === 'none') {
        form.style.display = 'block';
    } else {
        form.style.display = 'none';
    }
});