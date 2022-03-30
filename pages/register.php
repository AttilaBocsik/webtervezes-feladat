<!DOCTYPE html>
<html lang="hu">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <link rel="icon" type="image/x-icon" href="../img/favicon.ico">
    <link rel="stylesheet" href="../css/styles.css"/>
    <title>Regisztráció | Autókereskedés</title>
    <style>
        .error-message {
            color: red;
            padding: 10px;
            margin: 2px;
        }
    </style>
</head>
<body>
<?php
//enctype="multipart/form-data"
$errorMessageFname = "";
$errorMessageLname = "";
$emailErr = "";
$fav_languageErr = "";
$vezetek_nev = $kereszt_nev = $email = $fav_languageErr = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    function test_input($data)
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    if (empty($_POST["fname"])) {
        $errorMessageFname = "A vezetéknév megadása kötelező !";
    } else {
        $vezetek_nev = test_input($_POST["fname"]);
        if (!preg_match("/^[a-zA-Z-' ]*$/",$vezetek_nev)) {
            $errorMessageFname = "Csak betűk és szóközök megengedettek !";
        }
    }


    if (empty($_POST["lname"])) {
        $errorMessageLname = "A keresztnév megadása kötelező !";
    } else {
        $kereszt_nev = test_input($_POST["lname"]);
        if (!preg_match("/^[a-zA-Z-' ]*$/",$kereszt_nev)) {
            $errorMessageLname = "Csak betűk és szóközök megengedettek !";
        }
    }

    if (empty($_POST["email"])) {
        $emailErr = "E-mail megadása kötelező !";
    } else {
        $email = test_input($_POST["email"]);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $emailErr = "Érvénytelen e-mail formátum !";
        }
    }

    if (empty($_POST["fav_language"])) {
        $fav_languageErr = "Az állampolgárság kötelező !";
    } else {
        $fav_language = test_input($_POST["fav_language"]);
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
            <li><a href="../index.html">Főoldal</a></li>
            <li><a href="content1.html">Renault autók</a></li>
            <li><a href="content2.html">Opel autók</a></li>
            <li><a href="login.html">Bejelentkezés</a></li>
            <li><a class="current" href="register.php">Regisztráció</a></li>
        </ul>
    </nav>
    <article>
        <div class="card-title">
            <h1>Használt autókereskedés</h1>
        </div>
        <article class="form-container">
            <h2>Regisztráció</h2>
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" >
                <div class="form-row">
                    <div class="form-col-25">
                        <label for="fname">Vezetéknév:</label>
                    </div>
                    <div class="form-col-75">
                        <input type="text" id="fname" name="fname" value="<?php echo $vezetek_nev;?>">
                        <?php if ($errorMessageFname) { ?>
                            <div class="error-message">
                                <?php echo $errorMessageFname ?>
                            </div>
                        <?php } ?>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-col-25">
                        <label for="lname">Keresztnév:</label>
                    </div>
                    <div class="form-col-75">
                        <input type="text" id="lname" name="lname" value="<?php echo $kereszt_nev;?>">
                        <?php if ($errorMessageLname) { ?>
                            <div class="error-message">
                                <?php echo $errorMessageLname ?>
                            </div>
                        <?php } ?>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-col-25">
                        <label for="email">Email:</label>
                    </div>
                    <div class="form-col-75">
                        <input type="email" id="email" name="email" value="<?php echo $email;?>">
                        <div class="error-message">
                            <?php echo $emailErr; ?>
                        </div>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-col-25">
                        <label for="pwd">Jelszó:</label><br>
                    </div>
                    <div class="form-col-75">
                        <input type="password" id="pwd" name="pwd"><br><br>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-col-25">
                        <label for="pwd2">Jelszó mégegyszer:</label><br>
                    </div>
                    <div class="form-col-75">
                        <input type="password" id="pwd2" name="pwd2"><br><br>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-col-25">
                        <label for="age">Életkor (18 - 65):</label><br>
                    </div>
                    <div class="form-col-75">
                        <input type="number" id="age" name="age" min="18" max="65"><br><br>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-col-25">
                        <label for="registerday">Dátum:</label>
                    </div>
                    <div class="form-col-75">
                        <input type="date" id="registerday" name="registerday">
                    </div>
                </div>
                <div class="form-row">
                    <div style="width: 60%; margin-left: auto; margin-right: auto;">
                        <fieldset>
                            <legend>Állampolgárság</legend>
                            <input type="radio" id="hun" name="fav_language" value="Magyar" <?php if (isset($fav_language) && $fav_language=="Magyar") echo "checked";?>>
                            <label for="hun">Magyar</label><br>
                            <input type="radio" id="oth" name="fav_language" value="Külföldi" <?php if (isset($fav_language) && $fav_language=="Külföldi") echo "checked";?>>
                            <label for="oth">Külföldi</label><br>
                        </fieldset>
                        <div class="error-message">
                            <?php echo $fav_languageErr;?>
                        </div>
                    </div>
                </div>
                <br>
                <div class="form-row">
                    <input type="submit" value="Regisztráció">
                    <input type="reset">
                </div>
            </form>
        </article>
    </article>
    <footer class="footer">
        <p>&copy; Autókereskedés 2022 | Minden jog fenntartva.
        </p>
    </footer>
</main>

</body>
</html>