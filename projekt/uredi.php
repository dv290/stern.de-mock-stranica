<?php

include 'dbconn.php'; 

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$news_item = null; 

if (isset($_POST['update'])) {
    $id_clanka = (int) $_POST['id']; 
    $category = trim(str_replace("\xC2\xA0", " ", $_POST['category']));
    $title = trim(str_replace("\xC2\xA0", " ", $_POST['title']));
    $summary = trim(str_replace("\xC2\xA0", " ", $_POST['summary']));
    $content = trim(str_replace("\xC2\xA0", " ", $_POST['content']));
    $archive = isset($_POST['archive']) ? 1 : 0;

    $picture_name = ''; 

    $current_image_query = "SELECT slika FROM vijesti WHERE id = ?";
    $stmt_current_image = mysqli_prepare($dbc, $current_image_query);
    mysqli_stmt_bind_param($stmt_current_image, 'i', $id_clanka);
    mysqli_stmt_execute($stmt_current_image);
    $result_current_image = mysqli_stmt_get_result($stmt_current_image);
    if ($row_current_image = mysqli_fetch_assoc($result_current_image)) {
        $picture_name = $row_current_image['slika']; 
    }
    mysqli_stmt_close($stmt_current_image);

    if (isset($_FILES['pphoto']) && $_FILES['pphoto']['error'] == UPLOAD_ERR_OK) {
        $target_dir = "uploads/";
        $new_picture_name = basename($_FILES["pphoto"]["name"]);
        $target_file = $target_dir . $new_picture_name;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        $check = getimagesize($_FILES["pphoto"]["tmp_name"]);
        if ($check !== false) {
            if (file_exists($target_file)) {
                $new_picture_name = uniqid() . '_' . $new_picture_name;
                $target_file = $target_dir . $new_picture_name;
            }

            if (move_uploaded_file($_FILES["pphoto"]["tmp_name"], $target_file)) {
                $picture_name = $target_file; 
            } else {
                echo "Greška prilikom premještanja datoteke.";
            }
        } else {
            echo "Datoteka nije slika.";
        }
    }

    $query = "UPDATE vijesti SET naslov=?, sazetak=?, tekst=?, slika=?, kategorija=?, arhiva=? WHERE id=?";

    $stmt = mysqli_prepare($dbc, $query);

    if ($stmt === false) {
        die("Greška pri pripremi SQL upita za ažuriranje: " . mysqli_error($dbc));
    }

    mysqli_stmt_bind_param($stmt, 'sssssii', $title, $summary, $content, $picture_name, $category, $archive, $id_clanka); 

    if (mysqli_stmt_execute($stmt)) {
        header('Location: administrator.php?status=success_update');
        exit;
    } else {
        echo "Greška pri ažuriranju vijesti: " . mysqli_error($dbc);
    }

    mysqli_stmt_close($stmt);

} else if (isset($_GET['id']) && is_numeric($_GET['id'])) { 
    $id_clanka = mysqli_real_escape_string($dbc, $_GET['id']); 

    $query = "SELECT id, datum, naslov, sazetak, tekst, slika, kategorija, arhiva FROM vijesti WHERE id = ?";
    $stmt = mysqli_prepare($dbc, $query);

    if ($stmt === false) {
        die("Greška pri pripremi SQL upita za dohvat vijesti: " . mysqli_error($dbc));
    }

    mysqli_stmt_bind_param($stmt, 'i', $id_clanka);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) == 1) {
        $news_item = mysqli_fetch_assoc($result);
        $news_item['sazetak'] = stripslashes($news_item['sazetak']);
        $news_item['tekst'] = stripslashes($news_item['tekst']);

    } else {
        header('Location: administrator.php');
        exit;
    }
    mysqli_stmt_close($stmt);

} else {
    header('Location: administrator.php');
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
    <title>Uredi Vijest - Novinski Portal</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .form-container {
            max-width: 800px;
            margin: 30px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        .form-group input[type="text"],
        .form-group textarea,
        .form-group select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box; 
        }
        .form-group textarea {
            resize: vertical;
            min-height: 150px;
        }
        .form-group input[type="file"] {
            padding: 5px 0;
        }
        .checkbox-group {
            display: flex;
            align-items: center;
            margin-top: 10px;
        }
        .checkbox-group input[type="checkbox"] {
            margin-right: 10px;
            width: auto; 
        }
        .form-buttons {
            text-align: right;
            margin-top: 20px;
        }
        .form-buttons button {
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s ease;
        }
        .form-buttons button:hover {
            background-color: #0056b3;
        }
        .current-image {
            margin-top: 10px;
        }
        .current-image img {
            max-width: 200px;
            height: auto;
            display: block;
            margin-top: 5px;
            border: 1px solid #ddd;
            box-shadow: 0 0 5px rgba(0,0,0,0.2);
        }
    </style>
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
        <section class="form-container">
            <h2>Uredi vijest (ID: <?php echo htmlspecialchars($news_item['id'] ?? ''); ?>)</h2>
            <form action="uredi.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="id" value="<?php echo htmlspecialchars($news_item['id'] ?? ''); ?>">

                <div class="form-group">
                    <label for="category">Kategorija:</label>
                    <select id="category" name="category" required>
                        <option value="Politika" <?php if (($news_item['kategorija'] ?? '') == 'Politika') echo 'selected'; ?>>Politika</option>
                        <option value="Zdravlje" <?php if (($news_item['kategorija'] ?? '') == 'Zdravlje') echo 'selected'; ?>>Zdravlje</option>
                        <option value="Društvo" <?php if (($news_item['kategorija'] ?? '') == 'Društvo') echo 'selected'; ?>>Društvo</option>
                        <option value="Migracija" <?php if (($news_item['kategorija'] ?? '') == 'Migracija') echo 'selected'; ?>>Migracija</option>
                        <option value="E-mobilnost" <?php if (($news_item['kategorija'] ?? '') == 'E-mobilnost') echo 'selected'; ?>>E-mobilnost</option>
                        <option value="Zabava" <?php if (($news_item['kategorija'] ?? '') == 'Zabava') echo 'selected'; ?>>Zabava</option>
                        <option value="Sport" <?php if (($news_item['kategorija'] ?? '') == 'Sport') echo 'selected'; ?>>Sport</option>
                        <option value="Razno" <?php if (($news_item['kategorija'] ?? '') == 'Razno') echo 'selected'; ?>>Razno</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="title">Naslov vijesti:</label>
                    <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($news_item['naslov'] ?? ''); ?>" required>
                </div>

                <div class="form-group">
                    <label for="summary">Kratki sadržaj (sažetak):</label>
                    <textarea id="summary" name="summary" required><?php echo htmlspecialchars($news_item['sazetak'] ?? ''); ?></textarea>
                </div>

                <div class="form-group">
                    <label for="content">Cijeli sadržaj:</label>
                    <textarea id="content" name="content" required><?php echo htmlspecialchars($news_item['tekst'] ?? ''); ?></textarea>
                </div>

                <div class="form-group">
                    <label for="pphoto">Trenutna slika:</label>
                    <?php if (!empty($news_item['slika'] ?? '') && file_exists($news_item['slika'] ?? '')): ?>
                        <div class="current-image">
                            <img src="<?php echo htmlspecialchars($news_item['slika'] ?? ''); ?>" alt="Trenutna slika">
                        </div>
                    <?php else: ?>
                        <p>Nema trenutne slike.</p>
                    <?php endif; ?>
                    <label for="pphoto">Učitaj novu sliku (ostavi prazno za zadržavanje postojeće):</label>
                    <input type="file" id="pphoto" name="pphoto" accept="image/jpeg, image/png, image/gif">
                </div>

                <div class="form-group checkbox-group">
                    <input type="checkbox" id="archive" name="archive" value="1" <?php if (($news_item['arhiva'] ?? 0) == 1) echo 'checked'; ?>>
                    <label for="archive">Arhiviraj vijest (Ne prikazuj na početnoj stranici)</label>
                </div>

                <div class="form-buttons">
                    <button type="submit" name="update">Ažuriraj vijest</button>
                </div>
            </form>
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