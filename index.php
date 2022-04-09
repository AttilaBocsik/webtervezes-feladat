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
            margin: auto 10px;
            color: yellow;
        }

        .error-message {
            color: red;
            padding: 10px;
            margin: 2px;
        }

        .info-message {
            color: green;
            padding: 6px;
            margin: 2px;
        }

        .cookie-info {
            color: #dddddd;
            padding: 6px;
            margin: 2px;
        }
    </style>
    <title>Főoldal | Autókereskedés</title>
</head>
<body>
<?php
include("scripts/User.php");
$user = new User();
include("scripts/Encryption.php");
$encryption = new Encryption();
include("scripts/Validation.php");
$valid = new Validation();
include("scripts/PublicData.php");
$publicData = new PublicData();

session_start();
$evaluation = "five";
$noData = "nem publikus";
unset($_SESSION["evaluationMessage"]);
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["signout_submit"])) {
    session_unset();
    session_destroy();
}

//evaluation
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["evaluation_submit"]) && isset($_POST["evaluation"])) {
    $evaluation = $valid->test_input($_POST["evaluation"]);
    $_SESSION["evaluationMessage"] = "Köszönjük az értékelést.";
    $day = (time() + 3600) * 24; //1 nap
    $value = "Felhasználó: " . $_SESSION["userid"] . ". Videó értékelése: " . $evaluation . ". Értékelés dátuma: " . date('Y-m-d H:i:s', $_SESSION['time']);
    setcookie("Evaluation", $value, $day);
}

