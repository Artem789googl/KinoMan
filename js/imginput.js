const imageContainer = document.querySelector('.image-container');
const currentImage = document.querySelector('.current-image');
const imageInput = document.querySelector('.image-input');

imageContainer.addEventListener('click', function(){
  imageInput.click(); // Срабатывает выбор файла
});

imageInput.addEventListener('change', function(event) {
  const file = event.target.files[0];
  if (file) {
    const reader = new FileReader();
    reader.onload = (e) => {
      currentImage.src = e.target.result;
    };
    reader.readAsDataURL(file);
  }
});