<?php
session_start();
include 'connect.php';

define('UPLPATH', 'img/');

$uspjesnaPrijava = false;
$admin = false;
$neuspjesnaPrijava = false;
$imeKorisnika = '';

if (isset($_POST['prijava'])) {
    $prijavaImeKorisnika = $_POST['username'];
    $prijavaLozinkaKorisnika = $_POST['lozinka'];

    $sql = "SELECT korisnicko_ime, lozinka, razina FROM korisnik WHERE korisnicko_ime = ?";
    $stmt = mysqli_stmt_init($dbc);

    if (mysqli_stmt_prepare($stmt, $sql)) {
        mysqli_stmt_bind_param($stmt, 's', $prijavaImeKorisnika);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);
        mysqli_stmt_bind_result($stmt, $imeKorisnika, $lozinkaKorisnika, $levelKorisnika);
        mysqli_stmt_fetch($stmt);
    }

    if (mysqli_stmt_num_rows($stmt) > 0 && password_verify($prijavaLozinkaKorisnika, $lozinkaKorisnika)) {
        $uspjesnaPrijava = true;

        if ($levelKorisnika == 1) {
            $admin = true;
        } else {
            $admin = false;
        }

        $_SESSION['username'] = $imeKorisnika;
        $_SESSION['level'] = $levelKorisnika;
    } else {
        $neuspjesnaPrijava = true;
    }
}

if (isset($_POST['odjava'])) {
    session_unset();
    session_destroy();
    header("Location: administrator.php");
    exit;
}

if ((isset($_SESSION['username'])) && $_SESSION['level'] == 1) {
    $admin = true;
}

if ((isset($_SESSION['username'])) && $_SESSION['level'] == 0) {
    $admin = false;
    $uspjesnaPrijava = true;
    $imeKorisnika = $_SESSION['username'];
}

if (isset($_POST['delete']) && isset($_SESSION['username']) && $_SESSION['level'] == 1) {
    $id = $_POST['id'];
    $query = "DELETE FROM vijesti WHERE id=$id";
    mysqli_query($dbc, $query);
    header("Location: administrator.php");
    exit;
}

