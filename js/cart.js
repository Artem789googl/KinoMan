const prices = document.querySelectorAll('.price');
const totalPriceElement = document.querySelector('.total-price');

document.addEventListener('DOMContentLoaded', function () {
    // Получаем корзину при загрузке страницы
    fetch('../content/get_cart.php')  // Скрипт для получения данных корзины
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                updateCartTable(data.data);
            }
        })
        .catch(error => console.error('Ошибка при загрузке корзины:', error));
});

function updateCartTable(cartItems) {
    const tbody = document.querySelector('#cart .list_film tbody');
    const totalPriceElement = document.querySelector('.total-price');
    let totalPrice = 0;

    // Очистить текущие данные в таблице
    tbody.innerHTML = '';

    cartItems.forEach(item => {
        // Рассчитываем стоимость
        const price = item.stoimost;
        totalPrice += price;

        // Добавляем новую строку в таблицу
        const row = document.createElement('tr');
        row.innerHTML = `
            <td>${item.naz_film}</td>
            <td>${item.zal}/${item.ryad}/${item.mesto}</td>
            <td>${item.type_bilet}</td>
            <td>${item.data_i_vremya}</td>
            <td class="price">${price} руб</td>
            <td><button class="delete-btn" data-cart-id="${item.id_cart}">
                <img src="../img/bin.png" width="30px" height="30px">
            </button></td>
        `;
        tbody.appendChild(row);
    });

    // Обновляем итоговую стоимость
    totalPriceElement.innerText = `${totalPrice} руб`;
}

// Используем делегирование событий для кнопок удаления
document.querySelector('#cart .list_film').addEventListener('click', function (e) {
    if (e.target && e.target.closest('.delete-btn')) {
        const cartId = e.target.closest('.delete-btn').getAttribute('data-cart-id');
        
        // Подтверждаем удаление
        if (confirm("Вы уверены, что хотите удалить этот элемент из корзины?")) {
            // Отправляем запрос на сервер для удаления
            fetch('../content/delete_from_cart.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: new URLSearchParams({
                    cart_id: cartId
                }),
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Удаляем строку из таблицы
                    alert('Элемент удален из корзины');
                    e.target.closest('tr').remove(); // Удаляем строку из таблицы
                } else {
                    alert('Ошибка при удалении элемента');
                }
            })
            .catch(error => {
                console.error('Ошибка при отправке запроса:', error);
            });
        }
    }
});

document.getElementById('oplata')?.addEventListener('click', function (event) {
    // Отключаем стандартное поведение кнопки (отправку формы)
    event.preventDefault();

    // Получаем список фильмов из таблицы корзины
    const filmNames = Array.from(document.querySelectorAll('#cart .list_film tbody tr td:first-child'))
        .map(td => td.textContent.trim())
        .filter(name => name !== ''); // Убираем пустые значения, если есть

    // Проверяем, есть ли фильмы в корзине
    if (filmNames.length === 0) {
        alert('Ваша корзина пуста.');
        return;
    }

    // Создаем строку с перечислением фильмов
    const filmsList = filmNames.join(', ');

    // Подтверждение оплаты
    if (!confirm(`Вы точно хотите оплатить билеты на: ${filmsList}?`)) {
        return; // Если пользователь нажал "Отмена", ничего не делаем
    }

    // Отправляем запрос на сервер для оплаты
    fetch('../content/pay_cart.php', { // Укажите путь к вашему скрипту PHP
        method: 'POST',
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Успешная оплата
                alert('Оплата прошла успешно!');
                // Очищаем интерфейс корзины
                document.querySelector('#cart .list_film tbody').innerHTML = '';
                document.querySelector('.total-price').textContent = '0 руб';
            } else {
                // Ошибка оплаты
                alert(data.message);
            }
        })
        .catch(error => console.error('Ошибка при оформлении заказа:', error));
});



