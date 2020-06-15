<div class="row" id="buttons">
	<div class="dropdown">
		<button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
			<?php if (isset($_GET['show']) && intval($_GET['show']) > 0) {
				$id = intval($_GET['show']);
				$stmt = $dbh->prepare("SELECT * FROM kategorie WHERE id = :id");
				$stmt -> execute([':id' => $id]);
				$category = $stmt->fetch();
				print $category['name'];
			}else {
				print 'all';
			}?>
		</button>
		<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
			<?php
				$stmt = $dbh->prepare("SELECT * FROM kategorie");
				$stmt -> execute();
				while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
					print '<a class="dropdown-item" href="/photo_galery/show/'. $row['id'] .'">'. $row['name'] .'</a>';
				}
			?>
		</div>
	</div>
</div>
			
<div class="row" id="gallery" data-toggle="modal" data-target="#exampleModal">
<?php
	$stmt = pullGallery($dbh);
	$i=0;
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        print'
            <div class="col-12 col-sm-6 col-md-3 col-lg-3 ">
                <a class="black-text" href="https://s24.labwww.pl/GaleryStorage/'.$row['id'].'" data-target="#carouselExample" data-slide-to="'.$i.'">
                    <img class ="w-100 zdjecia" src="https://s24.labwww.pl/GaleryStorage/'.$row['id'].'" data-target="#carouselExample" data-slide-to="'.$i.'">
                        <h3 class="text-center">'.$row['title'].'</h3>
                </a>
			</div>';
		$i++;
    }
?>
</div>
<!-- Tutaj jest robiona karuzela, z tutorialu - autor nie wiedział o co chodzi, więc też nie czułem się zobowiązany zrozumieć za dużo. -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div id="carouselExample" class="carousel slide" data-ride="carousel">
          <ol class="carousel-indicators">
			<?php
			$i=0;
			$stmt = pullGallery($dbh);
			while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
				print'<li data-target="#carouselExample" data-slide-to="' .$i. '"';
				if($i==0){
					print' class="active"></li> ';
				} else {
					print' ></li>';
				}
				$i++;
			}
			?>
          </ol>
          <div class="carousel-inner">
			  <?php
			  	$i=0;
			  	$stmt = pullGallery($dbh);
			  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
				  if($i==0){
					  print'<div class="carousel-item active">
					  			<a href="/GaleryStorage/'.$row['id'].'">
								  <img class="d-block w-100" src="https://s24.labwww.pl/GaleryStorage/'.$row['id'].'" alt="'.$i.' slide">
								</a>
							</div>';
				  } else {
					  print'<div class="carousel-item"><img class="d-block w-100" src="https://s24.labwww.pl/GaleryStorage/'.$row['id'].'" alt="'.$i.' slide"></div>';
				  }
				  $i++;
				}
			  ?>
          </div>
          <a class="carousel-control-prev" href="#carouselExample" role="button" data-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="sr-only">Previous</span>
          </a>
          <a class="carousel-control-next" href="#carouselExample" role="button" data-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="sr-only">Next</span>
          </a>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>