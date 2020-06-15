<div class="card mb-3">
  <div class="card-header">
    Dodawanie kategorii
  </div>
  <div class="card-body">
    <?php
      // Tutaj sprawdzamy google captcha, aby uniknąć spammu.
      if(isset($_POST['g-recaptcha-response'])){ 
        $gRecaptchaResponse=$_POST['g-recaptcha-response'];
        $recaptcha = new \ReCaptcha\ReCaptcha($config['recaptcha_private']);
        $resp = $recaptcha->verify($gRecaptchaResponse, $_SERVER['REMOTE_ADDR']);
        if ($resp->isSuccess()) {
          // Jeśli submit-category został kliknięty, dodajemy kategorię do bazy danych.
          if (isset($_POST['submit-category'])) {
            $newCategoryName = strtolower($_POST['name']);
            $stmt = $dbh->prepare("INSERT INTO kategorie (name, created)
                                    VALUES (:name,  NOW())");
              $stmt->execute([':name' => $newCategoryName]);
              }
        } else {
          $errors = $resp->getErrorCodes();
          print '<p style="font-weight: bold; color: red;">Captcha incorrect.</p>';
        }
      }
      // Jeśli submit-delete-category został kliknięty, usuwamy kategorię z bazy danych.
      if(isset($_POST['submit-delete-category'])){
        $deleteCategoryName = $_POST['delete-category'];
        $stmt = $dbh->prepare("DELETE FROM kategorie WHERE name = :name");
        if($deleteCategoryName!="all")
        $stmt->execute([':name' => $deleteCategoryName]);
    }
    ?>
    <!-- Dodajemy form do zbierania nowych kategorii.  -->
    <form action="/add_category" method="POST" class="with-border">
      <div class="form-group">
        <label for="name">Nazwa twojej kategorii</label>
        <input type="text" class="form-control" name="name" placeholder="np. 'Obrazy'" maxlength = "15">
      </div>
      <div class="g-recaptcha" data-sitekey="<?php
              include("config.inc.php");
              print $config['recaptcha_public'];
              ?>">
      </div>
      <button type="submit" class="btn btn-secondary" name="submit-category">Dodaj</button>
    </form>

  </div>
</div>

<div class="card mb-3">
  <div class="card-header">
    Usuwanie kategorii
  </div>
  <div class="card-body">
    <!-- Dodajemy form do usuwania kategorii.  -->
    <form action="/add_category" method="POST" class="with-border">
        <div class="form-group">
            <label for="categories">Którą kategorie mam usunąć?</label>
            <select class="form-control" name="delete-category">
                <?php
                $stmt = $dbh->prepare("SELECT * FROM kategorie WHERE name != 'all' ");
                $stmt->execute();
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    print'<option>'.$row['name'].'</option>)';
                }
                ?>
            </select>
        </div>
      <button type="submit" class="btn btn-secondary" name="submit-delete-category">Usuń</button>
    </form>
  </div>
</div>