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
//enctype="multipart/form-data"
$errorMessageFname = $errorMessageLname = "";
$emailErr = $fav_languageErr = $passwordErr = $ageErr = $registerdayErr = "";
$vezetek_nev = $kereszt_nev = $email = $fav_language = $pwd = $pwd2 = $age = $registerday = $img_name = "";
$emailMessage = "";
$responseAddUser = false;
$responseIsEmailUsers = "";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["form_submit"])) {
    function test_input($data)
    {
        $data = trim($data); //felesleges karakterek eltávolítása
        $data = stripslashes($data); //fordított perjeleket (\) eltávolítja a felhasználói bemeneti adatokból
        $data = htmlspecialchars($data);
        return $data;
    }

    function password_validation($pwd, $pwd2)
    {
        if ($pwd != $pwd2) return false;
        $number = preg_match('@[0-9]@', $pwd);
        $uppercase = preg_match('@[A-Z]@', $pwd);
        $lowercase = preg_match('@[a-z]@', $pwd);
        $specialChars = preg_match('@[^\w]@', $pwd);
        $number2 = preg_match('@[0-9]@', $pwd2);
        $uppercase2 = preg_match('@[A-Z]@', $pwd2);
        $lowercase2 = preg_match('@[a-z]@', $pwd2);
        $specialChars2 = preg_match('@[^\w]@', $pwd2);
        if (strlen($pwd) < 8 || !$number || !$uppercase || !$lowercase || !$specialChars || strlen($pwd2) < 8
            || !$number2 || !$uppercase2 || !$lowercase2 || !$specialChars2) {
            return false;
        } else {
            return true;
        }
    }

    //firstname
    if (empty($_POST["fname"])) {
        $errorMessageFname = "A vezetéknév megadása kötelező !";
    } else {
        $vezetek_nev = test_input($_POST["fname"]);
        if (!preg_match("/^[a-zA-Z-' ]*$/", $vezetek_nev)) {
            $errorMessageFname = "Csak betűk és szóközök megengedettek !";
        }
    }

    //lastname
    if (empty($_POST["lname"])) {
        $errorMessageLname = "A keresztnév megadása kötelező !";
    } else {
        $kereszt_nev = test_input($_POST["lname"]);
        if (!preg_match("/^[a-zA-Z-' ]*$/", $kereszt_nev)) {
            $errorMessageLname = "Csak betűk és szóközök megengedettek !";
        }
    }
    //email
    if (empty($_POST["email"])) {
        $emailErr = "E-mail megadása kötelező !";
    } else {
        $email = test_input($_POST["email"]);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $emailErr = "Érvénytelen e-mail formátum !";
        }
    }

    //age
    if (empty($_POST["age"])) {
        $ageErr = "Életkor megadása kötelező !";
    } else {
        $age = test_input($_POST["age"]);
        if (!preg_match('@[0-9]@', $age)) {
            $ageErr = "Érvénytelen életkor formátum !";
        }
    }

    //Date
    if (empty($_POST["registerday"])) {
        $registerdayErr = "Dátum megadása kötelező !";
    } else {
        $registerday = test_input($_POST["registerday"]);
        if (!preg_match("/^[0-9-. ]*$/", $registerday)) {
            $registerdayErr = "Érvénytelen dátum formátum !";
        }
    }

    //fav. language
    if (empty($_POST["fav_language"])) {
        $fav_languageErr = "Az állampolgárság kötelező !";
    } else {
        $fav_language = test_input($_POST["fav_language"]);
    }

    //passwords
    if (empty($_POST["pwd"]) && empty($_POST["pwd2"])) {
        $passwordErr = "A jelszavak megadása kötelező !";
    } else {
        if (!(password_validation($_POST["pwd"], $_POST["pwd2"]))) {
            $passwordErr = "A jelszónak legalább 8 karakter hosszúságúnak kell lennie, és tartalmaznia kell legalább egy számot, egy nagybetűt, egy kisbetűt és egy speciális karaktert.";
        } else {
            $pwd = $_POST["pwd"];
            $pwd2 = $_POST["pwd2"];
        }
    }

    //save
    $emailMessage = "";
    if ($errorMessageFname == "" && $errorMessageLname == "" && $emailErr == "" && $fav_languageErr == "" && $passwordErr == "" && $ageErr == "" && $registerdayErr == "") {
        $hash = $encryption->pass_hash($pwd);
        $uj_felhasznalo = [
            "firstname" => $vezetek_nev,
            "lastname" => $kereszt_nev,
            "email" => $email,
            "language" => $fav_language,
            "password" => $hash,
            "age" => $age,
            "registerday" => $registerday,
            "img" => $img_name
        ];

        $responseIsEmailUsers = $user->isEmailUsers($email);
        if(!$responseIsEmailUsers){
            $responseAddUser = $user->addUser($uj_felhasznalo);
            $vezetek_nev = $kereszt_nev = $email = $fav_language = $pwd = $pwd2 = $age = $registerday = $img_name = "";
        } else {
            $emailMessage = "Az email cím már létezik!";
        }
    }
}
?>
<?php
$imgToUploadMessage = $imgToUploadErr = $target_file = "";
$target_dir = "../img/";
$target_file = $target_dir . basename($_FILES["imgToUpload"]["name"]);
$uploadOk = 1;
$imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

