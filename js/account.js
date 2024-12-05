document.getElementById('add_film_log')?.addEventListener('submit', function (event) {
    event.preventDefault();

    const formData = new FormData(this);

    // Логируем данные формы для проверки
    for (let [key, value] of formData.entries()) {
        console.log(`${key}:`, value);
    }

    fetch('../content/add_film.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            document.getElementById('add_film_log').reset();
        } else {
            alert(data.message);
        }
    })
    .catch(error => {
        console.error('Ошибка:', error);
        alert('Произошла ошибка при отправке данных.');
    });
});



document.getElementById("icon_edit").addEventListener('click', function() {
    const input = document.getElementById('editable-input');
    input.disabled = !input.disabled; // Переключение редактирования
    if (!input.disabled) input.focus(); // Установка фокуса при включении редактирования
});




document.addEventListener('DOMContentLoaded', function () {
    console.log("DOMContentLoaded сработал!");

    // Получаем данные пользователя
    fetch('account.php', {
        method: 'GET',
        headers: { 'Content-Type': 'application/json' }
    })
    .then(response => response.json())
    .then(data => {
        console.log('Данные пользователя:', data);
        if (data.success) {
            document.getElementById('login').textContent = "Логин: " + data.data.login;
            document.getElementById('editable-input').value = "ФИО: " + data.data.fio;
            document.getElementById('contact').textContent = "Контакты: " + data.data.contact;
            document.getElementById('icon_edit').src = data.data.ikonka || "../img/AccIcon.png";

            if (data.data.privelegia === 'user') {
                document.querySelector(".button_admin_pol").classList.add("hidden");
                document.querySelector(".accord_film").classList.add("hidden");

                // Получаем заказы пользователя
                fetch('../content/get_zakaz.php')
                .then(response => response.json())
                .then(ordersData => {
                    console.log('Данные о заказах:', ordersData);

                    if (ordersData.success) {
                        const tbody = document.querySelector('.list_film.zakaz tbody');

                        if (!tbody) {
                            console.error('Элемент tbody не найден.');
                            return;
                        }

                        tbody.innerHTML = ''; // Очищаем текущие данные

                        ordersData.data.forEach(order => {
                            const row = document.createElement('tr');
                            row.setAttribute('data-order-id', order.id_zakaz);

                            // Заполняем строку таблицы
                            row.innerHTML = `
                                <td><button data-modal-button="bilet" data-order-id="${order.id_zakaz}"> ${order.id_zakaz} </button></td>
                                <td>${order.data_sozdaniya}</td>
                                <td>${order.kolichestvo_biletov}</td>
                                <td>${order.summa} руб</td>
                            `;

                            // Добавляем строку в tbody
                            tbody.appendChild(row);
                        });

                        // Добавляем обработчик для кнопок для получения подробных данных о заказах
                        const zakazButtons = document.querySelectorAll('[data-modal-button="bilet"]');
                        zakazButtons.forEach(function(item) {
                            item.addEventListener('click', function() {
                                const orderId = this.dataset.orderId;
                                fetchOrderDetails(orderId);
                            });
                        });
                    } else {
                        console.error('Ошибка загрузки заказов:', ordersData.message);
                        alert('Не удалось загрузить заказы: ' + ordersData.message);
                    }
                })
                .catch(error => {
                    console.error('Ошибка при запросе заказов:', error);
                    alert('Произошла ошибка при загрузке заказов.');
                });
            } else if (data.data.privelegia === 'admin') {
                document.querySelectorAll(".accord_film")[1].classList.add("hidden");
            }
        } else {
            alert(data.message);
        }
    })
    .catch(error => console.error('Ошибка:', error));

    // Функция для получения подробных данных о заказе
    function fetchOrderDetails(orderId) {
        fetch(`../content/get_order_details.php?id=${orderId}`)
        .then(response => response.json())
        .then(orderDetailsData => {
            if (orderDetailsData.success) {
                console.log('Детали заказа:', orderDetailsData);

                // Получаем tbody для вывода данных в модальное окно
                const tbody = document.querySelector('#bilet .list_film tbody');
                if (!tbody) {
                    console.error('Тело таблицы не найдено в модальном окне.');
                    return;
                }

                tbody.innerHTML = ''; // Очищаем старые данные

                orderDetailsData.data.forEach(detail => {
                    const row = document.createElement('tr');

                    row.innerHTML = `
                        <td>${detail.film_nazvanie}</td>
                        <td>${detail.id_zakaz}</td>
                        <td>${detail.zal}/${detail.ryad}/${detail.mesto}</td>
                        <td>${detail.tip_bileta}</td>
                        <td>${detail.data_i_vremya}</td>
                        <td>${detail.stoimost} руб</td>
                    `;

                    tbody.appendChild(row);
                });

                // Показываем модальное окно
                const modal = document.querySelector('#bilet');
                modal.classList.remove('hidden');
            } else {

                console.error('Ошибка загрузки данных о заказе:', orderDetailsData.message);
                alert('Не удалось загрузить подробности о заказе: ' + orderDetailsData.message);
            }
        })
        .catch(error => {
            console.error('Ошибка при запросе подробностей заказа:', error);
            alert('Произошла ошибка при загрузке данных о заказе.');
        });
    }
}, loadFilms);




const dateInput = document.querySelector('#film-date');
const filmList = document.querySelector('#film-list');
dateInput.value =  new Date().toISOString().split('T')[0]; // Формат yyyy-mm-dd
// Загрузка фильмов по дате
function loadFilms(date) {
    fetch(`../content/get_infofilm.php?action=get_films_by_date&date=${date}`)
        .then(response => response.json())
        .then(data => {
            filmList.innerHTML = '';
            if (data.success && data.films.length > 0) {
                data.films.forEach(film => {
                    const filmElement = document.createElement('div');
                    filmElement.className = 'del_movie';
                    filmElement.innerHTML = `
                        <img src="../img/${film.poster}" alt="${film.nazvanie}" class="film_poster">
                        <button class="button" data-film-id="${film.id_film}" data-film-date="${date}">Удалить фильм</button>
                    `;
                    filmList.appendChild(filmElement);
                });
            } else {
                filmList.innerHTML = '<p>Нет фильмов на выбранную дату.</p>';
            }
        })
        .catch(error => console.error('Ошибка загрузки фильмов:', error));
}


// Удаление фильма
filmList.addEventListener('click', (e) => {
    if (e.target.tagName === 'BUTTON') {
        const filmId = e.target.getAttribute('data-film-id');
        const date = e.target.getAttribute('data-film-date');
        console.log(filmId, date);
        if (confirm('Вы точно хотите удалить фильм?')) {
            fetch('../content/delete_film.php?action=delete_film', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ film_id: filmId, date: date })
            })
                .then(response => {
                    return response.json(); // преобразуем ответ в JSON
                })
                .then(data => {
                    console.log(data); // Логируем ответ сервера
                    alert(data.message);
                    if (data.success) loadFilms(date); // Обновляем список
                })
                .catch(error => {
                    console.error('Ошибка удаления фильма:', error);
                });
            
        }
    }
});

// При изменении даты загружаем фильмы
dateInput.addEventListener('change', () => {
    const date = dateInput.value;
    loadFilms(date);
});


document.addEventListener('DOMContentLoaded', loadFilms);


