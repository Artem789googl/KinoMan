<?php
    session_start();
    if (isset($_SESSION['username'])) {
        $username = $_SESSION['username'];
    } else {
        $username = null;
    }
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Регистрация</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../style/style.css">
</head>
<body>
    <div id="modal-5" data-modal class="hidden registr_section">
        <div class="mainblock panel_registr">
            <form class = "form_registr" id = "add_film_log" method="post" enctype="multipart/form-data">

            <h2>Добавить фильм</h2>
            <label for="imageUpload">Загрузить картинку:</label><br>
            <input type="file" id="imageUpload" name="imageUpload" accept="image/png, image/jpeg" ><br>

            <label for="filmTitle">Название фильма:</label><br>
            <input type="text" id="filmTitle" name="filmTitle"><br>
      
            <label for="ageRating">Описание:</label><br>
            <input type="text" id="description" name="description"><br>

            <label for="ageRating">Возрастное ограничение:</label><br>
            <input type="text" id="ageRating" name="ageRating"><br>
      
            <label for="releaseYear">Год выпуска:</label><br>
            <input type="number" id="releaseYear" name="releaseYear"><br>
      
            <label for="production">Производство:</label><br>
            <input type="text" id="production" name="production"><br>
      
            <label for="director">Режиссёр:</label><br>
            <input type="text" id="director" name="director"><br>
      
            <label for="duration">Продолжительность (мин):</label><br>
            <input type="number" id="duration" name="duration"><br>
      
            <label for="trailerLink">Ссылка на трейлер:</label><br>
            <input type="url" id="trailerLink" name="trailerLink"><br>

            <label for="zal">Зал:</label>
            <select id="zal" name="zal">
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="4">4</option>
                <option value="5">5</option>
                <option value="6">6</option>
                <option value="7">7</option>
            </select><br>

            <label for="dateivremya">Дата:</label><br>
            <input type="date" id="dateivremya" name="dateivremya"><br>

            <label for="vremya">Время:</label><br>
            <input type="time" id="vremya" name="vremya"><br>

            <button  type = "sumbit">Добавить фильм</button>
          </form>
        </div>
    </div>

    <div id = "modal-1" data-modal class="hidden kontack_section">
        <div class="mainblock kontackt_block">
            <div class="backkont">
                <nav class="tittleBlock">
                    Контакты
                </nav>
            </div>
            <div class="consistblock">
                <p class="descblock">
                    Центральный офис: <br>
    
                    119019, Москва, ул. Новый Арбат, 24. <br>
    
                    График работы: <br>
    
                    понедельник - четверг с 09:00 до 18:00 <br>
    
                    пятница с 09:00 до 16:00 <br>
    
                    суббота, воскресенье, праздничные дни — выходные
                </p>
                <!-- /.desc -->
                 <button modal-close-button class="reg__button">
                    Закрыть
                 </button>
            </div>
        </div>

    </div>

    <div id = "modal-2" data-modal class="hidden panel_menu">
        <div class="mainblock panel__menu">
            <button modal-close-button class ="button close_button"></button>
            <div class="menu_text">
                <a href="#" class="text_menu">главная</a>
                <a href="#" class="text_menu">настройки</a>
                <a href="#" class="text_menu">контакты</a>
                <a href="#" class="text_menu">новости</a>

                <?php if ($username): ?>
                    <button class="button_a text_menu" data-modal-button = "cart">корзина</button>
                    <a href="./content/logout.php" class="text_menu">выйти</a>
                <?php endif; ?>
            </div>

        </div>
    </div>
    
    <div id="bilet" data-modal class="hidden registr_section">
        <div class="mainblock panel_bilet">
            <form class="form_registr" id="cart_form" method="post">
                <summary class="tittle_accord">Билеты</summary>
                <div class="list_film">
                    <table>
                        <thead>
                            <tr>
                                <th>Название</th>
                                <th>Номер билета</th>
                                <th>Зал Ряд Место</th>
                                <th>Тип билета</th>
                                <th>Дата и время</th>
                                <th>Стоимость</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Данные будут загружаться сюда -->
                        </tbody>
                    </table>
                </div>
            </form>
        </div>
    </div>

    <div id = "cart" data-modal class="hidden registr_section">
        <div class="mainblock cart">
            <form class = "form_registr" id = "cart_form" method="post">
                <summary class="tittle_accord">Корзина</summary>
                <div class="list_film cart_list">
                    <table>
                        <thead>
                            <tr>
                                <th>Название</th>
                                <th>Зал Ряд Место</th>
                                <th>Тип билета</th>
                                <th>Дата и время</th>
                                <th>Стоимость</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Миньёны</td>
                                <td>3/4/12</td>
                                <td>Тип билета</td>
                                <td>12:40 <br>
                                    14.12.2024
                                </td>
                                <td class="price">700 руб</td>
                                <td><button class="delete-btn" data-cart-id="<?php echo $cartItem['id_cart']; ?>">
                                <img src="../img/bin.png" width="30px" height="30px">
                                </button></td>
                            </tr>
                        </tbody>
                        <tbody>
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td>Итого:</td>
                                <td class="total-price">700 р</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <button type="submit" id = "oplata" class="reg__button cart_button_oplata">
                    Оплатить
                </button>           
            </form>
        </div>
    </div>


    <header class="section_header">
        <div class="container block">
            <a href="../index.php" class="logo">
                <img src="../img/logo.svg" alt="логотип" class="logo__img">
            </a>
            <!-- /.logo -->
            <div class = "buttons">

                <button data-modal-button = "modal-2" class="button button__cart">
                </button>
            </div> 
            <!-- /.buttons -->
        </div>
        <!-- /.container /.block-->
    </header>
    <!-- /.scetion_header -->
    <section class="main_sec_acc">
        <div class="head_sec_acc">
            <div class="info_pol">
                <div class = "image-container">
                    <img id = "AccIcon" class ="current-image" src="../img/AccIcon.png" alt="Текущее изображение">
                </div>                  
                <input type="file" class="hidden image-input" name="avatar" class = "input_image" accept="image/png, image/jpeg" />
                <div class="info_text_pol">
                    <div class="rectungleGray login_text_pol">
                        <nav class="acc_text" id = "login">
                            LOGIN
                        </nav>
                    </div>
                    <div id = "fio" class="rectungleGray name_text_pol">
                        <div class="input-container">
                            <input type="text" class = "input_fio" id="editable-input" disabled value="ФИО">
                            <img 
                            src="../img/edit.png" 
                            alt="edit" 
                            class="icon" 
                            width="20" 
                            height="20" 
                            id = "icon_edit"
                            >
                        </div>
                        
                    </div>
                    <div id = "contact" class="rectungleGray mail_text_pol acc_text">
                        <nav class="acc_text" id = "contact">
                            mail@mai.ru
                        </nav>
                    </div>
                </div>

            </div>
        </div>

        <div class="button_admin_pol" data-modal-button = "modal-5">
            <button class="button add_film_button">
                Добавить фильм
            </button>
        </div>
        <details class="accord_film">
            <input type="date" class="input input-search" value="2024-10-15" id="film-date">
            <summary class="tittle_accord">фильмы</summary>
            <div class="list_film" id="film-list">
                <!-- <div class="del_movie">
                    <img src="../img/Gadki4.png" alt="Первый фильм" class="film_poster">
                    <button class="button">
                        Удалить фильм
                    </button>
                </div>
                <div class="del_movie">
                    <img src="../img/Cat.png" alt="Второй фильм" class="film_poster">
                    <button class="button">
                        Удалить фильм
                    </button>
                </div> -->

                
            </div>
        </details>


        <details class="accord_film">
            <summary class="tittle_accord">Заказы</summary>
            <div class="list_film zakaz">
                <table class = "table">
                    <thead>
                        <tr>
                            <th>Номер заказа</th>
                            <th>Дата оформления</th>
                            <th>Количество билетов</th>
                            <th>Конечная сумма</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Здесь будут отображаться данные -->
                    </tbody>
                </table>
            </div>
        </details>

    </section>
    
    
    <footer class="section_footer">
        <div class="container footer_block">
            <a href="../index.php" class="logo">
                <img src="../img/logo.svg" alt="логотип" class="logo__img">
            </a>
            <div class="info__footer">
                <nav class="navigation__footer">
                    <a href="../index.php" class="nav-item">главная</a>
                    <a href="#" class="nav-item">новости</a>
                    <button data-modal-button = "modal-1" class="nav-item button_Kontakt">контакты</button>
                </nav>
                <hr class = "line__footer">
                <a href="#main" class="prava__foot">
                    Все права защищены © 2024–2025 «CinemaStar»
                </a>
            </div>

            <div class="info__footer__message">
                <div class="socails">
                    <a href="#" class="social-link">
                        <img src="../img/VK.svg" alt="социальная сеть" class="social-img">
                    </a>
                    <!-- /.social-link -->
                    <a href="#" class="social-link">
                        <img src="../img/Telegram.svg" alt="социальная сеть" class="social-img">
                    </a>
                    <!-- /.social-link -->
                </div>
            </div>

             <!-- /.socails -->

        </div>

    </footer>
    <!-- /.section_footer -->
    <script src="../js/openwindow.js"></script>
    <script src="../js/imginput.js"></script>
    <script src="../js/account.js"></script>
</body>
</html>