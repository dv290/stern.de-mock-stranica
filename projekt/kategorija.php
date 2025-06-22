<?php

include 'dbconn.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$current_year = date("Y");
$current_date_footer = date("d.m.Y.");

$category_name = 'Sve kategorije'; 
$filtered_news = []; 

if (isset($_GET['id']) && !empty($_GET['id'])) {
    $category_name = $_GET['id']; 

    $query = "SELECT id, datum, naslov, sazetak, slika, kategorija FROM vijesti WHERE arhiva = 0 AND kategorija = ? ORDER BY id DESC";

    $stmt = mysqli_prepare($dbc, $query);

    if ($stmt === false) {
        die("Greška pri pripremi SQL upita: " . mysqli_error($dbc));
    }

    mysqli_stmt_bind_param($stmt, 's', $category_name);

    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $filtered_news[] = $row;
        }
    }

    mysqli_free_result($result);
    mysqli_stmt_close($stmt);

} else {
    header('Location: index.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="hr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($category_name); ?> - Novinski Portal</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <header>
        <div class="site-branding">
            <img src="images/stern_logo.png" alt="Stern Logo">
        </div>
        <nav>
            <ul>
                <li><a href="index.php">Početna</a></li>
                <li><a href="kategorija.php?id=Politika">Politika</a></li>
                <li><a href="kategorija.php?id=Zdravlje">Zdravlje</a></li>
                <li><a href="unos.html">Unos vijesti</a></li>
                <li><a href="administrator.php">Administracija</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <section class="category-section">
            <h2>Kategorija: <?php echo htmlspecialchars($category_name); ?></h2>
            <div class="articles-grid">
                <?php
                if (!empty($filtered_news)) {
                    foreach ($filtered_news as $row) {
                        echo '<article>';
                        echo '    <img src="' . htmlspecialchars($row['slika']) . '" alt="' . htmlspecialchars($row['naslov']) . '">';
                        echo '    <span class="category-tag">' . htmlspecialchars($row['kategorija']) . '</span>';
                        echo '    <h3><a href="clanak.php?id=' . htmlspecialchars($row['id']) . '">' . htmlspecialchars($row['naslov']) . '</a></h3>';
                        echo '    <p>' . nl2br(htmlspecialchars(stripslashes($row['sazetak']))) . '</p>';
                        echo '    <span class="news-date">' . htmlspecialchars($row['datum']) . '</span>';
                        echo '</article>';
                    }
                } else {
                    echo '<p>Trenutno nema vijesti u kategoriji "' . htmlspecialchars($category_name) . '".</p>';
                }
                ?>
            </div>
        </section>
    </main>

    <footer>
        <p>Vijesti od <?php echo $current_date_footer; ?> | &copy; Dino Vlaisavljević, <?php echo $current_year; ?>.</p>
        <p>Kontakt: <a href="mailto:dvlaisavl@tvz.hr">dvlaisavl@tvz.hr</a></p>
    </footer>

</body>
</html>

<?php
mysqli_close($dbc);
?>