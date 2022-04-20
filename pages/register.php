<!DOCTYPE html>
<html lang="hu">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <link rel="icon" type="image/x-icon" href="../img/favicon.ico">
    <link rel="stylesheet" href="../css/styles.css"/>
    <title>Regisztráció | Autókereskedés</title>
    <style>
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
    </style>
</head>
<body>
<?php
include("../scripts/User.php");
$user = new User();
include("../scripts/Encryption.php");
$encryption = new Encryption();
include("../scripts/Validation.php");
$valid = new Validation();
session_start();
//enctype="multipart/form-data"
$errorMessageFname = $errorMessageLname = "";
$emailErr = $fav_languageErr = $passwordErr = $ageErr = $registerdayErr = $fav_roleErr = "";
$vezetek_nev = $kereszt_nev = $email = $fav_language = $pwd = $pwd2 = $age = $registerday = $img_name = "";
$fav_role = ""; // szerepkor
$allowed = "true"; // a felhasznalo engedelyezett
$emailMessage = "";
$responseAddUser = false;
$responseIsEmailUsers = "";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["form_submit"])) {

    //firstname
    if (empty($_POST["fname"])) {
        $errorMessageFname = "A vezetéknév megadása kötelező !";
    } else {
        $vezetek_nev = $valid->test_input($_POST["fname"]);
        if (!preg_match("/^[a-zA-Z-' ]*$/", $vezetek_nev)) {
            $errorMessageFname = "Csak betűk és szóközök megengedettek !";
        }
    }

    //lastname
    if (empty($_POST["lname"])) {
        $errorMessageLname = "A keresztnév megadása kötelező !";
    } else {
        $kereszt_nev = $valid->test_input($_POST["lname"]);
        if (!preg_match("/^[a-zA-Z-' ]*$/", $kereszt_nev)) {
            $errorMessageLname = "Csak betűk és szóközök megengedettek !";
        }
    }
    //email
    if (empty($_POST["email"])) {
        $emailErr = "E-mail megadása kötelező !";
    } else {
        $email = $valid->test_input($_POST["email"]);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $emailErr = "Érvénytelen e-mail formátum !";
        }
    }

    //age
    if (empty($_POST["age"])) {
        $ageErr = "Életkor megadása kötelező !";
    } else {
        $age = $valid->test_input($_POST["age"]);
        if (!preg_match('@[0-9]@', $age)) {
            $ageErr = "Érvénytelen életkor formátum !";
        }
    }

    //Date
    if (empty($_POST["registerday"])) {
        $registerdayErr = "Dátum megadása kötelező !";
    } else {
        $registerday = $valid->test_input($_POST["registerday"]);
        if (!preg_match("/^[0-9-. ]*$/", $registerday)) {
            $registerdayErr = "Érvénytelen dátum formátum !";
        }
    }

    //fav. language
    if (empty($_POST["fav_language"])) {
        $fav_languageErr = "Az állampolgárság kötelező !";
    } else {
        $fav_language = $valid->test_input($_POST["fav_language"]);
    }

    //fav. role
    if (empty($_POST["fav_role"])) {
        $fav_roleErr = "A jogosultság megadása kötelező !";
    } else {
        $fav_role = $valid->test_input($_POST["fav_role"]);
    }

    //passwords
    if (empty($_POST["pwd"]) && empty($_POST["pwd2"])) {
        $passwordErr = "A jelszavak megadása kötelező !";
    } else {
        if (!($valid->password_validation($_POST["pwd"], $_POST["pwd2"]))) {
            $passwordErr = "A jelszónak legalább 8 karakter hosszúságúnak kell lennie, és tartalmaznia kell legalább egy számot, egy nagybetűt, egy kisbetűt és egy speciális karaktert.";
        } else {
            $pwd = $_POST["pwd"];
            $pwd2 = $_POST["pwd2"];
        }
    }

    //save
    if ($errorMessageFname == "" && $errorMessageLname == "" && $emailErr == "" && $fav_languageErr == ""
        && $passwordErr == "" && $ageErr == "" && $registerdayErr == "" && $fav_roleErr == "") {
        $hash = $encryption->pass_hash($pwd);
        $uj_felhasznalo = [
            "firstname" => $vezetek_nev,
            "lastname" => $kereszt_nev,
            "email" => $email,
            "language" => $fav_language,
            "password" => $hash,
            "age" => $age,
            "registerday" => $registerday,
            "img" => $img_name,
            "role" => $fav_role,
            "allowed" => $allowed
        ];

        $responseIsEmailUsers = $user->isEmailUsers($email);
        if (!$responseIsEmailUsers) {
            $responseAddUser = $user->addUser($uj_felhasznalo);
            $vezetek_nev = $kereszt_nev = $email = $fav_language = $pwd = $pwd2 = "";
            $age = $registerday = $img_name = "";
            $fav_role = "";
            $allowed = "true";
        } else {
            $emailMessage = "Az email cím már létezik!";
        }
    }
}

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
            <li><a href="<?php if (isset($_SESSION["userid"])) { ?>content2.php<?php } else { ?>login.php<?php } ?>">
                    Opel autók
                </a>
            </li>
            <li><a href="login.php"><?php if (isset($_SESSION["userid"])) {
                        echo "Felhasználó";
                    } else {
                        echo "Bejelentkezés";
                    } ?></a></li>
            <li><a class="current" href="register.php">Regisztráció</a></li>
        </ul>
    </nav>
    <?php if (isset($_SESSION["userid"])) { ?>
        <div class="row advertisements-layer">
            <article class="flex-container">
                <?php if (isset($_SESSION["user_img"]) && $valid->imgPathSlice($_SESSION["user_img"]) != "") { ?>
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
    <article>
        <div class="card-title">
            <h1>Használt autókereskedés</h1>
        </div>
        <article class="form-container">
            <h2>Regisztráció</h2>
            <!-- $_SERVER["PHP_SELF"] magának az oldalnak küldi el a beküldött űrlapadatokat,
            ahelyett, hogy egy másik oldalra ugorna.
            Így a felhasználó ugyanazon az oldalon kap hibaüzeneteket, mint az űrlap. -->
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <!-- First name -->
                <div class="form-row">
                    <div class="form-col-25">
                        <label for="fname">Vezetéknév:</label>
                    </div>
                    <div class="form-col-75">
                        <input type="text" id="fname" name="fname" value="<?php echo $vezetek_nev; ?>">
                        <?php if ($errorMessageFname) { ?>
                            <div class="error-message">
                                <?php echo $errorMessageFname ?>
                            </div>
                        <?php } ?>
                    </div>
                </div>
                <!-- Last name -->
                <div class="form-row">
                    <div class="form-col-25">
                        <label for="lname">Keresztnév:</label>
                    </div>
                    <div class="form-col-75">
                        <input type="text" id="lname" name="lname" value="<?php echo $kereszt_nev; ?>">
                        <?php if ($errorMessageLname) { ?>
                            <div class="error-message">
                                <?php echo $errorMessageLname ?>
                            </div>
                        <?php } ?>
                    </div>
                </div>
                <div class="info-message">
                    <p>A neveket ékezet nélkül kell megadni!</p>
                </div>
                <!-- E-mail -->
                <div class="form-row">
                    <div class="form-col-25">
                        <label for="email">Email:</label>
                    </div>
                    <div class="form-col-75">
                        <input type="email" id="email" name="email" value="<?php echo $email; ?>">
                        <?php if ($emailErr) { ?>
                            <div class="error-message">
                                <?php echo $emailErr; ?>
                            </div>
                        <?php } ?>
                        <?php if ($emailMessage) { ?>
                            <div class="error-message">
                                <?php echo $emailMessage ?>
                            </div>
                        <?php } ?>
                    </div>
                </div>
                <!-- Password -->
                <div class="form-row">
                    <div class="form-col-25">
                        <label for="pwd">Jelszó:</label><br>
                    </div>
                    <div class="form-col-75">
                        <input type="password" id="pwd" name="pwd" value="<?php echo $pwd; ?>"><br><br>
                    </div>
                </div>
                <!-- Password -->
                <div class="form-row">
                    <div class="form-col-25">
                        <label for="pwd2">Jelszó mégegyszer:</label><br>
                    </div>
                    <div class="form-col-75">
                        <input type="password" id="pwd2" name="pwd2" value="<?php echo $pwd2; ?>"><br><br>
                        <?php if ($passwordErr) { ?>
                            <div class="error-message">
                                <?php echo $passwordErr ?>
                            </div>
                        <?php } ?>
                    </div>
                    <div class="info-message">
                        <p>A jelszónak legalább 8 karakter hosszúságúnak kell lennie, és tartalmaznia kell legalább egy
                            számot, egy nagybetűt, egy kisbetűt és egy speciális karaktert.</p>
                    </div>
                </div>
                <!-- User age -->
                <div class="form-row">
                    <div class="form-col-25">
                        <label for="age">Életkor (18 - 65):</label><br>
                    </div>
                    <div class="form-col-75">
                        <input type="number" id="age" name="age" min="18" max="65" value="<?php echo $age; ?>"><br><br>
                        <?php if ($ageErr) { ?>
                            <div class="error-message">
                                <?php echo $ageErr ?>
                            </div>
                        <?php } ?>
                    </div>
                </div>
                <!-- Registration day -->
                <div class="form-row">
                    <div class="form-col-25">
                        <label for="registerday">Dátum:</label>
                    </div>
                    <div class="form-col-75">
                        <input type="date" id="registerday" name="registerday" value="<?php echo $registerday; ?>">
                        <?php if ($registerdayErr) { ?>
                            <div class="error-message">
                                <?php echo $registerdayErr ?>
                            </div>
                        <?php } ?>
                    </div>
                </div>
                <!-- Nationality -->
                <div class="form-row">
                    <div style="width: 60%; margin-left: auto; margin-right: auto;">
                        <fieldset>
                            <legend>Állampolgárság</legend>
                            <input type="radio" id="hun" name="fav_language"
                                   value="hungary" <?php if (isset($fav_language) && $fav_language == "hungary") echo "checked"; ?>>
                            <label for="hun">Magyar</label><br>
                            <input type="radio" id="other" name="fav_language"
                                   value="other" <?php if (isset($fav_language) && $fav_language == "other") echo "checked"; ?>>
                            <label for="other">Külföldi</label><br>
                        </fieldset>
                        <?php if ($fav_languageErr) { ?>
                            <div class="error-message">
                                <?php echo $fav_languageErr; ?>
                            </div>
                        <?php } ?>
                    </div>
                </div>
                <!-- Role -->
                <?php if (isset($_SESSION["userid"])) { ?>
                    <div class="form-row">
                        <div style="width: 60%; margin-left: auto; margin-right: auto;">
                            <fieldset>
                                <legend>Jogosultság</legend>
                                <input type="radio" id="admin" name="fav_role"
                                       value="admin" <?php if (isset($fav_role) && $fav_role == "admin") echo "checked"; ?>>
                                <label for="admin">Admin</label><br>
                                <input type="radio" id="user" name="fav_role"
                                       value="user" <?php if (isset($fav_role) && $fav_role == "user") echo "checked"; ?>>
                                <label for="user">Felhasználó</label><br>
                            </fieldset>
                            <?php if ($fav_roleErr) { ?>
                                <div class="error-message">
                                    <?php echo $fav_roleErr; ?>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                <?php } ?>
                <?php if (!isset($_SESSION["userid"])) { ?>
                    <input type="hidden" id="admin" name="fav_role" value="user">
                <?php } ?>
                <br>
                <!-- buttons -->
                <div class="form-row">
                    <input type="submit" value="Regisztráció" name="form_submit">
                    <input type="reset">
                    <?php if ($responseAddUser) { ?>
                        <div class="info-message">
                            <p>A regisztráció sikeres.</p>
                        </div>
                    <?php } ?>
                </div>
            </form>
        </article>
    </article>
    <footer class="footer">
        <p>&copy; Autókereskedés 2022 | Minden jog fenntartva.</p>
    </footer>
</main>

</body>
</html>