<?php
include 'connect.php';
define('UPLPATH', 'img/');
$kategorija = $_GET['id'];
?>
<!DOCTYPE html>
<html lang="hr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BBC News Portal - Kategorija</title>
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
                    <li><a href="kategorija.php?id=News" <?php if ($kategorija == 'News') echo 'class="aktivna"'; ?>>News</a></li>
                    <li><a href="kategorija.php?id=Sport" <?php if ($kategorija == 'Sport') echo 'class="aktivna"'; ?>>Sport</a></li>
                    <li><a href="unos.php">Unos</a></li>
                    <li><a href="administrator.php">Administracija</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main class="glavni-sadrzaj">
        <div class="okvir">
            <section class="kategorija-sekcija">
                <h2 class="naslov-sekcije <?php echo ($kategorija == 'Sport') ? 'sport-boja' : 'news-boja'; ?>">
                    <?php echo $kategorija; ?>
                </h2>

                <div class="grid-vijesti">
                    <?php
                    $query = "SELECT * FROM vijesti WHERE arhiva=0 AND kategorija='$kategorija' ORDER BY id DESC";
                    $result = mysqli_query($dbc, $query);
                    while ($row = mysqli_fetch_array($result)) {
                        echo '<article class="kartica-vijesti">';
                        echo '<a href="clanak.php?id=' . $row['id'] . '">';
                        echo '<img src="' . UPLPATH . $row['slika'] . '" alt="' . $row['naslov'] . '">';
                        echo '</a>';
                        echo '<h3><a href="clanak.php?id=' . $row['id'] . '">' . $row['naslov'] . '</a></h3>';
                        echo '<p>' . $row['sazetak'] . '</p>';
                        echo '</article>';
                    }
                    ?>
                </div>
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