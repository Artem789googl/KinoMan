/*
Программа : название программы: CinemaStar:sumbit.js
Лабораторная работа №9 по профессиональному модулю МДК 02.01 Технология разработки программного обеспечения
Тема «Инспекция программного кода на предмет соответствия стандартам кодирования»
Язык программирования: JavaScript
Разработал: Шум Артём
Дата: 24.11.24
____________________________________________________________________________
Задача: Отправить запрос в БД, приэтом нужно выводить данные об ошибке или об успехе процесса;
____________________________________________________________________________
Переменные, используемые в программе:
formData - хранит данные с формы.

Процедуры:
Процедура на форме входа - процедура для нахождение модального окна и его открытия;
Процедура на кнопке закрития - процедура для нахождение модального окна и его закрытия.
Процедура на окне - процедура, которая срабатывает при открытие онка страницы и служит для выгрузки фильмов на страницу
Процедура на поле ввода - процедура, которая срабатывает при изменение даты в себе и выгрузке фильмов по этой дате
*/

//Назначение на форму входа событие на отправку
document.getElementById('log_form')?.addEventListener('submit', function (event) {
    event.preventDefault(); // Отменяем стандартное поведение формы

    const formData = new FormData(this); // Собираем данные формы

    fetch('../content/login.php', {
        method: 'POST',
        body: formData
    })
    .then(response => {
        // Проверяем статус ответа
        if (!response.ok) {
            throw new Error(`HTTP error! Status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        console.log(data); // Логируем ответ от сервера

        if (data.success) {
            // Если вход успешен, можно перенаправить пользователя
            alert(data.message);
            window.location.href = '../content/accountpage.php';
        } else {
            // Если ошибка, выводим сообщение
            document.getElementById('errorMessage').textContent = data.message;
        }
    })
    .catch(error => {
        console.error('Ошибка:', error); // Логируем ошибку в консоль
        document.getElementById('errorMessage').textContent = 'Произошла ошибка, попробуйте снова.';
    });
});

//Назначение на форму регистрации событие на отправку
document.getElementById('log_reg_form')?.addEventListener('submit', function (event) {
    event.preventDefault(); // Отменяем стандартное поведение формы

    const formData = new FormData(this); // Собираем данные формы

    fetch('../content/registr.php', {
        method: 'POST',
        body: formData
    })
    .then(response => {
        // Проверяем статус ответа
        if (!response.ok) {
            throw new Error(`HTTP error! Status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        console.log(data); // Логируем ответ от сервера

        if (data.success) {
            // Если вход успешен, можно перенаправить пользователя
            alert(data.message);
            window.location.href = '../content/accountpage.php';
        } else {
            // Если ошибка, выводим сообщение
            document.getElementById('errorMessageReg').textContent = data.message;
        }
    })
    .catch(error => {
        console.error('Ошибка:', error);
        document.getElementById('errorMessageReg').textContent = 'Произошла ошибка, попробуйте снова.';
    });
});


//Поиск на главной страницы объекта инпута с типом дата
input_searc_date = document.querySelector('.input-search'); 

//Названчение при открытие страницы действия по выгрузке фильмов
window.addEventListener('load', function () {
    const selectedDate = input_searc_date.value;

    fetch(`../content/films.php?date=${selectedDate}`)
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! Status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            const container = document.querySelector('.cards');
            container.innerHTML = ''; // Очищаем контейнер перед рендером новых фильмов

            if (data.success && data.films.length > 0) {
                data.films.forEach(film => {
                    const filmCard = `
                        <div class="card" id="film-${film.id_film}">
                            <a href="../content/infofilm.php?id_film=${film.id_film}" class="card__header">
                                <img src="img/${film.poster}" alt="${film.nazvanie}" class="card__img">
                            </a>
                            <div class="card__main">
                                <div class="card__tittle">
                                    <h3 class="tittle">${film.nazvanie}</h3>
                                    <div class="card__desc">
                                        <strong class="card__reit">4.0</strong> <!-- Рейтинг можно заменить на реальный -->
                                        <span class="card__prodol">${film.prodolzhitelnost} минут</span>
                                    </div>
                                </div>
                                <span class="tittle__time">
                                    <a href="#" class="time__time">
                                    ${film.seans_times.split(', ').map(time => time.substring(0, 5)).join('<br>')}</a>

                                </span>
                            </div>
                        </div>
                    `;
                    container.innerHTML += filmCard;
                });
            } else {
                container.innerHTML = `<p>Фильмы на выбранную дату отсутствуют.</p>`;
            }
        })
        .catch(error => {
            console.error('Ошибка:', error);
        });
});



//При изменение инпута изменятеся и выгружаемые фильмы
input_searc_date?.addEventListener('change', function () {
    const selectedDate = this.value;

    fetch(`../content/films.php?date=${selectedDate}`)
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! Status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            const container = document.querySelector('.cards');
            container.innerHTML = ''; // Очищаем контейнер перед рендером новых фильмов

            if (data.success && data.films.length > 0) {
                data.films.forEach(film => {
                    const filmCard = `
                        <div class="card" id="film-${film.id_film}">
                            <a href="content/infofilm.php?id_film=${film.id_film}" class="card__header">
                                <img src="img/${film.poster}" alt="${film.nazvanie}" class="card__img">
                            </a>
                            <div class="card__main">
                                <div class="card__tittle">
                                    <h3 class="tittle">${film.nazvanie}</h3>
                                    <div class="card__desc">
                                        <strong class="card__reit">4.0</strong> <!-- Рейтинг можно заменить на реальный -->
                                        <span class="card__prodol">${film.prodolzhitelnost} минут</span>
                                    </div>
                                </div>
                                <span class="tittle__time">
                                    <a href="#" class="time__time">
                                    ${film.seans_times.split(', ').map(time => time.substring(0, 5)).join('<br>')}</a>
                                </span>
                            </div>
                        </div>
                    `;
                    container.innerHTML += filmCard;
                });
            } else {
                container.innerHTML = `<p>Фильмы на выбранную дату отсутствуют.</p>`;
            }
        })
        .catch(error => {
            console.error('Ошибка:', error);
        });
        
});

//Указание инпоту дате сегодняшнее число
input_searc_date.value = new Date().toISOString().split('T')[0]; // Формат yyyy-mm-dd
