const button = document.querySelector('#Button_Kontakt');

button.addEventListener('click', function(){
    const object = document.querySelector("[data-modal]");

    object.classList.remove('hidden');
});
