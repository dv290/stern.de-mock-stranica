<?php
session_start();

include 'dbconn.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$uspjesnaPrijava = false;
$admin = false;
$porukaPrijava = '';

if (isset($_SESSION['korisnicko_ime']) && isset($_SESSION['razina_prava'])) {
    $uspjesnaPrijava = true;
    if ($_SESSION['razina_prava'] == 1) {
        $admin = true;
    }
}

if (isset($_POST['prijava'])) {
    $prijavaKorisnickoIme = trim($_POST['korisnicko_ime_prijava']);
    $prijavaLozinka = $_POST['lozinka_prijava'];

    if (empty($prijavaKorisnickoIme) || empty($prijavaLozinka)) {
        $porukaPrijava = 'Korisničko ime i lozinka su obavezni.';
    } else {
        $query = "SELECT id, ime, prezime, korisnicko_ime, lozinka_hash, razina_prava FROM korisnik WHERE korisnicko_ime = ?";
        $stmt = mysqli_prepare($dbc, $query);

        if ($stmt === false) {
            die("Greška pri pripremi SQL upita za prijavu: " . mysqli_error($dbc));
        }

        mysqli_stmt_bind_param($stmt, 's', $prijavaKorisnickoIme);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($row = mysqli_fetch_assoc($result)) {
            if (password_verify($prijavaLozinka, $row['lozinka_hash'])) {
                $uspjesnaPrijava = true;
                $_SESSION['korisnicko_ime'] = $row['korisnicko_ime'];
                $_SESSION['razina_prava'] = $row['razina_prava'];
                $_SESSION['ime_korisnika'] = $row['ime']; 

                if ($row['razina_prava'] == 1) {
                    $admin = true;
                }
            } else {
                $porukaPrijava = 'Netočno korisničko ime i/ili lozinka.';
            }
        } else {
            $porukaPrijava = 'Netočno korisničko ime i/ili lozinka.';
        }
        mysqli_stmt_close($stmt);
    }
}

if (isset($_GET['logout'])) {
    session_unset(); 
    session_destroy(); 
    header('Location: administrator.php'); 
    exit();
}

$current_year = date("Y");
$current_date_footer = date("d.m.Y.");
?>

