<!DOCTYPE html>
<html lang="hu">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <link rel="icon" type="image/x-icon" href="img/favicon.ico">
    <link rel="stylesheet" type="text/css" href="css/styles.css"/>
    <style>
        @media print {
            body {
                background-color: white;
                color: black;
                font-family: serif;
            }

            header, nav, aside {
                display: none
            }

            .advertisements-layer {
                display: none
            }

            .leftcolumn {
                width: 100%;
            }
        }

        .flex-container > p {
            margin-top: auto;
            margin-bottom: auto;
            margin-left: 10px;
            margin-right: 10px;
            color: yellow;
        }
    </style>
    <title>Főoldal | Autókereskedés</title>
</head>
<body>
<?php
session_start();
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["signout_submit"])) {
    session_unset();
    session_destroy();
}
?>
<main>
    <header class="header">
        <div class="slide-container">
            <span id="slider-image-1"></span>
            <span id="slider-image-2"></span>
            <span id="slider-image-3"></span>
            <div class="image-container">
                <img src="img/slider-cars.jpg" alt="Cars" class="slider-image"/>
                <img src="img/slider-opel.jpg" alt="Opel" class="slider-image"/>
                <img src="img/slider-renault.jpg" alt="Renault" class="slider-image"/>
            </div>
            <div class="button-container">
                <a href="#slider-image-1" class="slider-button"></a>
                <a href="#slider-image-2" class="slider-button"></a>
                <a href="#slider-image-3" class="slider-button"></a>
            </div>
        </div>
    </header>
    <!-- Navigation Bar -->
    <nav id="menu">
        <ul>
            <li><a class="current" href="index.php">Főoldal</a></li>
            <li><a href="<?php if (isset($_SESSION["userid"])) { ?>pages/content1.php<?php } else { ?>pages/login.php<?php } ?>">
                    Renault autók
                </a>
            </li>
            <li><a href="<?php if (isset($_SESSION["userid"])) { ?>pages/content2.php<?php } else { ?>pages/login.php<?php } ?>">
                    Opel autók
                </a>
            </li>
            <li><a href="pages/login.php">Bejelentkezés</a></li>
            <li><a href="pages/register.php">Regisztráció</a></li>
        </ul>
    </nav>
    <?php if (isset($_SESSION["userid"])) { ?>
        <div class="row advertisements-layer">
            <article class="flex-container">
                <p>Belépve mint: <strong><?php echo $_SESSION["userid"]; ?></strong></p>
                <?php if (isset($_SESSION["userid"])) { ?>
                    <p>Belépés ideje: <?php echo date('Y-m-d H:i:s', $_SESSION['time']); ?></p>
                <?php } ?>
                <div>
                    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>"
                          enctype="multipart/form-data">
                        <input type="submit" value="Kilépés" name="signout_submit">
                    </form>
                </div>
            </article>
        </div>
    <?php } ?>
    <div class="row">
        <article class="leftcolumn">
            <div class="card-title">
                <h1>Használt autókereskedés</h1>
            </div>
            <article class="card-article">
                <h2>Tisztelt látogató!</h2>
                <p>
                    <strong>Üdvözöljük oldalunkon.</strong> Az oldalon használt autó adás-vétele lehetséges.<br>
                    Kereskedésünk kizárólag Renault és Opel típusú személyautókkal foglalkozik.<br>
                    Az oldal használatához regisztráció szükséges. <span class="important-highlight">A felhasználói adatokat bizalmasan kezeljük és
                    harmadik fél részére nem szolgáltatjuk ki.</span>
                </p>
            </article>
            <article class="card-article">
                <h2>Elérhetőségünk:</h2>
                <hr>
                <address>
                    Email: <a href="mailto:hasznaltauttokereskedes@gmail.hu">hasznaltauttokereskedes@gmail.hu</a>.<br>
                    Telefonszám: 06 70/456-2556<br>
                    Címünk:<br>
                    Béla király út. 144.<br>
                    1083, Budapest
                </address>
            </article>
        </article>
        <aside class="rightcolumn">
            <h2 style="text-align: center; color: #ddd">Ajánlott tesztek</h2>
            <div class="card-aside">
                <video width="300" height="200" controls>
                    <source src="media/renault_kadjar.mp4" type="video/mp4">
                </video>
            </div>

            <h2 style="text-align: center; color: #ddd">Kiemelt ajánlataink</h2>
            <div class="card-aside flip-box">
                <div class="flip-box-inner">
                    <div class="flip-box-front">
                        <img class="card-aside-image" src="img/renault_kadjar_13.jpg" alt="Renault Kadjar">
                    </div>
                    <div class="flip-box-back">
                        <h2>Renault Kadjar</h2>
                        <p>Évjárat: 2021/03</p>
                        <p>Kivitel: Városi terepjáró</p>
                    </div>
                </div>
            </div>
        </aside>
    </div>
    <div class="row advertisements-layer">
        <article class="flex-container">
            <h3>Kiemelt hirdetések</h3>
            <img class="advertisements-layer-image" src="img/hirdetes_1.jpg" alt="Hírdetés">
            <img class="advertisements-layer-image" src="img/hirdetes_2.jpg" alt="Hírdetés">
            <img class="advertisements-layer-image" src="img/hirdetes_3.jpg" alt="Hírdetés">
            <h3>Kiemelt hirdetések</h3>
        </article>
    </div>
    <footer class="footer">
        <p>&copy; Autókereskedés 2022 | Minden jog fenntartva.
        </p>
    </footer>
</main>

</body>
</html>