if (isset($_POST['update']) && isset($_SESSION['username']) && $_SESSION['level'] == 1) {
    $id = $_POST['id'];
    $title = $_POST['title'];
    $about = $_POST['about'];
    $content = $_POST['content'];
    $category = $_POST['category'];

    if (isset($_POST['archive'])) {
        $archive = 1;
    } else {
        $archive = 0;
    }

    $picture = $_FILES['pphoto']['name'];

    if ($picture != '') {
        $target_dir = 'img/' . $picture;
        move_uploaded_file($_FILES["pphoto"]["tmp_name"], $target_dir);

        $query = "UPDATE vijesti SET naslov='$title', sazetak='$about', tekst='$content', slika='$picture',
                  kategorija='$category', arhiva='$archive' WHERE id=$id";
    } else {
        $query = "UPDATE vijesti SET naslov='$title', sazetak='$about', tekst='$content',
                  kategorija='$category', arhiva='$archive' WHERE id=$id";
    }

    mysqli_query($dbc, $query);
    header("Location: administrator.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="hr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BBC News Portal - Administracija</title>
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
                    <li><a href="administrator.php" class="aktivna">Administracija</a></li>
                    <li><a href="registracija.php">Registracija</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main class="glavni-sadrzaj">
        <div class="okvir okvir-forma">
            <section class="kategorija-sekcija">
                <h2 class="naslov-sekcije news-boja">Administracija</h2>

                <?php if (($uspjesnaPrijava == true && $admin == true) || (isset($_SESSION['username']) && $_SESSION['level'] == 1)) { ?>

                    <form action="" method="POST" class="gumbi-forme odjava-forma">
                        <button type="submit" name="odjava">Odjava</button>
                    </form>

                    <?php
                    $query = "SELECT * FROM vijesti ORDER BY id DESC";
                    $result = mysqli_query($dbc, $query);

                    while ($row = mysqli_fetch_array($result)) {
                    ?>
                        <form enctype="multipart/form-data" action="" method="POST" class="forma-unos admin-blok">
                            <div class="form-item">
                                <label>Naslov vijesti</label>
                                <div class="form-field">
                                    <input type="text" name="title" class="form-field-textual" value="<?php echo $row['naslov']; ?>">
                                </div>
                            </div>

                            <div class="form-item">
                                <label>Kratki sadržaj vijesti</label>
                                <div class="form-field">
                                    <textarea name="about" cols="30" rows="6" class="form-field-textual"><?php echo $row['sazetak']; ?></textarea>
                                </div>
                            </div>

                            <div class="form-item">
                                <label>Sadržaj vijesti</label>
                                <div class="form-field">
                                    <textarea name="content" cols="30" rows="10" class="form-field-textual"><?php echo $row['tekst']; ?></textarea>
                                </div>
                            </div>

                            <div class="form-item">
                                <label>Slika</label>
                                <div class="form-field">
                                    <input type="file" name="pphoto" class="input-text">
                                    <br>
                                    <img src="<?php echo UPLPATH . $row['slika']; ?>" width="120" alt="<?php echo $row['naslov']; ?>">
                                </div>
                            </div>

                            <div class="form-item">
                                <label>Kategorija vijesti</label>
                                <div class="form-field">
                                    <select name="category" class="form-field-textual">
                                        <option value="News" <?php if ($row['kategorija'] == 'News') echo 'selected'; ?>>News</option>
                                        <option value="Sport" <?php if ($row['kategorija'] == 'Sport') echo 'selected'; ?>>Sport</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-item checkbox-red">
                                <label>Spremiti u arhivu</label>
                                <div class="form-field">
                                    <input type="checkbox" name="archive" <?php if ($row['arhiva'] == 1) echo 'checked'; ?>>
                                </div>
                            </div>

                            <div class="form-item">
                                <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                <div class="gumbi-forme">
                                    <button type="submit" name="update">Izmjeni</button>
                                    <button type="submit" name="delete">Izbriši</button>
                                </div>
                            </div>
                        </form>
                    <?php } ?>

                <?php } else if (($uspjesnaPrijava == true && $admin == false) || (isset($_SESSION['username']) && $_SESSION['level'] == 0)) { ?>

                    <p class="greska-poruka">Bok <?php echo $imeKorisnika != '' ? $imeKorisnika : $_SESSION['username']; ?>! Uspješno ste prijavljeni, ali nemate dovoljna prava za pristup ovoj stranici.</p>

                    <form action="" method="POST" class="gumbi-forme odjava-forma">
                        <button type="submit" name="odjava">Odjava</button>
                    </form>

                <?php } else { ?>

                    <?php if ($neuspjesnaPrijava == true) { ?>
                        <p class="greska-poruka">Neispravno korisničko ime i/ili lozinka. Potrebno se prvo registrirati. <a href="registracija.php">Registracija</a></p>
                    <?php } ?>

                    <form action="" method="POST" class="forma-unos" id="forma-prijava">
                        <div class="form-item">
                            <label for="username">Korisničko ime</label>
                            <div class="form-field">
                                <input type="text" name="username" id="username" class="form-field-textual">
                            </div>
                        </div>

                        <div class="form-item">
                            <label for="lozinka">Lozinka</label>
                            <div class="form-field">
                                <input type="password" name="lozinka" id="lozinka" class="form-field-textual">
                            </div>
                        </div>

                        <div class="form-item gumbi-forme">
                            <button type="submit" name="prijava" id="login-gumb">Prijava</button>
                        </div>
                    </form>

                    <script>
                    document.getElementById("login-gumb").onclick = function(event) {
                        var slanjeForme = true;
                        var username = document.getElementById("username");
                        var lozinka = document.getElementById("lozinka");

                        if (username.value.length == 0) {
                            slanjeForme = false;
                            username.style.border = "1px dashed red";
                        } else {
                            username.style.border = "1px solid green";
                        }

                        if (lozinka.value.length == 0) {
                            slanjeForme = false;
                            lozinka.style.border = "1px dashed red";
                        } else {
                            lozinka.style.border = "1px solid green";
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