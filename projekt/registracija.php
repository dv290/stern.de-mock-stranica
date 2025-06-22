<?php
include 'dbconn.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$current_year = date("Y");
$current_date_footer = date("d.m.Y.");

$registration_success = false;
$error_message = '';
$ime = '';
$prezime = '';
$korisnicko_ime = '';

if (isset($_POST['registriraj'])) {
    $ime = trim($_POST['ime']);
    $prezime = trim($_POST['prezime']);
    $korisnicko_ime = trim($_POST['korisnicko_ime']);
    $lozinka = $_POST['lozinka'];
    $lozinka_ponovi = $_POST['lozinka_ponovi'];

    if (empty($ime) || empty($prezime) || empty($korisnicko_ime) || empty($lozinka) || empty($lozinka_ponovi)) {
        $error_message = 'Sva polja moraju biti popunjena.';
    } elseif ($lozinka !== $lozinka_ponovi) {
        $error_message = 'Lozinke se ne podudaraju.';
    } elseif (strlen($lozinka) < 6) { 
        $error_message = 'Lozinka mora imati najmanje 6 znakova.';
    } else {
        $query_check_username = "SELECT korisnicko_ime FROM korisnik WHERE korisnicko_ime = ?";
        $stmt_check = mysqli_prepare($dbc, $query_check_username);
        
        if ($stmt_check === false) {
            die("Greška pri pripremi SQL upita za provjeru korisničkog imena: " . mysqli_error($dbc));
        }
        
        mysqli_stmt_bind_param($stmt_check, 's', $korisnicko_ime);
        mysqli_stmt_execute($stmt_check);
        mysqli_stmt_store_result($stmt_check); 
        
        if (mysqli_stmt_num_rows($stmt_check) > 0) {
            $error_message = 'Korisničko ime je već zauzeto. Molimo odaberite drugo.';
        } else {
            $hashed_password = password_hash($lozinka, PASSWORD_BCRYPT);
            
            $razina_prava = 0; 

            $query_insert_user = "INSERT INTO korisnik (ime, prezime, korisnicko_ime, lozinka_hash, razina_prava) VALUES (?, ?, ?, ?, ?)";
            $stmt_insert = mysqli_prepare($dbc, $query_insert_user);
            
            if ($stmt_insert === false) {
                die("Greška pri pripremi SQL upita za registraciju: " . mysqli_error($dbc));
            }
            
            mysqli_stmt_bind_param($stmt_insert, 'ssssi', $ime, $prezime, $korisnicko_ime, $hashed_password, $razina_prava);
            
            if (mysqli_stmt_execute($stmt_insert)) {
                $registration_success = true;
                $ime = ''; 
                $prezime = '';
                $korisnicko_ime = '';
            } else {
                $error_message = 'Greška prilikom registracije. Pokušajte ponovno.';
            }
            mysqli_stmt_close($stmt_insert);
        }
        mysqli_stmt_close($stmt_check);
    }
}
?>

<!DOCTYPE html>
<html lang="hr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registracija - Novinski Portal</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .form-container {
            max-width: 600px;
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
        .form-group input[type="password"],
        .form-group input[type="email"] { 
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }
        .form-buttons {
            text-align: right;
            margin-top: 20px;
        }
        .form-buttons button {
            padding: 10px 20px;
            background-color: #28a745; 
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s ease;
        }
        .form-buttons button:hover {
            background-color: #218838;
        }
        .error-message {
            color: red;
            margin-top: 5px;
            font-size: 0.9em;
            text-align: center;
            font-weight: bold;
        }
        .success-message {
            color: green;
            margin-top: 5px;
            font-size: 0.9em;
            text-align: center;
            font-weight: bold;
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
            <h2>Registracija novog korisnika</h2>

            <?php if ($error_message): ?>
                <p class="error-message"><?php echo htmlspecialchars($error_message); ?></p>
            <?php elseif ($registration_success): ?>
                <p class="success-message">Registracija uspješna! Možete se prijaviti.</p>
            <?php endif; ?>

            <form action="registracija.php" method="POST">
                <div class="form-group">
                    <label for="ime">Ime:</label>
                    <input type="text" id="ime" name="ime" value="<?php echo htmlspecialchars($ime); ?>" required>
                </div>

                <div class="form-group">
                    <label for="prezime">Prezime:</label>
                    <input type="text" id="prezime" name="prezime" value="<?php echo htmlspecialchars($prezime); ?>" required>
                </div>

                <div class="form-group">
                    <label for="korisnicko_ime">Korisničko ime:</label>
                    <input type="text" id="korisnicko_ime" name="korisnicko_ime" value="<?php echo htmlspecialchars($korisnicko_ime); ?>" required>
                </div>

                <div class="form-group">
                    <label for="lozinka">Lozinka:</label>
                    <input type="password" id="lozinka" name="lozinka" required>
                </div>

                <div class="form-group">
                    <label for="lozinka_ponovi">Ponovite lozinku:</label>
                    <input type="password" id="lozinka_ponovi" name="lozinka_ponovi" required>
                </div>

                <div class="form-buttons">
                    <button type="submit" name="registriraj">Registriraj se</button>
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