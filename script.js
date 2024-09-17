const images = document.querySelectorAll(".banniere img");
let currentImage = 0;

setInterval(() => {
  images[currentImage].classList.remove("active");
  currentImage = (currentImage + 1) % images.length;
  images[currentImage].classList.add("active");
}, 3000);