<!DOCTYPE html>
<html lang="hu">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <link rel="icon" type="image/x-icon" href="../img/favicon.ico">
    <link rel="stylesheet" href="../css/styles.css"/>
    <title>Opel | Autókereskedés</title>
    <style>
        .flex-container > p {
            margin-top: auto;
            margin-bottom: auto;
            margin-left: 10px;
            margin-right: 10px;
            color: yellow;
        }

        .profil-img {
            margin-left: 10px;
        }
    </style>
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
                <img src="../img/slider-cars.jpg" alt="Cars" class="slider-image"/>
                <img src="../img/slider-opel.jpg" alt="Opel" class="slider-image"/>
                <img src="../img/slider-renault.jpg" alt="Renault" class="slider-image"/>
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
            <li><a href="../index.php">Főoldal</a></li>
            <li><a href="<?php if (isset($_SESSION["userid"])) { ?>content1.php<?php } else { ?>login.php<?php } ?>">
                    Renault autók
                </a>
            </li>
            <li><a class="current"
                   href="<?php if (isset($_SESSION["userid"])) { ?>content2.php<?php } else { ?>login.php<?php } ?>">
                    Opel autók
                </a>
            </li>
            <li><a href="login.php">Bejelentkezés</a></li>
            <li><a href="register.php">Regisztráció</a></li>
        </ul>
    </nav>
    <?php if (!isset($_SESSION["userid"])) { ?>
        <div class="info-container">
            <p>Kérem <a href="login.php"><strong>itt</strong></a> jelentkezzen be !</p>
        </div>
    <?php } ?>
    <?php if (isset($_SESSION["userid"])) { ?>
        <div class="row advertisements-layer">
            <article class="flex-container">
                <?php if (isset($_SESSION["user_img"])) { ?>
                    <img src="<?php echo $_SESSION["user_img"]; ?>" alt="kép" style="width:auto;height:55px;">
                <?php } else { ?>
                    <img src="../img/no-image.jpg" alt="No image" style="width:auto;height:55px;">
                <?php } ?>
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
        <?php if (isset($_SESSION["userid"])) { ?>
            <article class="leftcolumn" style="width: 100%">
                <div class="card-title">
                    <h1>Használt autókereskedés</h1>
                </div>
                <article class="card-article">
                    <h2>Opel autók</h2>
                    <div style="overflow-x:auto;">
                        <table>
                            <tr>
                                <th>Márka</th>
                                <th>Modell</th>
                                <th>Évjárat</th>
                                <th>Kivitel</th>
                                <th>Üzemanyag</th>
                                <th>Hengerür tart.(m3)</th>
                                <th>Ár(Ft)</th>
                                <th>Fotó</th>
                            </tr>
                            <tr>
                                <td>Opel</td>
                                <td>Insignia</td>
                                <td>2022/01</td>
                                <td>Ferdehátú</td>
                                <td>Dízel</td>
                                <td>1496</td>
                                <td>12541000</td>
                                <td>
                                    <a target="_blank" href="../img/opel_insignia.jpg">
                                        <img src="../img/opel_insignia.jpg" alt="Insigna" style="width:150px">
                                    </a>
                                </td>
                            </tr>
                            <tr>
                                <td>Opel</td>
                                <td>Astra K</td>
                                <td>2021/06</td>
                                <td>Kombi</td>
                                <td>Benzin</td>
                                <td>1199</td>
                                <td>9399000</td>
                                <td>
                                    <a target="_blank" href="../img/opel_astra_k.jpg">
                                        <img src="../img/opel_astra_k.jpg" alt="Astra K" style="width:150px">
                                    </a>
                                </td>
                            </tr>
                            <tr>
                                <td>Opel</td>
                                <td>Astra F</td>
                                <td>2001/03</td>
                                <td>Ferdehátú</td>
                                <td>Benzin</td>
                                <td>1598</td>
                                <td>215000</td>
                                <td>
                                    <a target="_blank" href="../img/opel_astra_f.jpg">
                                        <img src="../img/opel_astra_f.jpg" alt="Astra F" style="width:150px">
                                    </a>
                                </td>
                            </tr>
                        </table>
                    </div>
                </article>
            </article>
        <?php } ?>
    </div>

    <footer class="footer">
        <p>&copy; Autókereskedés 2022 | Minden jog fenntartva.
        </p>
    </footer>
</main>

</body>
</html>