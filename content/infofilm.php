<?php require_once('get_infofilm.php'); ?>
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
    <title>Фильм</title>
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


    <div id="seans_modal" data-modal class="hidden registr_section">
        <div class="mainblock panel_registr">
            <span close-button class="button_close">&times;</span>
            <h2 id="zal_text">Зал:</h2>
            <p>Выберите место и ряд:</p>
            <form id="seat_form" method="post">
                <label for="row">Ряд:</label>
                <select id="ryad" name="row">
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                    <option value="6">6</option>
                </select>
                <label for="seat">Место:</label>
                <select id="mesto" name="seat">
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                    <option value="6">6</option>
                    <option value="7">7</option>
                    <option value="8">8</option>
                    <option value="9">9</option>
                    <option value="10">10</option>
                </select>
                <label for="ticket_type">Тип билета:</label>
                <select id="ticket_type" class="ticket_type" name="ticket_type">
                    <option value="600">Студенческий</option>
                    <option value="700">Взрослый</option>
                </select>
                <nav id="stoimost" class="price_type">700 руб</nav>
                <input type="hidden" name="seans_id" id="seans_id">
                <input type="hidden" name="film_id" id="film_id">
                <input type="hidden" name="data_time" id="data_time">
                <button type="button" class="confirm" id="confirm_button">Подтвердить</button>
            </form>              
        </div>
    </div>

    <div id="videoModal" data-modal class = "hidden modal_video">
        <div class = "mainblock video__div">
            <iframe
                id = "video_player"  
                src = " <?php echo htmlspecialchars($film['trailer']. "?autoplay=0&enablejsapi=1"); ?>" 
                width="100%" 
                height="100%" 
                frameborder="0"
                allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture"
                allowfullscreen>
            </iframe>
            <button  id = "close"  modal-close-button class = "button_close">X</button>
        </div>
    </div>



    <header class="section_header">
        <div class="container block">
            <a href="../index.php" class="logo">
                <img src="../img/logo.svg" alt="логотип" class="logo__img">
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

    <section class="section_info_film">
        <div class="container block_film">
        <div class="tittle_info">
            <img id="poster_film" src="../img/<?php echo htmlspecialchars($film['poster']); ?>" alt="ФотоФильма" class="img_info">
            <div class="text_film">
                <h1 id="nazvanie_filma" class="text_tittle"><?php echo htmlspecialchars($film['nazvanie']); ?></h1>
                <div class="rectungleGray rectVosr">
                    <nav id="vozrastnoe" class="textvosr">
                        <?php echo htmlspecialchars($film['vozrast_ogranich']); ?>+
                    </nav>
                </div>
                <div class="text_desc1">
                    <nav id="vipusk" class="item_text_desc1">
                        год выпуска: <?php echo htmlspecialchars($film['god_create']); ?>
                    </nav>
                    <nav id="creater" class="item_text_desc1">
                        производство: <?php echo htmlspecialchars($film['strana']); ?>
                    </nav>
                    <nav id="director" class="item_text_desc1">
                        режиссёр: <?php echo htmlspecialchars($film['rezhisser']); ?>
                    </nav>
                    <nav id="duraction" class="item_text_desc1">
                        продолжительность: <?php echo htmlspecialchars($film['prodolzhitelnost']); ?> минут
                    </nav>
                </div>
                <button data-modal-button = "videoModal" id = "button_trailer" class="button open_trailler">
                    Смотреть трейлер
                </button>
            </div>
        </div>
        <div class="block_desc2">
            <p id="desc" class="text_desc2">
                <?php echo nl2br(htmlspecialchars($film['desc'])); ?>
            </p>
        </div>
        <div class="seans">
            <div class="seans_tittleSel">
                <h2 class="seans_title">сеансы</h2>
                <div class="seans_information">
                    <nav id="dateivremya" class="seans_date">
                        <?php
                        // Если есть хотя бы один сеанс
                        if (!empty($seanses)) {
                            echo htmlspecialchars(date('d.m.Y', strtotime($seanses[0]['data_i_vremya'])));
                        } else {
                            echo "Нет доступных сеансов";
                        }
                        ?>
                    </nav>
                    <div id="vremya" class="rect_seans">
                        <?php
                        // Вывод времени всех сеансов
                        foreach ($seanses as $seans):
                            ?>
                            <button type="button"   data-modal-button="seans_modal" 
                            data-seans-id="<?php echo htmlspecialchars($seans['id_seans']); ?>"
                            data-zal="<?php echo htmlspecialchars($seans['zal']); ?>"
                            data-film-id="<?php echo htmlspecialchars($seans['film_id_film']); ?>"
                            data-date-time="<?php echo htmlspecialchars($seans['data_i_vremya']); ?>"
                            class="button rectungleGray itemtime_1">
                                <?php echo htmlspecialchars(date('H:i', strtotime($seans['data_i_vremya']))); ?>
                            </button>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
        </div>
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
                    <a href="#" class="nav-item">контакты</a>
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
    <script src="../js/infofilm.js"></script>
    <script src="../js/cart.js"></script>
</body>
</html>
