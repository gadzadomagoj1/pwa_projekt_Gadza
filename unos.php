<?php
include 'connect.php';

if (isset($_POST['submit'])) {
    $picture = $_FILES['pphoto']['name'];
    $title = $_POST['title'];
    $about = $_POST['about'];
    $content = $_POST['content'];
    $category = $_POST['category'];
    $date = date('d.m.Y.');

    if (isset($_POST['archive'])) {
        $archive = 1;
    } else {
        $archive = 0;
    }

    $target_dir = 'img/' . $picture;
    move_uploaded_file($_FILES["pphoto"]["tmp_name"], $target_dir);

    $query = "INSERT INTO vijesti (datum, naslov, sazetak, tekst, slika, kategorija, arhiva)
              VALUES ('$date', '$title', '$about', '$content', '$picture', '$category', '$archive')";

    $result = mysqli_query($dbc, $query) or die('Error querying database.');

    mysqli_close($dbc);
    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="hr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BBC News Portal - Unos vijesti</title>
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
                    <li><a href="unos.php" class="aktivna">Unos</a></li>
                    <li><a href="administrator.php">Administracija</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main class="glavni-sadrzaj">
        <div class="okvir okvir-forma">
            <section class="kategorija-sekcija">
                <h2 class="naslov-sekcije news-boja">Unos vijesti</h2>

                <form enctype="multipart/form-data" action="unos.php" method="POST" class="forma-unos">
                    <div class="form-item">
                        <label for="title">Naslov vijesti</label>
                        <div class="form-field">
                            <input type="text" name="title" id="title" class="form-field-textual" required>
                        </div>
                    </div>

                    <div class="form-item">
                        <label for="about">Kratki sadržaj vijesti</label>
                        <div class="form-field">
                            <textarea name="about" id="about" cols="30" rows="10" class="form-field-textual" required></textarea>
                        </div>
                    </div>

                    <div class="form-item">
                        <label for="content">Sadržaj vijesti</label>
                        <div class="form-field">
                            <textarea name="content" id="content" cols="30" rows="12" class="form-field-textual" required></textarea>
                        </div>
                    </div>

                    <div class="form-item">
                        <label for="pphoto">Slika</label>
                        <div class="form-field">
                            <input type="file" id="pphoto" name="pphoto" class="input-text" accept=".jpg,.jpeg,.png,.gif" required>
                        </div>
                    </div>

                    <div class="form-item">
                        <label for="category">Kategorija vijesti</label>
                        <div class="form-field">
                            <select name="category" id="category" class="form-field-textual">
                                <option value="News">News</option>
                                <option value="Sport">Sport</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-item checkbox-red">
                        <label for="archive">Spremiti u arhivu</label>
                        <div class="form-field">
                            <input type="checkbox" name="archive" id="archive">
                        </div>
                    </div>

                    <div class="form-item gumbi-forme">
                        <button type="reset">Poništi</button>
                        <button type="submit" name="submit">Prihvati</button>
                    </div>
                </form>
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