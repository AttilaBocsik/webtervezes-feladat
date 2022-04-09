<!DOCTYPE html>
<html lang="hu">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <link rel="icon" type="image/x-icon" href="../img/favicon.ico">
    <link rel="stylesheet" href="../css/styles.css"/>
    <title>Bejelentkezés | Autókereskedés</title>
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
session_start();
include("../scripts/User.php");
$user = new User();
include("../scripts/Encryption.php");
$encryption = new Encryption();
include("../scripts/Validation.php");
$valid = new Validation();
include("../scripts/PublicData.php");
$publicData = new PublicData();

$emailErr = $passwordErr = $userMessage = $passwordMessage = $email = $pwd = "";
$selectedAction = "";
$imgUpdate = false;
//variable in modified user function
$vezetek_nev = $kereszt_nev = $pwd3 = $pwd4 = "";
$errorMessageFname = $errorMessageLname = $errorMessagePassword = "";
$modifyUser = $modifiedUser = $responseModifiedUser = false;
//variable in remove user function
$removedUser = false;
unset($_SESSION["passwordErr"]);

// Sign In User
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
                $_SESSION["user_img"] = $user->getImgUser($email); //Load profile image
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

//Modify user button
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["modify_user_submit"])) {
    $actualUser = $user->getOneUser($_SESSION["userid"]);
    $vezetek_nev = $actualUser["firstname"];
    $kereszt_nev = $actualUser["lastname"];
    //$modifyUser = true;
    unset($_SESSION["img_upload"]);
    unset($_SESSION["img_removed"]);
    unset($_SESSION["imgToUploadMessage"]);
    $_SESSION["public_data_set"] = false;
    $_SESSION["public_data_message"] = false;
    $_SESSION["modifyUser"] = true;
}


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["modified_user_submit"])) {
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

    //passwords
    if (empty($_POST["pwd3"]) && empty($_POST["pwd4"])) {
        $errorMessagePassword = "A jelszavak megadása kötelező !";
    } else {
        if (!($valid->password_validation($_POST["pwd3"], $_POST["pwd4"]))) {
            $errorMessagePassword = "A jelszónak legalább 8 karakter hosszúságúnak kell lennie, és tartalmaznia kell legalább egy számot, egy nagybetűt, egy kisbetűt és egy speciális karaktert.";
        } else {
            $pwd3 = $_POST["pwd3"];
            $pwd4 = $_POST["pwd4"];
        }
    }

    if ($errorMessageFname == "" && $errorMessageLname == "" && $errorMessagePassword == "") {
        $hash = $encryption->pass_hash($pwd3);
        $actualUser = $user->getOneUser($_SESSION["userid"]);
        $responseModifiedUser = $user->modifyUser($_SESSION["userid"], $vezetek_nev, $kereszt_nev, $hash);
        $vezetek_nev = $kereszt_nev = $pwd3 = $pwd4 = "";
        unset($_SESSION["modifyUser"]);
    }
}

//Remove user
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["remove_user_submit"])) {
    //session_unset();
    //session_destroy();
    unset($_SESSION["img_removed"]);
    unset($_SESSION["modifyUser"]);
    unset($_SESSION["imgToUploadMessage"]);
    $_SESSION["public_data_set"] = false;
    $_SESSION["public_data_message"] = false;
    $removedUser = $user->removeUser($_SESSION["userid"]);
    $emailErr = $passwordErr = $userMessage = $passwordMessage = $email = $pwd = "";

}
?>
<?php
//Image update selected button
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["image_update_user_submit"])) {
    unset($_SESSION["img_removed"]);
    unset($_SESSION["modifyUser"]);
    unset($_SESSION["imgToUploadMessage"]);
    $_SESSION["img_upload"] = true;
    $_SESSION["public_data_set"] = false;
    $_SESSION["public_data_message"] = false;
}
//Image update
$imgToUploadMessage = $imgToUploadErr = $target_file = $img_name = "";
if (isset($_FILES["imgToUpload"]) && basename($_FILES["imgToUpload"]["name"]) != "") {
    $target_dir = "../img/";
    // a kép elérési útvonala
    $target_file = $target_dir . basename($_FILES["imgToUpload"]["name"]);
    //Ellenőrizze, hogy a képfájl valódi kép vagy hamis kép
    $isImageFile = getimagesize($_FILES["imgToUpload"]["tmp_name"]);
    // Ellenőrizze, hogy létezik-e már fájl
    $isFile = file_exists($target_file);
    // fájl típus ellenőrzés
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    // Ellenőrizze a fájl méretét
    $size = $_FILES["imgToUpload"]["size"];
    if (isset($_POST["img_submit"])) {
        if ($isImageFile === false) {
            $_SESSION["imgToUploadMessage"] = false;
            $imgToUploadErr = "A kép nem valódi !";
        } elseif ($isFile === true) {
            $_SESSION["imgToUploadMessage"] = false;
            $imgToUploadErr = "Ilyen nevű fájl már létezik !";
        } elseif ($size >= 200000) {
            $_SESSION["imgToUploadMessage"] = false;
            $imgToUploadErr = "A fájl mérete túl nagy !";
        } elseif ($imageFileType !== "jpg") {
            $_SESSION["imgToUploadMessage"] = false;
            $imgToUploadErr = "Csak jpg kiterjesztést lehet megadni !";
        } else {
            if (move_uploaded_file($_FILES["imgToUpload"]["tmp_name"], $target_file)) {
                $responseImgNameWrite = $user->modifyUserImg($_SESSION["userid"], $target_file);
                if ($responseImgNameWrite) {
                    $_SESSION["imgToUploadMessage"] = true;
                    $imgToUploadMessage = "Fájl feltöltés sikeres.";
                    unset($_SESSION["img_upload"]);
                    $_SESSION["user_img"] = $user->getImgUser($_SESSION["userid"]); //Load profile image
                }
            } else {
                $_SESSION["imgToUploadMessage"] = false;
                $imgToUploadErr = "Elnézést, hiba történt a fájl feltöltésekor !";
            }
        }
    }
}