if (isset($_POST["img_submit"])) {
    //Ellenőrizze, hogy a képfájl valódi kép vagy hamis kép
    $isImageFile = getimagesize($_FILES["imgToUpload"]["tmp_name"]);
    // Ellenőrizze, hogy létezik-e már fájl
    $isFile = file_exists($target_file);
    // Ellenőrizze a fájl méretét
    $size = $_FILES["imgToUpload"]["size"];

    if ($isImageFile !== false && $isFile !== true && $size <= 200000 && $imageFileType === "jpg") {
        // ha minden rendben van a képpel, akkor átmásoljuk az ideiglenes mappából
        if (move_uploaded_file($_FILES["imgToUpload"]["tmp_name"], $target_file)) {
            $imgToUploadMessage = "A " . htmlspecialchars(basename($_FILES["imgToUpload"]["name"])) . " fájl feltöltve.";
        }
    } else {
        $imgToUploadErr = "Elnézést, hiba történt a fájl feltöltésekor! 
        Hibak lehetnek: nem kép, nem jpg kiterjesztés, már létezik ez a fájl, nagy méret! ";
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
            <!-- $_SERVER["PHP_SELF"] magának az oldalnak küldi el a beküldött űrlapadatokat,
            ahelyett, hogy egy másik oldalra ugorna.
            Így a felhasználó ugyanazon az oldalon kap hibaüzeneteket, mint az űrlap. -->
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
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
                <div class="form-row">
                    <div class="form-col-25">
                        <label for="pwd">Jelszó:</label><br>
                    </div>
                    <div class="form-col-75">
                        <input type="password" id="pwd" name="pwd" value="<?php echo $pwd; ?>"><br><br>
                    </div>
                </div>
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
                <div class="form-row">
                    <div style="width: 60%; margin-left: auto; margin-right: auto;">
                        <fieldset>
                            <legend>Állampolgárság</legend>
                            <input type="radio" id="hun" name="fav_language"
                                   value="Magyar" <?php if (isset($fav_language) && $fav_language == "Magyar") echo "checked"; ?>>
                            <label for="hun">Magyar</label><br>
                            <input type="radio" id="oth" name="fav_language"
                                   value="Külföldi" <?php if (isset($fav_language) && $fav_language == "Külföldi") echo "checked"; ?>>
                            <label for="oth">Külföldi</label><br>
                        </fieldset>
                        <?php if ($fav_languageErr) { ?>
                            <div class="error-message">
                                <?php echo $fav_languageErr; ?>
                            </div>
                        <?php } ?>
                    </div>
                </div>
                <br>
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
        <article class="form-container" style="margin-top: 10px">
            <h2>Profilkép feltöltése</h2>
            <form method="post" action="" enctype="multipart/form-data">
                <div class="form-row">
                    <p>Válassza ki a feltöltendő képet(jpg kiterjesztés, 700px*700px):</p>
                    <input type="file" name="imgToUpload" id="imgToUpload" accept=".JPG">
                </div>
                <div class="form-row">
                    <input type="submit" value="Kép feltöltése" name="img_submit">
                </div>
                <?php if ($imgToUploadMessage) { ?>
                    <div class="info-message">
                        <?php echo $imgToUploadMessage; ?>
                    </div>
                <?php } ?>
                <?php if ($imgToUploadErr) { ?>
                    <div class="error-message">
                        <?php echo $imgToUploadErr; ?>
                    </div>
                <?php } ?>
            </form>
        </article>
    </article>
    <footer class="footer">
        <p>&copy; Autókereskedés 2022 | Minden jog fenntartva.
        </p>
        <p><?php echo json_encode($responseIsEmailUsers) ?></p>
    </footer>
</main>

</body>
</html>