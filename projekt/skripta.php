<?php

include 'dbconn.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    function clean_input($input) {
        if (function_exists('get_magic_quotes_gpc') && get_magic_quotes_gpc()) {
            $input = stripslashes($input);
        }
        $input = str_replace(["\xC2\xA0", "\r\n", "\r"], [" ", "\n", "\n"], $input);
        $input = trim($input);
        return $input;
    }

    $title = isset($_POST['title']) ? clean_input($_POST['title']) : '';
    $summary = isset($_POST['summary']) ? clean_input($_POST['summary']) : '';
    $content = isset($_POST['content']) ? clean_input($_POST['content']) : '';
    $category = isset($_POST['category']) ? clean_input($_POST['category']) : '';
    $display_on_homepage = isset($_POST['display_on_homepage']) ? 0 : 1;

    $current_date_for_db = date("Y-m-d");

    $image_path = '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = 'uploads/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        $image_name = basename($_FILES['image']['name']);
        $target_file = $upload_dir . $image_name;
        $image_file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        $allowed_types = array('jpg', 'jpeg', 'png', 'gif');
        if (in_array($image_file_type, $allowed_types)) {
            if (file_exists($target_file)) {
                $image_name = uniqid() . '_' . $image_name;
                $target_file = $upload_dir . $image_name;
            }
            
            if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
                $image_path = $target_file;
            } else {
                $image_path = 'Greška pri premještanju slike.';
            }
        } else {
            $image_path = 'Dozvoljeni su samo JPG, JPEG, PNG i GIF formati.';
        }
    } else {
        $image_path = 'Nema odabrane slike ili je došlo do greške pri uploadu.';
    }

    $current_year = date("Y");
    $current_date = date("d.m.Y.");

    $query = "INSERT INTO vijesti (datum, naslov, sazetak, tekst, slika, kategorija, arhiva) VALUES (?, ?, ?, ?, ?, ?, ?)";

    $stmt = mysqli_prepare($dbc, $query);

    if ($stmt === false) {
        die("Greška pri pripremi SQL upita: " . mysqli_error($dbc));
    }

    mysqli_stmt_bind_param($stmt, 'ssssssi', $current_date_for_db, $title, $summary, $content, $image_path, $category, $display_on_homepage);

    if (!mysqli_stmt_execute($stmt)) {
        echo "<p style='color: red;'>Greška pri spremanju vijesti u bazu: " . mysqli_error($dbc) . "</p>";
    }

    mysqli_stmt_close($stmt);

    echo '<!DOCTYPE html>';
    echo '<html lang="hr">';
    echo '<head>';
    echo '    <meta charset="UTF-8">';
    echo '    <meta name="viewport" content="width=device-width, initial-scale=1.0">';
    echo '    <title>' . htmlspecialchars($title) . ' - Novinski Portal</title>';
    echo '    <link rel="stylesheet" href="style.css">';
    echo '</head>';
    echo '<body>';

    echo '    <header>';
    echo '        <div class="site-branding">';
    echo '            <img src="images/stern_logo.png" alt="Stern Logo">';
    echo '        </div>';
    echo '        <nav>';
    echo '            <ul>';
    echo '                <li><a href="index.php">Početna</a></li>';
    echo '                <li><a href="kategorija.php?id=Politika">Politika</a></li>';
    echo '                <li><a href="kategorija.php?id=Zdravlje">Zdravlje</a></li>';
    echo '                <li><a href="unos.html">Unos vijesti</a></li>';
    echo '                <li><a href="administrator.php">Administracija</a></li>';
    echo '            </ul>';
    echo '</nav>';
    echo '    </header>';

    echo '    <main class="article-page">';
    echo '        <article>';
    echo '            <span class="category-breadcrumb">Home > ' . htmlspecialchars($category) . ' > ' . htmlspecialchars($title) . '</span>';
    echo '            <h1>' . htmlspecialchars($title) . '</h1>';
    echo '            <p class="article-intro">' . nl2br(htmlspecialchars($summary)) . '</p>';

    if (strpos($image_path, 'uploads/') === 0) {
        echo '            <figure>';
        echo '                <img src="' . htmlspecialchars($image_path) . '" alt="Slika vijesti">';
        echo '                <figcaption>Slika za vijest: ' . htmlspecialchars($title) . '</figcaption>';
        echo '            </figure>';
    } else {
        echo '            <p style="color: red;">' . htmlspecialchars($image_path) . '</p>';
    }

    echo '            <p>' . nl2br(htmlspecialchars($content)) . '</p>';
    echo '            <p><strong>Kategorija:</strong> ' . htmlspecialchars($category) . '</p>';
    echo '            <p><strong>Prikaz na početnoj stranici:</strong> ' . ($display_on_homepage ? 'Da' : 'Ne') . '</p>';
    echo '            <p style="margin-top: 30px;"><em>Objavljeno: ' . htmlspecialchars($current_date) . '</em></p>';
    echo '        </article>';
    echo '    </main>';

    echo '    <footer>';
    echo '        <p>Vijesti od ' . htmlspecialchars($current_date) . ' | &copy; Dino Vlaisavljević, ' . htmlspecialchars($current_year) . '.</p>';
    echo '        <p>Kontakt: <a href="mailto:dvlaisavl@tvz.hr">dvlaisavl@tvz.hr</a></p>';
    echo '    </footer>';

    echo '</body>';
    echo '</html>';

} else {
    header('Location: unos.html');
    exit;
}

mysqli_close($dbc);
?>