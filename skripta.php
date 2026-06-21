<?php
$title = '';
$about = '';
$content = '';
$category = '';
$image = '';
$archive = 'Ne';

if (isset($_POST['title'])) {
    $title = $_POST['title'];
}

if (isset($_POST['about'])) {
    $about = $_POST['about'];
}

if (isset($_POST['content'])) {
    $content = $_POST['content'];
}

if (isset($_POST['category'])) {
    $category = $_POST['category'];
}

if (isset($_POST['archive'])) {
    $archive = 'Da';
}

if (isset($_FILES['pphoto']) && isset($_FILES['pphoto']['name'])) {
    $image = $_FILES['pphoto']['name'];
}

if ($image == '') {
    $image = 'news1.jpg';
}

$datum = date("d.m.Y.");
?>
<!DOCTYPE html>
<html lang="hr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BBC News Portal - Pregled unosa</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header class="zaglavlje">
        <div class="okvir header-red">
            <div class="logo">
                <a href="index.html">BBC</a>
            </div>

            <nav class="navigacija">
                <ul>
                    <li><a href="index.html">Home</a></li>
                    <li><a href="index.html#news">News</a></li>
                    <li><a href="index.html#sport">Sport</a></li>
                    <li><a href="unos.html" class="aktivna">Unos</a></li>
                    <li><a href="#">Administracija</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main class="glavni-sadrzaj">
        <div class="okvir okvir-clanak">
            <section class="traka-kategorije <?php echo ($category == 'Sport') ? 'sport-traka' : 'news-traka'; ?>">
                <h2><?php echo $category; ?></h2>
            </section>

            <article class="clanak-detalj">
                <h1><?php echo $title; ?></h1>

                <div class="meta-podaci">
                    <p><strong>AUTOR:</strong> Domagoj Gadža</p>
                    <p><strong>OBJAVLJENO:</strong> <?php echo $datum; ?></p>
                    <p><strong>PRIKAZATI NA STRANICI:</strong> <?php echo $archive; ?></p>
                </div>

                <figure class="slika-clanka">
                    <img src="img/<?php echo $image; ?>" alt="<?php echo $title; ?>">
                    <figcaption>Odabrana slika za članak.</figcaption>
                </figure>

                <p class="istaknuti-uvod">
                    <?php echo $about; ?>
                </p>

                <section class="tekst-skripte">
                    <p><?php echo nl2br($content); ?></p>
                </section>
            </article>
        </div>
    </main>

    <footer class="podnozje">
        <div class="okvir">
            <p>Domagoj Gadža | dgadza@tvz.hr | 2026.</p>
        </div>
    </footer>
</body>
</html>