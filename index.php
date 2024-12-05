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
    <title>CinemaStar</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../style/style.css">

</head>
<body>

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

    <div id = "modal-3" data-modal class="hidden registr_section">
        <div class="mainblock panel_registr">
            <form class = "form_registr" id = "log_form" method="post">
                <h1 class="reg__tittle">Вход</h1>
                <input autocomplete  required type="text" class="reg_inp reg_input" name = "login" placeholder = "логин">
                <input autocomplete required type="password" class="reg_inp reg_input" name = "password" placeholder = "пароль">
                <div id = "errorMessage" class="errormessage"></div>

                <button class="enter__button">
                    войти
                </button>

                <div type="button" class="reg__foot">
                    <button class="reg_button_foot">
                        забыли пароль
                    </button>

                    <button type="button" data-modal-button = "modal-4" class="reg_button_foot">
                        регистрация
                    </button>
                </div>                    
            </form>
        </div>
    </div>

    <div id = "modal-4" data-modal class="hidden registr_section">
        <div class="mainblock panel_registr">
            <form class = "form_registr" id = "log_reg_form" method="post">
                <h1 class="reg__tittle">Регистрация</h1>

                <input type="email" class="reg_inp reg_input" required name = "contact" placeholder = "почта">
                <input type="text" class="reg_inp reg_input" name = "login" required placeholder = "логин">
                <input type="password" class="reg_inp reg_input" name = "password" required  placeholder = "пароль">
                <input type="password" required class="reg_inp reg_input" name = "repeatpassword"  placeholder = "введите пароль ещё раз">
                
                <div id = "errorMessageReg" class="errormessage"></div>

                <button type = "sumbit" class="reg__button">
                    зарегистрироваться
                </button>                
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
            <a href="index.php" class="logo">
                <img src="img/logo.svg" alt="логотип" class="logo__img">
            </a>
            <!-- /.logo -->
            <input type="text" class="input input-adress" placeholder="Название фильма"> 
            <div class = "buttons">
            <?php if ($username): ?>
                <a href = "./content/accountpage.php" class="button button__man" style = " padding: 8px 25px 23px 25px; background-color: red;     border-radius: 100%;">
                </a>
            <?php else: ?>
                <button data-modal-button = "modal-3" class="button button__man">
                </button>
            <?php endif; ?>
                

                <button data-modal-button = "modal-2" class="button button__cart">
                </button>
            </div>
                    
 
            <!-- /.buttons -->
        </div>
        <!-- /.container /.block-->
    </header>
    <!-- /.scetion_header -->
    
    <section class="section_promo">
        <div class="slider">
            <div class="slide">
                <img src="/img/afisha/1.jpg" alt="картинкаафиша1" class = "promo_img">
            </div>
            <div class="slide">
                <img src="/img/afisha/2.jpg" alt="картинкаафиша1" class = "promo_img">
            </div>
            <div class="slide">
            <img src="/img/afisha/3.jpg" alt="картинкаафиша1" class = "promo_img">
            </div>
            <div class="slide">
            <img src="/img/afisha/4.jpg" alt="картинкаафиша1" class = "promo_img">
            </div>
        </div>
    </section>
    <!-- /.section_promo -->

    <section class="section_films">
        <div class="container">
        <div class="film__search">
            <h2 class="section_title">Фильмы</h2>
            <input type="date" class="input input-search" value="2024-10-15">
        </div>
            <!-- /.restour__search -->
            <div class="cards">
                <!-- <div class="card">
                    <a href="content/infofilm.php" class="card__header">
                        <img src="img/Cat.png" alt="Фильм1" class="card__img">
                    </a>
                    <div class="card__main">
                        <div class="card__tittle">
                            <h3 class="tittle">Кот-призрак Андзу</h3> 
                            <div class="card__desc">
                                <strong class="card__reit">4.0</strong>
                                <span class="card__prodol">97 минут</span>
                            </div>
                        </div>
                        <span class="tittle__time">
                            <a href="#" class="time__time">
                                18:00 <br>
                                19:00 <br>
                                20:00 <br>
                            </a>
                        </span>

                    </div>

                </div>-->
            </div>
            <!-- /.cards -->


        </div>
    </section>
    <!-- /.section_films -->

    <footer class="section_footer">
        <div class="container footer_block">
            <a href="index.php" class="logo">
                <img src="img/logo.svg" alt="логотип" class="logo__img">
            </a>
            <div class="info__footer">
                <nav class="navigation__footer">
                    <a href="index.php" class="nav-item">главная</a>
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
                        <img src="img/VK.svg" alt="социальная сеть" class="social-img">
                    </a>
                    <!-- /.social-link -->
                    <a href="#" class="social-link">
                        <img src="img/Telegram.svg" alt="социальная сеть" class="social-img">
                    </a>
                    <!-- /.social-link -->
                </div>
            </div>

             <!-- /.socails -->

        </div>

    </footer>
    <!-- /.section_footer -->


    <script src="./js/openwindow.js"></script>
    <script src="./js/sumbit.js"></script>
    <script src="./js/cart.js"></script>
</body>
</html>