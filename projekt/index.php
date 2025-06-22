<?php

// http://localhost/projekt/index.php
// http://localhost/projekt/index.html

include 'dbconn.php';

$query = "SELECT id, datum, naslov, sazetak, slika, kategorija FROM vijesti WHERE arhiva = 0 ORDER BY id DESC";
$result = mysqli_query($dbc, $query);

if (!$result) {
    die("Greška pri dohvaćanju vijesti iz baze: " . mysqli_error($dbc));
}

$politika_news = [];
$razno_news = []; 

while ($row = mysqli_fetch_assoc($result)) {
    $kategorija_lower = strtolower($row['kategorija']);

    if ($kategorija_lower == 'politika') {
        $politika_news[] = $row;
    } else {
        $razno_news[] = $row;
    }
}

mysqli_free_result($result);

$current_year = date("Y");
$current_date_footer = date("d.m.Y.");
?>

<!DOCTYPE html>
<html lang="hr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Novinski Portal - Početna</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <header>
        <link rel="icon" href="icon.png" type="image/png">
        <div class="site-branding">
            <img src="images/stern_logo.png" alt="Stern Logo">
        </div>
        <nav>
            <ul>
                <li><a href="index.php">Početna</a></li>
                <li><a href="kategorija.php?id=Politika">Politika</a></li>
                <li><a href="kategorija.php?id=Zdravlje">Zdravlje</a></li>
                <li><a href="kategorija.php?id=Drustvo">Društvo</a></li>
                <li><a href="kategorija.php?id=Ekonomija">Ekonomija</a></li>
                <li><a href="kategorija.php?id=Sport">Sport</a></li>
                <li><a href="kategorija.php?id=Kultura">Kultura</a></li>
                <li><a href="unos.html">Unos vijesti</a></li>
                <li><a href="administrator.php">Administracija</a></li>
                <li><a href="registracija.php">Registracija</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <section class="category-section">
            <h2>POLITIKA</h2>
            <div class="articles-grid">
                <?php
                if (!empty($politika_news)) {
                    foreach ($politika_news as $row) {
                        echo '<article>';
                        echo '    <img src="' . htmlspecialchars($row['slika']) . '" alt="' . htmlspecialchars($row['naslov']) . '">';
                        echo '    <span class="category-tag">' . htmlspecialchars($row['kategorija']) . '</span>';
                        echo '    <h3><a href="clanak.php?id=' . htmlspecialchars($row['id']) . '">' . htmlspecialchars($row['naslov']) . '</a></h3>';
                        echo '    <p>' . nl2br(htmlspecialchars(stripslashes($row['sazetak']))) . '</p>';
                        echo '    <span class="news-date">' . htmlspecialchars($row['datum']) . '</span>';
                        echo '</article>';
                    }
                } else {
                    echo '<p>Trenutno nema vijesti u kategoriji Politika.</p>';
                }
                ?>
            </div>
        </section>

        <section class="category-section">
            <h2>RAZNO</h2>
            <div class="articles-grid">
                <?php
                if (!empty($razno_news)) {
                    foreach ($razno_news as $row) {
                        echo '<article>';
                        echo '    <img src="' . htmlspecialchars($row['slika']) . '" alt="' . htmlspecialchars($row['naslov']) . '">';
                        echo '    <span class="category-tag">' . htmlspecialchars($row['kategorija']) . '</span>';
                        echo '    <h3><a href="clanak.php?id=' . htmlspecialchars($row['id']) . '">' . htmlspecialchars($row['naslov']) . '</a></h3>';
                        echo '    <p>' . nl2br(htmlspecialchars(stripslashes($row['sazetak']))) . '</p>';
                        echo '    <span class="news-date">' . htmlspecialchars($row['datum']) . '</span>';
                        echo '</article>';
                    }
                } else {
                    echo '<p>Trenutno nema vijesti u kategoriji Razno.</p>';
                }
                ?>
            </div>
        </section>
    </main>

</body>
    <footer>
        <p>Vijesti od <?php echo $current_date_footer; ?> | &copy; Dino Vlaisavljević, <?php echo $current_year; ?>.</p>
        <p>Kontakt: <a href="mailto:dvlaisavl@tvz.hr">dvlaisavl@tvz.hr</a></p>
    </footer>
</html>

<?php
mysqli_close($dbc);
?>