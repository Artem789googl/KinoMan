const modalbuttonsfilm = document.querySelectorAll("[data-modal-button]");
const closeButtonVideo = document.getElementById("close");
const closeButton = document.querySelector("[close-button]");
const modalZalText = document.getElementById('zal_text');
modalvideo = document.getElementById("videoModal");

const ticketTypeSelects = document.querySelectorAll('.ticket_type');
const prices_type = document.querySelectorAll('.price_type');


function updatePrices() {
    ticketTypeSelects.forEach((select, index) => {
        const price = parseInt(select.value);
        
        prices_type[index].textContent = `${price} руб`;
    });
}

ticketTypeSelects.forEach(select => {
    select.addEventListener('change', updatePrices);
});


closeButton.addEventListener('click', function(){
    //У родителя данной кнопки берём модуль окна
    const modal = this.closest('[data-modal]');

    //Закрываем данный модуль
    modal.classList.add('hidden');
});

closeButtonVideo.addEventListener('click', function(){
    document.getElementById('video_player').contentWindow.postMessage('{"event":"command","func":"pauseVideo","args":""}', '*');
});

document.addEventListener('DOMContentLoaded', function () {

    modalbuttonsfilm.forEach(function(item) {
        item.addEventListener('click', function () {
            // Извлекаем данные из атрибутов кнопки
            const seansId = item.getAttribute('data-seans-id');
            const filmId = item.getAttribute('data-film-id');
            const zal = item.getAttribute('data-zal');
            const dataTime = item.getAttribute('data-date-time'); // Получаем полную дату и время

            // Заполняем скрытые поля в модальном окне
            document.getElementById('seans_id').value = seansId;
            document.getElementById('film_id').value = filmId;
            document.getElementById('data_time').value = dataTime;
            document.getElementById('zal_text').textContent = `Зал: ${zal}`;
        }, updatePrices());
    });

    // Обработчик для кнопки "Подтвердить"
    document.getElementById('confirm_button').addEventListener('click', function() {
        // Получаем данные из формы
        const seansId = document.getElementById('seans_id').value;
        const filmId = document.getElementById('film_id').value;
        const dataTime = document.getElementById('data_time').value;
        const zal = document.getElementById('zal_text').textContent.split(":")[1].trim();  // Получаем значение зала
        const row = document.getElementById('ryad').value;
        const seat = document.getElementById('mesto').value;
        const typeBilet = document.getElementById('ticket_type').options[document.getElementById('ticket_type').selectedIndex].text;  // Текст типа билета
        const stoimost = document.getElementById('stoimost').innerText.replace(' руб', '');
    
        // Делаем запрос на сервер через fetch для добавления в корзину
        fetch('add_to_cart.php', {
            method: 'POST',
            body: new URLSearchParams({
                seans_id: seansId,
                film_id: filmId,
                data_time: dataTime,
                zal: zal,
                row: row,
                seat: seat,
                type_bilet: typeBilet,
                stoimost: stoimost
            }),
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
                // Закрыть модальное окно, если добавление прошло успешно
                document.getElementById('seans_modal').classList.add('hidden');
    
                // Обновляем UI: убираем занятое место и ряд
                removeSeat(zal, row, seat);
            } else {
                alert(data.message);
            }
        })
        .catch(error => {
            console.error('Ошибка при отправке данных:', error);
        });
    });
});







