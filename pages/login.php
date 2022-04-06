<!DOCTYPE html>
<html lang="hu">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <link rel="icon" type="image/x-icon" href="../img/favicon.ico">
    <link rel="stylesheet" href="../css/styles.css"/>
    <title>Bejelentkezés | Autókereskedés</title>
    <style>
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

$emailErr = $passwordErr = $userMessage = $passwordMessage = $email = $pwd = "";
$vezetek_nev = $errorMessageFname = $kereszt_nev = $errorMessageLname = $pwd3 = $pwd4 = "";
$imgUpdate = false;
$modifyUser = false;
$modifiedUser = false;
$removedUser = false;
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["signin_submit"])) {
    // email
    if (empty($_POST["email"])) {
        $emailErr = "E-mail megadása kötelező !";
    } else {
        $email = $valid->test_input($_POST["email"]);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $emailErr = "Érvénytelen e-mail formátum !";
        }
        if (!$user->isEmailUsers($_POST["email"])) {
            $emailErr = "Ez az e-mail cím nincs regisztrálva !";
        }
    }
    // password
    if (empty($_POST["pwd"])) {
        $passwordErr = "A jelszó nem lehet üres !";
    } else {
        if (!($valid->password_validation($_POST["pwd"], $_POST["pwd"]))) {
            $passwordErr = "A jelszó formátuma nem megfelelő !";
        } else {
            $pwd = $_POST["pwd"];
            $actualUserHash = $user->getPasswordUsers($email);
            if (!$encryption->pass_verify($pwd, $actualUserHash)) {
                $passwordErr = "A jelszó nem érvényes !";
                session_unset();
                session_destroy();
            } else {
                $_SESSION["userid"] = $email;
                $_SESSION['time'] = time();
            }
        }
    }
}
//Sign Out
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["signout_submit"])) {
    $emailErr = $passwordErr = $userMessage = $passwordMessage = $email = $pwd = "";
    session_unset();
    //session_destroy();
}

//Image update
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["image_update_user_submit"])) {
    $actualUser = $user->getOneUser($_SESSION["userid"]);
    $imgUpdate = true;
    //$_SESSION["removedUser"] = json_encode($user->removeUser($_SESSION["userid"]));
    $emailErr = $passwordErr = $userMessage = $passwordMessage = $email = $pwd = "";

}

//Modify user
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["modify_user_submit"])) {
    $actualUser = $user->getOneUser($_SESSION["userid"]);
    $modifyUser = true;
    $vezetek_nev = $actualUser["firstname"];
    $kereszt_nev = $actualUser["lastname"];
}

//passwords
if (empty($_POST["pwd3"]) && empty($_POST["pwd4"])) {
    átírni$passwordErrM = "A jelszavak megadása kötelező !";
} else {
    if (!($valid->password_validation($_POST["pwd3"], $_POST["pwd4"]))) {
        $passwordErrM = "A jelszónak legalább 8 karakter hosszúságúnak kell lennie, és tartalmaznia kell legalább egy számot, egy nagybetűt, egy kisbetűt és egy speciális karaktert.";
    } else {
        $pwd3 = $_POST["pwd3"];
        $pwd4 = $_POST["pwd4"];
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["modified_user_submit"])) {
    $actualUser = $user->getOneUser($_SESSION["userid"]);
    $modifyUser = false;
    $modifiedUser = true;
    $hash = $encryption->pass_hash($pwd);
    //$_SESSION["modifiedUser"] = json_encode($user->modifyUser($_SESSION["userid"]), $_POST[""], $_POST[""], $hash);
    $vezetek_nev = $errorMessageFname = $kereszt_nev = $errorMessageLname = $pwd3 = $pwd4 = $passwordErrM = "";
}