function updateUserDataArray($email)
{
    if (empty($_SESSION["publicDataUsersArray"])) return [];
    foreach ($_SESSION["publicDataUsersArray"] as $item) {
        if ($item["email"] == $email) {
            return $item["public_list"];
        }
    }
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
            <li>
                <a href="<?php if (isset($_SESSION["userid"])) { ?>pages/content1.php<?php } else { ?>pages/login.php<?php } ?>">
                    Renault autók
                </a>
            </li>
            <li>
                <a href="<?php if (isset($_SESSION["userid"])) { ?>pages/content2.php<?php } else { ?>pages/login.php<?php } ?>">
                    Opel autók
                </a>
            </li>
            <li><a href="pages/login.php"><?php if (isset($_SESSION["userid"])) {
                        echo "Felhasználó";
                    } else {
                        echo "Bejelentkezés";
                    } ?></a></li>
            <li><a href="pages/register.php">Regisztráció</a></li>
        </ul>
    </nav>
    <?php if (isset($_SESSION["userid"])) { ?>
        <div class="row advertisements-layer">
            <article class="flex-container">
                <?php if (isset($_SESSION["user_img"]) && $valid->imgPahtSlice($_SESSION["user_img"]) != "") { ?>
                    <img src="<?php echo "img/" . $valid->imgPahtSlice($_SESSION["user_img"]); ?>" alt="kép"
                         style="width:auto;height:55px;">
                <?php } else { ?>
                    <img src="img/no-image.jpg" alt="No image" style="width:auto;height:55px;">
                <?php } ?>
                <p>Belépve mint: <strong><?php echo $_SESSION["userid"]; ?></strong></p>
                <p>Belépés ideje: <?php echo date('Y-m-d H:i:s', $_SESSION['time']); ?></p>
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
            <!-- Users data -->
            <?php if (isset($_SESSION["isPublicFileExists"]) && $_SESSION["isPublicFileExists"] == true) { ?>
                <article class="card-article">
                    <h2>Felhasználók nyilvános adatai:</h2>
                    <hr>
                    <div style="overflow-x:auto;">
                        <table>
                            <tr>
                                <th>Vezetéknév</th>
                                <th>Kersztnév</th>
                                <th>Email</th>
                                <th>Életkor</th>
                                <th>Profilkép</th>
                                <th>Szerepkör</th>
                            </tr>
                            <?php foreach ($_SESSION["usersArray"] as $user) {
                                $publicDataItems =  updateUserDataArray($user["email"]);
                                echo "<tr>";
                                foreach ($user as $key => $value) {
                                    if (in_array($key, ["firstname", "lastname", "email", "age", "role", "img"])) {
                                        if (in_array($key, $publicDataItems)) {
                                            if ($key == "img") {
                                                echo '<td><img src="img/' . $valid->imgPahtSlice($value) . '" alt="Kép" style="width:auto;height:55px;"></td>';
                                            } else {
                                                echo "<td>{$value}</td>";
                                            }
                                        } else {
                                            echo "<td>{$noData}</td>";
                                        }
                                    }

                                    /*
                                    if (in_array($key, $_SESSION["publicDataUsersArray"])) {
                                        echo "<td>{$value}</td>";
                                    } else {
                                        echo "<td>{$noData}</td>";
                                    }*/
                                }
                                echo "<tr>";
                            } ?>
                        </table>
                    </div>
                </article>
            <?php } ?>
        </article>
        <aside class="rightcolumn">
            <h2 style="text-align: center; color: #ddd">Ajánlott tesztek</h2>
            <div class="card-aside">
                <video width="300" height="200" controls>
                    <source src="media/renault_kadjar.mp4" type="video/mp4">
                </video>
                <?php if (isset($_COOKIE["Evaluation"])) { ?>
                    <div class="cookie-info">
                        <p>Eddigi videó értékelések:</p>
                        <p><?php print_r($_COOKIE["Evaluation"]); ?></p>
                    </div>
                <?php } ?>
                <!-- If user sign In then video evalution -->
                <?php if (isset($_SESSION["userid"])) { ?>
                    <article class="form-container" style="margin-top: 10px; width: 100%">
                        <?php if (isset($_SESSION["evaluationMessage"])) { ?>
                            <div class="info-message">
                                <p><?php echo $_SESSION["evaluationMessage"]; ?></p>
                            </div>
                        <?php } ?>
                        <p>Kérem értékelje a videót 1 - 5-ig skálán.</p>
                        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>"
                              enctype="multipart/form-data">
                            <div class="form-row">
                                <div style="width: 100%; margin-left: auto; margin-right: auto;">
                                    <fieldset>
                                        <legend>Értékelés</legend>
                                        <input type="radio" id="one" name="evaluation"
                                               value="one" <?php if (isset($evaluation) && $evaluation == "one") echo "checked"; ?>>
                                        <label for="one">1</label><br>
                                        <input type="radio" id="two" name="evaluation"
                                               value="two" <?php if (isset($evaluation) && $evaluation == "two") echo "checked"; ?>>
                                        <label for="two">2</label><br>
                                        <input type="radio" id="three" name="evaluation"
                                               value="three" <?php if (isset($evaluation) && $evaluation == "three") echo "checked"; ?>>
                                        <label for="three">3</label><br>
                                        <input type="radio" id="four" name="evaluation"
                                               value="four" <?php if (isset($evaluation) && $evaluation == "four") echo "checked"; ?>>
                                        <label for="four">4</label><br>
                                        <input type="radio" id="five" name="evaluation"
                                               value="five" <?php if (isset($evaluation) && $evaluation == "five") echo "checked"; ?>>
                                        <label for="five">5</label><br>
                                    </fieldset>
                                </div>
                            </div>
                            <div class="form-row" style="margin-top: 10px">
                                <input type="submit" value="Értékelés elküldése" name="evaluation_submit">
                            </div>
                        </form>
                    </article>
                <?php } ?>
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
        <p>&copy; Autókereskedés 2022 | Minden jog fenntartva.</p>
        <?php if (isset($_SESSION["isPublicFileExists"])) { ?>
            <p><?php echo json_encode($_SESSION["publicDataUsersArray"]) ?></p>
        <?php } ?>
    </footer>
</main>

</body>
</html>