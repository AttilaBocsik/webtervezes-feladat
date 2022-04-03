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
            //$isPasswordOk = $encryption->pass_verify($pwd, $actualUserHash);
            if (!$encryption->pass_verify($pwd, $actualUserHash)) {
                $passwordErr = "A jelszó nem érvényes !";
                session_unset();
                session_destroy();
            } else {
                $passwordErr = "";

                $_SESSION["userid"] = $email;
                $_SESSION['time'] = time();
            }
            //$isPasswordOk = false;
        }
    }
}
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["signout_submit"])) {
    $emailErr = $passwordErr = $userMessage = $passwordMessage = $email = $pwd = "";
    $isPasswordOk = false;
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
            <li><a class="current" href="login.php">Bejelentkezés</a></li>
            <li><a href="register.php">Regisztráció</a></li>
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
    <article>
        <div class="card-title">
            <h1>Használt autókereskedés</h1>
        </div>
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
                                <?php if ($userMessage) { ?>
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