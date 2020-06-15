<div class="card mb-3">
  <div class="card-header">
    Dodaj zdjęcie poniżej.
  </div>
  <div class="card-body">
    <?php
      // Predefiniowana lista dozwolonych rozszerzeń akceptowanych przez POST.
      $ALLOWED_EXTENSIONS = array('jpg', 'jpeg', 'png');
      if (!defined('IN_INDEX')) { exit("Nie można uruchomić tego pliku bezpośrednio."); }
      // Google Captcha sprawdzenie
      if(isset($_POST['g-recaptcha-response'])){
        $gRecaptchaResponse=$_POST['g-recaptcha-response'];
        $recaptcha = new \ReCaptcha\ReCaptcha($config['recaptcha_private']);
        $resp = $recaptcha->verify($gRecaptchaResponse, $_SERVER['REMOTE_ADDR']);
        if ($resp->isSuccess()) {
          // Sprawdzam czy ktoś klinął submit-image.
          if (isset($_POST['submit-image'])) {
            // Jeśli tak to bind te zmienne.
            $ip = $_SERVER['REMOTE_ADDR'];
            $file = $_FILES['img'];
            $fileName = $file['name'];
            $fileTmp = $file['tmp_name'];
            $fileSize = $file['size'];
            $fileError = $file['error'];
            // to samo co split(".")
            $temporary = explode('.', $fileName);
            // Wybieram rozszerzenie jako ostatni element wyniku explode()
            $fileExt = strtolower(end($temporary));
            // Sprawdza extensions i czy są errory w file
            if ($fileError === 0 && in_array($fileExt, $ALLOWED_EXTENSIONS)){ 
              $newFileUniqueID = uniqid('', true) . "." . $fileExt;
              $newFileTitle = $_POST['title'];
              $newFileDescription = $_POST['description'];
              $newFileCategories = $_POST['categories'];
              $newFileSourceIP = $_SERVER['REMOTE_ADDR'];
              // Ścieżka docelowa + unikalne ID
              $newFileDestination = 'GaleryStorage/'.$newFileUniqueID;
              move_uploaded_file($fileTmp, $newFileDestination);
              // Push do bazy danych.
              $stmt = $dbh->prepare("INSERT INTO galery (id, title, description, kategoria, created, ip)
                                      VALUES (:id, :title, :description, :categories, NOW(), :ip)");
              $stmt->execute([':id' => $newFileUniqueID,
                              ':title' => $newFileTitle,
                              ':description' => $newFileDescription,
                              ':categories' => $newFileCategories,
                              ':ip' => $newFileSourceIP]);
              }
        }
        } else {
          $errors = $resp->getErrorCodes();
          print '<p style="font-weight: bold; color: red;">Captcha incorrect.</p>';
        }
      }
    ?>
    <!-- Form do dodawania zdjęć -->
    <form action="/add_photos" method="POST" enctype="multipart/form-data" class="with-border">
      <!-- Nazwa -->
      <div class="form-group">
        <label for="title">Nazwa twojego zdjęcia</label>
        <input type="text" class="form-control" name="title" placeholder="np. 'zdjecie3'" maxlength = "15">
      </div>
      <!-- Opis -->
      <div class="form-group">
        <label for="description">Opis zdjęcia</label>
        <input type="text" name="description" placeholder="keep it short" class="form-control" maxlength = "100">
      </div>
      <!-- Wybór kategorii z listy -->
      <div class="form-group">
        <label for="categories">Kategoria (jak nie ma pasującej to sobie dodaj na górze)</label>
        <select class="form-control" name="categories">
          <?php
          $stmt = $dbh->prepare("SELECT * FROM kategorie");
          $stmt->execute();
          while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            print'<option>'.$row['name'].'</option>)';
          }
          ?>
        </select>
      </div>
      <!-- Drag&Drop -->
      <div class="form-group">
        <label for="img">Wybierz plik lub po prostu go przeciągnij</label>
        <input class = "dropzone" id="drop" type="file" name="img" accept="image/*" required>
      </div>
      <!-- Google Captcha -->
      <div class="g-recaptcha" data-sitekey="<?php
              include("config.inc.php");
              print $config['recaptcha_public'];
              ?>">
      </div>
      <button type="submit" class="btn btn-secondary" name="submit-image">Wrzuć</button>
    </form>
  </div>
</div>