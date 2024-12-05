/*
Программа : название программы: CinemaStar:openwindow.js
Лабораторная работа №9 по профессиональному модулю МДК 02.01 Технология разработки программного обеспечения
Тема «Инспекция программного кода на предмет соответствия стандартам кодирования»
Язык программирования: JavaScript
Разработал: Шум Артём
Дата: 09.11.24
____________________________________________________________________________
Задача: С помощью определённых кнопок на странице открывать и закрывать модальные окна. Для любого окна предусмотерть уникальную особенность;
____________________________________________________________________________
Переменные, используемые в программе:
modalbuttons - все кнопки открытия на странице;
modalClosebuttons - все кнопки закрытия на странице;
allModals - все модальные окна на странице;
modalId - значение в модуле кнопок открытия;
modal - модальное окно;
a - логическая переменная, значение которой зависит от значения S.

Процедуры:
Процедура на кнопке открытия - процедура для нахождение модального окна и его открытия;
Процедура на кнопке закрития - процедура для нахождение модального окна и его закрытия;
Процедура на модульных окнах - процедура для закрытия модальных окон.

*/

//Массив, который хранит все кнокпи с модулем data-modal-button на странице
const modalbuttons = document.querySelectorAll("[data-modal-button]");

//Массив, который хранит все кнокпи заурытия с модулем modal-close-button на странице
const modalClosebuttons = document.querySelectorAll("[modal-close-button]");

//Массив, который хранит все модальные кнопкуи с модулем data-modal на странице
const allModals = document.querySelectorAll("[data-modal]");

//Цикл Foreach, который перебирает все кнопки в массиве modalbuttons
modalbuttons.forEach(function (item){
    //Добавления на кнопку событие по клику
    item.addEventListener('click', function(){
        //Закрытие всех предыдущих модальных окон
        allModals.forEach(function (item){
            item.classList.add('hidden');
        });

        //Берёт у модуля значение
        const modalId = this.dataset.modalButton;
        //Данная переменная хранит модальное окно
        const modal = document.querySelector('#'+modalId);
        //Уникальный алгоритм для форма с названием modal-2
        if (modal?.id == "modal-2"){
            modal.classList.add('move_menu_panel');
        }


        //Убираем css свойство у модального окна
        modal.classList.remove('hidden');

        //Блокирует клик для текста в модальном окне
        modal.querySelector('.mainblock').addEventListener('click', function(e){
            e.stopPropagation();
        });

    });
});

//Цикл Foreach, который перебирает все кнопки закрытия в массиве 
modalClosebuttons.forEach(function (item){
    //Добавления на кнопку закрития событие по клику 
    item.addEventListener('click', function(){
        //У родителя данной кнопки берём модуль окна
        const modal = this.closest('[data-modal]');

        //Закрываем данный модуль
        modal.classList.add('hidden');
    });
});

//Цикл Foreach, который перебирает все модальные окна в массиве 
allModals.forEach(function (item){
    //Добавления на модальное окно событие по клику 
    item.addEventListener('click', function(){
        //Прячем данное окно
        this.classList.add('hidden');
    });
});