//Remove user
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["remove_user_submit"])) {
    //session_unset();
    //session_destroy();
    $_SESSION["removedUser"] = json_encode($user->removeUser($_SESSION["userid"]));
    $emailErr = $passwordErr = $userMessage = $passwordMessage = $email = $pwd = "";

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
            <li><a class="current" href="login.php">Bejelentkezés</a></li>
            <li><a href="register.php">Regisztráció</a></li>
        </ul>
    </nav>
    <?php if (isset($_SESSION["userid"])) { ?>
        <div class="row advertisements-layer">
            <article class="flex-container">
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
    <article>
        <div class="card-title">
            <h1>Használt autókereskedés</h1>
        </div>
        <!-- If Sign In -->
        <?php if (isset($_SESSION["userid"])) { ?>
            <article class="form-container">
                <h4>Felhasználói lehetőségek</h4>
                <hr/>
                <br/>
                <div class="row">
                    <article class="flex-container" style="background-color: burlywood">
                        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                            <div class="form-row">
                                <input type="submit" value="Fénykép kezelés" name="image_update_user_submit">
                            </div>
                        </form>
                        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                            <div class="form-row">
                                <input type="submit" value="Adat módosítás" name="modify_user_submit">
                            </div>
                        </form>
                        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                            <div class="form-row">
                                <input type="submit" value="Végleges törlés" name="remove_user_submit">
                            </div>
                        </form>
                    </article>
                </div>
            </article>
        <?php } ?>
        <?php if (isset($_SESSION["userid"]) && $modifyUser == true) { ?>
            <article class="form-container">
                <h4>Felhasználó módosítása</h4>
                <hr/>
                <br/>
                <div class="form-row">
                    <!-- First name -->
                    <div class="form-row">
                        <div class="form-col-25">
                            <label for="fname">Vezetéknév:</label>
                        </div>
                        <div class="form-col-75">
                            <input type="text" id="fname" name="fname" value="<?php echo $vezetek_nev; ?>">
                            <?php if ($errorMessageFname != "") { ?>
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
                            <?php if ($errorMessageLname != "") { ?>
                                <div class="error-message">
                                    <?php echo $errorMessageLname ?>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="info-message">
                        <p>A neveket ékezet nélkül kell megadni!</p>
                    </div>
                    <!-- Password -->
                    <div class="form-row">
                        <div class="form-col-25">
                            <label for="pwd3">Jelszó:</label><br>
                        </div>
                        <div class="form-col-75">
                            <input type="password" id="pwd3" name="pwd3" value="<?php echo $pwd3; ?>"><br><br>
                        </div>
                    </div>
                    <!-- Password -->
                    <div class="form-row">
                        <div class="form-col-25">
                            <label for="pwd4">Jelszó mégegyszer:</label><br>
                        </div>
                        <div class="form-col-75">
                            <input type="password" id="pwd4" name="pwd4" value="<?php echo $pwd4; ?>"><br><br>
                            <?php if ($passwordErrM != "") { ?>
                                <div class="error-message">
                                    <?php echo $passwordErrM ?>
                                </div>
                            <?php } ?>
                        </div>
                        <div class="info-message">
                            <p>A jelszónak legalább 8 karakter hosszúságúnak kell lennie, és tartalmaznia kell legalább
                                egy
                                számot, egy nagybetűt, egy kisbetűt és egy speciális karaktert.</p>
                        </div>
                    </div>
                </div>
            </article>
        <?php } ?>
        <!-- User remove -->
        <?php if (isset($_SESSION["userid"]) && isset($_SESSION["removedUser"])) { ?>
            <div class="error-message">
                <p><strong>Felhasználó véglegesen törölve! <?php echo $_SESSION["removedUser"]; ?></strong></p>
            </div>
        <?php } ?>
        <!-- Sign In -->
        <?php if (!isset($_SESSION["userid"])) { ?>
            <article class="form-container">
                <h2>Bejelentkezés</h2>
                <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>"
                      enctype="multipart/form-data">
                    <fieldset>
                        <legend>Adatok</legend>
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
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-col-25">
                                <label for="pwd">Jelszó:</label><br>
                            </div>
                            <div class="form-col-75">
                                <input type="password" id="pwd" name="pwd" value="<?php echo $pwd; ?>"><br><br>
                                <?php if ($passwordErr) { ?>
                                    <div class="error-message">
                                        <?php echo $passwordErr ?>
                                    </div>
                                <?php } ?>
                                <?php if ($userMessage != "") { ?>
                                    <div class="info-message">
                                        <?php echo $userMessage; ?>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    </fieldset>
                    <br>
                    <div class="form-row">
                        <input type="submit" value="Belépés" name="signin_submit">
                        <input type="reset">
                    </div>
                </form>
            </article>
        <?php } ?>
    </article>
    <footer class="footer">
        <p>&copy; Autókereskedés 2022 | Minden jog fenntartva.
        </p>
    </footer>
</main>

</body>
</html>