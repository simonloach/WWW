<?php
    define("IN_INDEX", 1);
    ini_set('display_errors', 1);
    require __DIR__ . '/vendor/autoload.php';
    error_reporting(E_ALL);
    // Wczytanie z plików potrzebnych rzeczy.
    include("functions.inc.php");
    include("config.inc.php");

    // Logowanie do bazy danych na podstawie pliku config.
    if (isset($config) &&
     is_array($config)) {
        try {
            $dbh = new PDO('mysql:host=' . $config['db_host'] . ';dbname=' . $config['db_name'] . ';charset=utf8mb4', $config['db_user'], $config['db_password']);
            $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            print "Nie mozna polaczyc sie z baza danych: " . $e->getMessage();
            exit();
        }
    } else {
        exit("Nie znaleziono konfiguracji bazy danych.");
    }
?>


<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Strona <?php print domena(); ?></title>
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <!--  "Instalacja" bootstrapa, google captcha, jquery, css itd. -->
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
        <link rel="stylesheet" type="text/css" href="https://s24.labwww.pl/style.css">
        <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>        
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
        <script src="https://www.google.com/recaptcha/api.js" async defer></script>
        <script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
    </head>
    <body>
        <!-- Pasek nawigacyjny -->
        <nav class="navbar navbar-expand-sm navbar-dark bg-dark fixed-top">
          <div class="container">
          <a class="navbar-brand" href="#"><?php print domena(); ?></a>
          <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav" id="menu-buttons">
                <li class="nav-item active">
                    <a class="nav-link" href="/index.php">Strona główna</span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/photo_galery">Wszystkie zdjęcia</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/add_photos">Dodaj zdjęcia</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/add_category">Dodaj(usuń też) kategorie</a>
                </li>
                </ul>
            </div>
          </div>
        </nav>

        <div class="jumbotron">
            <div class="container">
                <h1 class="display-4">Galeria zdjęć na WWW.</h1>
            </div>
        </div>
        <div class="container mb-5">
                    <?php
                        // Limiter stron do tych zawartych w hardcode'owanym arrayu
                        $allowed_pages = ['main','photo_galery', 'add_photos', 'add_category'];
                        if (isset($_GET['page']) &&
                            $_GET['page'] && 
                            in_array($_GET['page'], $allowed_pages)) {
                            // Sprawdzanie czy jest taki plik(strona) na serwerze.
                            if (file_exists($_GET['page'] . '.php')) {
                                include($_GET['page'] . '.php');
                            } else {
                                print 'Plik ' . $_GET['page'] . '.php nie istnieje.';
                            }
                        } else {
                            include('main.php');
                        }
                    ?>
        </div>
        <footer class="footer fixed-bottom mt-auto" style="background-color: #f5f5f5;">
          <div class="container">
            <span class="text-muted">Aktualna data: <?php print date('Y-m-d'); ?></span>
          </div>
        </footer>
    </body>
</html>
<!-- <script>
    // Skrypt do blokowania konsoli pod F12.
    $(document).keydown(function(e){
        if(e.which === 123){
            return false;
        }
    });
    // Skrypt do wyłączania PPM
    document.addEventListener("contextmenu", function(e){
        e.preventDefault();
    }, false);
</script> -->