//Image remove selected button
$imgRemovedMessage = "";
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["image_remove_user_submit"])) {
    unset($_SESSION["img_upload"]);
    unset($_SESSION["modifyUser"]);
    unset($_SESSION["imgToUploadMessage"]);
    $_SESSION["img_removed"] = true;
    $_SESSION["public_data_set"] = false;
    $_SESSION["public_data_message"] = false;
    $_SESSION["img_upload"] = false;
    if (unlink($user->getImgUser($_SESSION["userid"]))) {
        $responseImgNameWrite = $user->modifyUserImg($_SESSION["userid"], "");
        if ($responseImgNameWrite) {
            unset($_SESSION["user_img"]);
            $imgRemovedMessage = "A kép törlése sikeres !";
        } else {
            $imgRemovedMessage = "Nem sikerült a képet törölni az adatokból !";
        }
    } else {
        $imgRemovedMessage = "A kép törlése sikertelen !";
    }
}
?>
<?php
$publicDataModifiedMessage = $publicsErr = "";
$publics = $uj_felhasznalo_adatok = [];
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["public_data_set_btn"])) {
    unset($_SESSION["modifyUser"]);
    unset($_SESSION["imgToUploadMessage"]);
    unset($_SESSION["img_removed"]);
    $_SESSION["img_upload"] = false;
    $_SESSION["public_data_message"] = false;
    $_SESSION["public_data_set"] = true;
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["public_data_set_submit"])) {
    if (!isset($_POST["publics"])) {
        $publicsErr = "Kérem válasszon !";
    } else {
        $uj_felhasznalo_adatok = [
            "email" => $_SESSION["userid"],
            "public_list" => $_POST["publics"],
        ];
    }

    if ($publicsErr == "") {
        if (!$publicData->isFile()) $publicData->writingAllDatas([]);
        $resIsEmail = $publicData->isEmailData($_SESSION["userid"]);
        if (!$resIsEmail) {
            $responseAddPublicData = $publicData->addUserData($uj_felhasznalo_adatok);
            if ($responseAddPublicData) {
                unset($_SESSION["public_data_set"]);
            } else {
                $publicsErr = "Sikertelen mentés !";
            }
        } else {
            $responseModifiedPublicData = $publicData->modifyUserData($_SESSION["userid"], $_POST["publics"]);
            if ($responseModifiedPublicData) {
                unset($_SESSION["public_data_set"]);
            } else {
                $publicsErr = "Sikertelen mentés !";
            }
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
            <li><a class="current" href="login.php"><?php if (isset($_SESSION["userid"])) {
                        echo "Felhasználó";
                    } else {
                        echo "Bejelentkezés";
                    } ?></a></li>
            <li><a href="register.php">Regisztráció</a></li>
        </ul>
    </nav>
    <?php if (isset($_SESSION["userid"])) { ?>
        <div class="row advertisements-layer">
            <article class="flex-container">
                <?php if (isset($_SESSION["user_img"]) && $valid->imgPahtSlice($_SESSION["user_img"]) != "") { ?>
                    <img src="<?php echo $_SESSION["user_img"]; ?>" alt="kép" style="width:auto;height:55px;">
                <?php } else { ?>
                    <img src="../img/no-image.jpg" alt="No image" style="width:auto;height:55px;">
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
    <article>
        <div class="card-title">
            <h1>Használt autókereskedés</h1>
        </div>
        <!-- If Sign In -->
        <?php if (isset($_SESSION["userid"])) { ?>
            <article class="btn-container">
                <h4>Felhasználói lehetőségek</h4>
                <hr/>
                <br/>
                <div class="row">
                    <article class="flex-container" style="background-color: burlywood">
                        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                            <div class="form-row">
                                <input type="submit" value="Adatok láthatósági beállítása" name="public_data_set_btn">
                            </div>
                        </form>
                        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                            <div class="form-row">
                                <input type="submit" value="Fénykép feltöltése" name="image_update_user_submit">
                            </div>
                        </form>
                        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                            <div class="form-row">
                                <input type="submit" value="Fénykép törlése" name="image_remove_user_submit">
                            </div>
                        </form>
                        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                            <div class="form-row">
                                <input type="submit" value="Adat módosítás" name="modify_user_submit">
                                <br/>
                                <?php if ($responseModifiedUser) { ?>
                                    <div class="info-message">
                                        <p>A modósítás sikeres.</p>
                                    </div>
                                <?php } ?>
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
        <!-- Modify user -->
        <?php if (isset($_SESSION["userid"]) && isset($_SESSION["modifyUser"]) && $_SESSION["modifyUser"] == true) { ?>
            <article class="form-container" style="margin-top: 10px">
                <h4>Felhasználó módosítása</h4>
                <hr/>
                <br/>
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
                            <?php if ($errorMessagePassword) { ?>
                                <div class="error-message">
                                    <?php echo $errorMessagePassword ?>
                                </div>
                            <?php } ?>
                        </div>
                        <div class="info-message">
                            <p>A jelszónak legalább 8 karakter hosszúságúnak kell lennie, és tartalmaznia kell legalább
                                egy
                                számot, egy nagybetűt, egy kisbetűt és egy speciális karaktert.</p>
                        </div>
                    </div>
                    <!-- buttons -->
                    <div class="form-row">
                        <input type="submit" value="Módosítás" name="modified_user_submit">
                    </div>
                </form>
            </article>
        <?php } ?>
        <!-- User remove -->
        <?php if (isset($_SESSION["userid"]) && isset($removedUser) && $removedUser == true) { ?>
            <div class="info-message">
                <p><strong>Felhasználó véglegesen törölve!</strong></p>
            </div>
        <?php } ?>
        <!-- image upload -->
        <?php if (isset($_SESSION["userid"]) && isset($_SESSION["img_upload"]) && $_SESSION["img_upload"] == true) { ?>
            <article class="form-container" style="margin-top: 10px">
                <h2>Profilkép feltöltése</h2>
                <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>"
                      enctype="multipart/form-data">
                    <div class="form-row">
                        <p>Válassza ki a feltöltendő képet(jpg kiterjesztés, 700px*700px):</p>
                        <input type="file" name="imgToUpload" id="imgToUpload" accept=".JPG">
                    </div>
                    <div class="form-row">
                        <input type="submit" value="Kép feltöltése" name="img_submit">
                    </div>
                    <?php if (isset($_SESSION["imgToUploadMessage"])) { ?>
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
        <?php } ?>
        <!-- Image remove -->
        <?php if (isset($_SESSION["userid"]) && isset($_SESSION["img_removed"]) && $_SESSION["img_removed"] == true) { ?>
            <div class="info-message">
                <p><strong><?php echo $imgRemovedMessage; ?></strong></p>
            </div>
        <?php } ?>
        <!-- Public data settings -->
        <?php if (isset($_SESSION["userid"]) && isset($_SESSION["public_data_set"]) && $_SESSION["public_data_set"] == true) { ?>
            <article class="form-container" style="margin-top: 10px">
                <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                    <div class="form-row">
                        <div style="width: 100%; margin-left: auto; margin-right: auto;">
                            <fieldset>
                                <legend>Láthatóság</legend>
                                <label><input type="checkbox" name="publics[]"
                                              value="firstname" <?php if (isset($_POST['publics']) && in_array('firstname', $_POST['publics'])) echo 'checked'; ?>/>
                                    Vezetéknév</label>
                                <label><input type="checkbox" name="publics[]"
                                              value="lastname" <?php if (isset($_POST['publics']) && in_array('lastname', $_POST['publics'])) echo 'checked'; ?>/>
                                    Keresztnév</label>
                                <label><input type="checkbox" name="publics[]"
                                              value="email" <?php if (isset($_POST['publics']) && in_array('email', $_POST['publics'])) echo 'checked'; ?>/>
                                    Email</label>
                                <label><input type="checkbox" name="publics[]"
                                              value="age" <?php if (isset($_POST['publics']) && in_array('age', $_POST['publics'])) echo 'checked'; ?>/>
                                    Életkor</label>
                                <label><input type="checkbox" name="publics[]"
                                              value="role" <?php if (isset($_POST['publics']) && in_array('role', $_POST['publics'])) echo 'checked'; ?>/>
                                    Szerepkör</label>
                                <label><input type="checkbox" name="publics[]"
                                              value="no" <?php if (isset($_POST['publics']) && in_array('no', $_POST['publics'])) echo 'checked'; ?>/>
                                    Semelyik</label>
                            </fieldset>
                        </div>
                    </div>
                    <br/>
                    <!-- buttons -->
                    <div class="form-row">
                        <input type="submit" value="Mentés" name="public_data_set_submit">
                    </div>
                    <?php if ($publicsErr) { ?>
                        <div class="error-message">
                            <?php echo $publicsErr; ?>
                        </div>
                    <?php } ?>
                </form>
            </article>
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