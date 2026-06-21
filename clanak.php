<?php
include 'connect.php';
define('UPLPATH', 'img/');

$id = $_GET['id'];
$query = "SELECT * FROM vijesti WHERE id=$id";
$result = mysqli_query($dbc, $query);
$row = mysqli_fetch_array($result);
?>
<!DOCTYPE html>
<html lang="hr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BBC News Portal - Članak</title>
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
                </ul>
            </nav>
        </div>
    </header>

    <main class="glavni-sadrzaj">
        <div class="okvir okvir-clanak">
            <section class="traka-kategorije <?php echo ($row['kategorija'] == 'Sport') ? 'sport-traka' : 'news-traka'; ?>">
                <h2><?php echo strtoupper($row['kategorija']); ?></h2>
            </section>

            <article class="clanak-detalj">
                <h1><?php echo $row['naslov']; ?></h1>

                <div class="meta-podaci">
                    <p><strong>AUTOR:</strong> Domagoj Gadža</p>
                    <p><strong>OBJAVLJENO:</strong> <?php echo $row['datum']; ?></p>
                </div>

                <figure class="slika-clanka">
                    <img src="<?php echo UPLPATH . $row['slika']; ?>" alt="<?php echo $row['naslov']; ?>">
                </figure>

                <p class="istaknuti-uvod"><?php echo $row['sazetak']; ?></p>

                <section class="tekst-skripte">
                    <p><?php echo nl2br($row['tekst']); ?></p>
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