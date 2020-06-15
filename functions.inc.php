<?php

function domena() {
    $domena = preg_replace('/[^a-zA-Z0-9\.]/', '', $_SERVER['HTTP_HOST']);
    return $domena;
}
function pullGallery($dbh) {
    if(isset($_GET['show']) && intval($_GET['show']) > 1){
		$kategoriaNum = intval($_GET['show']);
		$tmt = $dbh->prepare("SELECT * FROM kategorie WHERE id = :id");
		$tmt -> execute([':id' => $kategoriaNum]);
		$kategoria = $tmt->fetch(PDO::FETCH_ASSOC);
		$stmt = $dbh->prepare("SELECT * FROM galery WHERE kategoria = :kategoria");
		$stmt -> execute([':kategoria' => $kategoria['name']]);
	} else {
		$stmt = $dbh->prepare("SELECT * FROM galery");
        $stmt -> execute();
    } 
    return $stmt;
  }