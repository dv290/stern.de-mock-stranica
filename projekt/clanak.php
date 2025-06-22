<?php

include 'dbconn.php'; 

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id_clanka = mysqli_real_escape_string($dbc, $_GET['id']); 

    $query = "SELECT datum, naslov, sazetak, tekst, slika, kategorija FROM vijesti WHERE id = ?";

    $stmt = mysqli_prepare($dbc, $query);

    if ($stmt === false) {
        die("Greška pri pripremi SQL upita: " . mysqli_error($dbc));
    }

    mysqli_stmt_bind_param($stmt, 'i', $id_clanka);

    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) == 1) {
        $clanak = mysqli_fetch_assoc($result); 
    } else {
        header('Location: index.php'); 
        exit;
    }

    mysqli_stmt_close($stmt);

} else {
    header('Location: index.php'); 
    exit;
}

$current_year = date("Y");
$current_date_footer = date("d.m.Y.");
?>

<!DOCTYPE html>
<html lang="hr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($clanak['naslov']); ?> - Novinski Portal</title>
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

    <main class="article-page">
        <article>
            <span class="category-breadcrumb">Home > <?php echo htmlspecialchars($clanak['kategorija']); ?> > <?php echo htmlspecialchars($clanak['naslov']); ?></span>

            <h1><?php echo htmlspecialchars($clanak['naslov']); ?></h1>

            <p class="article-intro"><?php echo nl2br(htmlspecialchars(stripslashes($clanak['sazetak']))); ?></p>

            <?php if (!empty($clanak['slika']) && file_exists($clanak['slika'])): ?>
            <figure>
                <img src="<?php echo htmlspecialchars($clanak['slika']); ?>" alt="<?php echo htmlspecialchars($clanak['naslov']); ?>">
                <figcaption>Slika za vijest: <?php echo htmlspecialchars($clanak['naslov']); ?></figcaption>
            </figure>
            <?php else: ?>
            <p>Slika nije dostupna.</p>
            <?php endif; ?>

            <section class="article-content">
                <p><?php echo nl2br(htmlspecialchars(stripslashes($clanak['tekst']))); ?></p>
            </section>

            <p><strong>Kategorija:</strong> <?php echo htmlspecialchars($clanak['kategorija']); ?></p>
            <p style="margin-top: 30px;"><em>Objavljeno: <?php echo htmlspecialchars($clanak['datum']); ?></em></p>

        </article>
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