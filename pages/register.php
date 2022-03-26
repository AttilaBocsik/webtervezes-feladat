<?php
// POST paraméterek lekérése
    if (isset($_POST['fname'])) {
        // a paraméter létezik
        $vezetek_nev = $_POST['fname'];
    }

?>
<!DOCTYPE html>
<html lang="hu">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <link rel="icon" type="image/x-icon" href="../img/favicon.ico">
    <link rel="stylesheet" href="../css/styles.css"/>
    <title>Regisztráció | Autókereskedés</title>
</head>
<body>
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
            <form method="post" action="register.php" enctype="multipart/form-data">
                <div class="form-row">
                    <div class="form-col-25">
                        <label for="fname">Vezetéknév:</label>
                    </div>
                    <div class="form-col-75">
                        <input type="text" id="fname" name="fname" required>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-col-25">
                        <label for="lname">Keresztnév:</label>
                    </div>
                    <div class="form-col-75">
                        <input type="text" id="lname" name="lname" required>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-col-25">
                        <label for="email">Email:</label>
                    </div>
                    <div class="form-col-75">
                        <input type="email" id="email" name="email" required>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-col-25">
                        <label for="pwd">Jelszó:</label><br>
                    </div>
                    <div class="form-col-75">
                        <input type="password" id="pwd" name="pwd" required><br><br>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-col-25">
                        <label for="age">Életkor (18 - 65):</label><br>
                    </div>
                    <div class="form-col-75">
                        <input type="number" id="age" name="age" min="18" max="65" required><br><br>
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
                            <input type="radio" id="hun" name="fav_language" value="Magyar" required>
                            <label for="hun">Magyar</label><br>
                            <input type="radio" id="oth" name="fav_language" value="Külföldi" required>
                            <label for="oth">Külföldi</label><br>
                        </fieldset>
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