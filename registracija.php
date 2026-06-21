<?php
include 'connect.php';

$registriranKorisnik = false;
$msg = '';

if (isset($_POST['registracija'])) {
    $ime = $_POST['ime'];
    $prezime = $_POST['prezime'];
    $username = $_POST['username'];
    $lozinka = $_POST['pass'];
    $lozinkaRep = $_POST['passRep'];
    $razina = 0;

    if ($lozinka !== $lozinkaRep) {
        $msg = 'Lozinke nisu iste!';
    } else {
        $hashed_password = password_hash($lozinka, PASSWORD_BCRYPT);

        $sql = "SELECT korisnicko_ime FROM korisnik WHERE korisnicko_ime = ?";
        $stmt = mysqli_stmt_init($dbc);

        if (mysqli_stmt_prepare($stmt, $sql)) {
            mysqli_stmt_bind_param($stmt, 's', $username);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_store_result($stmt);
        }

        if (mysqli_stmt_num_rows($stmt) > 0) {
            $msg = 'Korisničko ime već postoji!';
        } else {
            $sql = "INSERT INTO korisnik (ime, prezime, korisnicko_ime, lozinka, razina) VALUES (?, ?, ?, ?, ?)";
            $stmt = mysqli_stmt_init($dbc);

            if (mysqli_stmt_prepare($stmt, $sql)) {
                mysqli_stmt_bind_param($stmt, 'ssssi', $ime, $prezime, $username, $hashed_password, $razina);
                mysqli_stmt_execute($stmt);
                $registriranKorisnik = true;
            }
        }
    }
}

mysqli_close($dbc);
?>
<!DOCTYPE html>
<html lang="hr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BBC News Portal - Registracija</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header class="zaglavlje">
        <div class="okvir header-red">
            <div class="logo">
                <a href="index.php">BBC</a>
            </div>

            <nav class="navigacija">
                <ul>
                    <li><a href="index.php">Home</a></li>
                    <li><a href="kategorija.php?id=News">News</a></li>
                    <li><a href="kategorija.php?id=Sport">Sport</a></li>
                    <li><a href="unos.php">Unos</a></li>
                    <li><a href="administrator.php">Administracija</a></li>
                    <li><a href="registracija.php" class="aktivna">Registracija</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main class="glavni-sadrzaj">
        <div class="okvir okvir-forma">
            <section class="kategorija-sekcija">
                <h2 class="naslov-sekcije news-boja">Registracija korisnika</h2>

                <?php if ($registriranKorisnik == true) { ?>
                    <p class="uspjesna-poruka">Korisnik je uspješno registriran!</p>
                <?php } else { ?>
                    <form action="" method="POST" class="forma-unos" id="forma-registracija">
                        <div class="form-item">
                            <span id="porukaIme" class="bojaPoruke"></span>
                            <label for="ime">Ime:</label>
                            <div class="form-field">
                                <input type="text" name="ime" id="ime" class="form-field-textual">
                            </div>
                        </div>

                        <div class="form-item">
                            <span id="porukaPrezime" class="bojaPoruke"></span>
                            <label for="prezime">Prezime:</label>
                            <div class="form-field">
                                <input type="text" name="prezime" id="prezime" class="form-field-textual">
                            </div>
                        </div>

                        <div class="form-item">
                            <span id="porukaUsername" class="bojaPoruke"></span>
                            <label for="username">Korisničko ime:</label>
                            <?php echo '<span class="bojaPoruke">' . $msg . '</span>'; ?>
                            <div class="form-field">
                                <input type="text" name="username" id="username" class="form-field-textual">
                            </div>
                        </div>

                        <div class="form-item">
                            <span id="porukaPass" class="bojaPoruke"></span>
                            <label for="pass">Lozinka:</label>
                            <div class="form-field">
                                <input type="password" name="pass" id="pass" class="form-field-textual">
                            </div>
                        </div>

                        <div class="form-item">
                            <span id="porukaPassRep" class="bojaPoruke"></span>
                            <label for="passRep">Ponovite lozinku:</label>
                            <div class="form-field">
                                <input type="password" name="passRep" id="passRep" class="form-field-textual">
                            </div>
                        </div>

                        <div class="form-item gumbi-forme">
                            <button type="submit" name="registracija" id="slanje">Registriraj se</button>
                        </div>
                    </form>

                    <script>
                    document.getElementById("slanje").onclick = function(event) {
                        var slanjeForme = true;

                        var poljeIme = document.getElementById("ime");
                        var ime = poljeIme.value;
                        if (ime.length == 0) {
                            slanjeForme = false;
                            poljeIme.style.border = "1px dashed red";
                            document.getElementById("porukaIme").innerHTML = "Unesite ime!";
                        } else {
                            poljeIme.style.border = "1px solid green";
                            document.getElementById("porukaIme").innerHTML = "";
                        }

                        var poljePrezime = document.getElementById("prezime");
                        var prezime = poljePrezime.value;
                        if (prezime.length == 0) {
                            slanjeForme = false;
                            poljePrezime.style.border = "1px dashed red";
                            document.getElementById("porukaPrezime").innerHTML = "Unesite prezime!";
                        } else {
                            poljePrezime.style.border = "1px solid green";
                            document.getElementById("porukaPrezime").innerHTML = "";
                        }

                        var poljeUsername = document.getElementById("username");
                        var username = poljeUsername.value;
                        if (username.length == 0) {
                            slanjeForme = false;
                            poljeUsername.style.border = "1px dashed red";
                            document.getElementById("porukaUsername").innerHTML = "Unesite korisničko ime!";
                        } else {
                            poljeUsername.style.border = "1px solid green";
                            document.getElementById("porukaUsername").innerHTML = "";
                        }

                        var poljePass = document.getElementById("pass");
                        var pass = poljePass.value;
                        var poljePassRep = document.getElementById("passRep");
                        var passRep = poljePassRep.value;

                        if (pass.length == 0 || passRep.length == 0 || pass != passRep) {
                            slanjeForme = false;
                            poljePass.style.border = "1px dashed red";
                            poljePassRep.style.border = "1px dashed red";
                            document.getElementById("porukaPass").innerHTML = "Lozinke nisu iste!";
                            document.getElementById("porukaPassRep").innerHTML = "Lozinke nisu iste!";
                        } else {
                            poljePass.style.border = "1px solid green";
                            poljePassRep.style.border = "1px solid green";
                            document.getElementById("porukaPass").innerHTML = "";
                            document.getElementById("porukaPassRep").innerHTML = "";
                        }

                        if (slanjeForme != true) {
                            event.preventDefault();
                        }
                    };
                    </script>
                <?php } ?>
            </section>
        </div>
    </main>

    <footer class="podnozje">
        <div class="okvir">
            <p>Domagoj Gadža | dgadza@tvz.hr | 2026.</p>
        </div>
    </footer>
</body>
</html>