<!DOCTYPE html>
<html lang="hr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administracija - Novinski Portal</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .login-form-container {
            max-width: 400px;
            margin: 50px auto;
            padding: 25px;
            background-color: #f9f9f9;
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            text-align: center;
        }
        .login-form-container h2 {
            margin-bottom: 25px;
            color: #333;
        }
        .login-form-container .form-group label {
            text-align: left;
            margin-bottom: 8px;
            color: #555;
        }
        .login-form-container .form-group input[type="text"],
        .login-form-container .form-group input[type="password"] {
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 1em;
            margin-bottom: 15px; 
        }
        .login-form-container button {
            width: 100%;
            padding: 12px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1.1em;
            transition: background-color 0.3s ease;
        }
        .login-form-container button:hover {
            background-color: #0056b3;
        }
        .login-message {
            margin-top: 20px;
            font-size: 1.1em;
            color: #333;
        }
        .login-message.error {
            color: #dc3545; 
            font-weight: bold;
        }
        .login-message a {
            color: #007bff;
            text-decoration: none;
            font-weight: bold;
        }
        .login-message a:hover {
            text-decoration: underline;
        }
        .admin-articles-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 20px;
            padding: 20px;
        }
        .admin-article-item {
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 15px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
            display: flex;
            flex-direction: column;
        }
        .admin-article-item h3 {
            margin-top: 0;
            margin-bottom: 10px;
            color: #333;
        }
        .admin-article-item p {
            font-size: 0.9em;
            color: #666;
            flex-grow: 1; 
        }
        .admin-article-item .admin-actions {
            margin-top: 15px;
            display: flex;
            gap: 10px;
            justify-content: flex-end;
        }
        .admin-article-item .admin-actions a,
        .admin-article-item .admin-actions button {
            padding: 8px 12px;
            border-radius: 5px;
            text-decoration: none;
            font-size: 0.9em;
            cursor: pointer;
            border: none;
            transition: background-color 0.3s ease;
        }
        .admin-article-item .admin-actions .edit-btn {
            background-color: #ffc107;
            color: #333;
        }
        .admin-article-item .admin-actions .edit-btn:hover {
            background-color: #e0a800;
        }
        .admin-article-item .admin-actions .delete-btn {
            background-color: #dc3545;
            color: white;
        }
        .admin-article-item .admin-actions .delete-btn:hover {
            background-color: #c82333;
        }
        .admin-article-item .article-image {
            max-width: 100%;
            height: auto;
            border-radius: 4px;
            margin-bottom: 10px;
        }
        .welcome-message {
            text-align: center;
            margin-top: 20px;
            font-size: 1.2em;
            font-weight: bold;
            color: #007bff;
        }
        .logout-link {
            text-align: center;
            margin-top: 10px;
        }
        .logout-link a {
            color: #dc3545;
            text-decoration: none;
            font-weight: bold;
            font-size: 1.1em;
        }
        .logout-link a:hover {
            text-decoration: underline;
        }

        .admin-table {
            width: 90%;
            margin: 20px auto;
            border-collapse: collapse;
            font-size: 0.9em;
        }
        .admin-table th, .admin-table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        .admin-table th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        .admin-table tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .admin-table tr:hover {
            background-color: #f1f1f1;
        }
        .admin-table .actions a {
            margin-right: 10px;
            text-decoration: none;
            color: #007bff;
        }
        .admin-table .actions a.delete {
            color: #dc3545;
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
                <li><a href="registracija.php">Registracija</a></li>
                <li><a href="administrator.php">Administracija</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <?php if ($uspjesnaPrijava && $admin): ?>
            <p class="welcome-message">Dobrodošli,<b><?php echo htmlspecialchars($_SESSION['ime_korisnika']); ?></b>!</p>
            <p class="logout-link"><a href="administrator.php?logout=true">Odjava</a></p>

            <section class="admin-panel">
                <h2>Administracija vijesti</h2>
                <?php
                $query = "SELECT id, datum, naslov, sazetak, slika, kategorija, arhiva FROM vijesti ORDER BY id DESC";
                $result = mysqli_query($dbc, $query);

                if (!$result) {
                    die("Greška pri dohvaćanju vijesti iz baze: " . mysqli_error($dbc));
                }

                if (mysqli_num_rows($result) > 0): ?>
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Datum</th>
                                <th>Naslov</th>
                                <th>Kategorija</th>
                                <th>Arhivirano</th>
                                <th>Akcije</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($row['id']); ?></td>
                                    <td><?php echo htmlspecialchars($row['datum']); ?></td>
                                    <td><?php echo htmlspecialchars(stripslashes($row['naslov'])); ?></td>
                                    <td><?php echo htmlspecialchars($row['kategorija']); ?></td>
                                    <td><?php echo $row['arhiva'] ? 'Da' : 'Ne'; ?></td>
                                    <td class="actions">
                                        <a href="uredi.php?id=<?php echo htmlspecialchars($row['id']); ?>">Uredi</a> |
                                        <a href="delete.php?id=<?php echo htmlspecialchars($row['id']); ?>" class="delete" onclick="return confirm('Jeste li sigurni da želite obrisati ovu vijest: <?php echo htmlspecialchars(addslashes($row['naslov'])); ?>?');">Obriši</a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p>Trenutno nema vijesti u bazi.</p>
                <?php endif;
                mysqli_free_result($result);
                ?>
            </section>

        <?php elseif ($uspjesnaPrijava && !$admin): ?>
            <section class="login-form-container">
                <p class="login-message">
                    Dobrodošli,<b> <?php echo htmlspecialchars($_SESSION['ime_korisnika']); ?></b>!
                    Nažalost, nemate administratorska prava za pristup ovoj stranici.
                </p>
                <p class="logout-link"><a href="administrator.php?logout=true">Odjava</a></p>
            </section>

        <?php else: ?>
            <section class="login-form-container">
                <h2>Prijava za administraciju</h2>
                <?php if ($porukaPrijava): ?>
                    <p class="login-message error"><?php echo htmlspecialchars($porukaPrijava); ?></p>
                <?php endif; ?>
                <form action="administrator.php" method="POST">
                    <div class="form-group">
                        <label for="korisnicko_ime_prijava">Korisničko ime:</label>
                        <input type="text" id="korisnicko_ime_prijava" name="korisnicko_ime_prijava" required>
                    </div>
                    <div class="form-group">
                        <label for="lozinka_prijava">Lozinka:</label>
                        <input type="password" id="lozinka_prijava" name="lozinka_prijava" required>
                    </div>
                    <button type="submit" name="prijava">Prijava</button>
                </form>
                <p class="login-message">
                    Niste registrirani? <a href="registracija.php">Registrirajte se ovdje!</a>
                </p>
            </section>

        <?php endif; ?>
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