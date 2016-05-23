<!DOCTYPE html>
<html>
<head>
    <title>Cherry -- Bibliothèque</title>
	<!--BOOTSTRAP-->
	<!-- aaa<link href="../../bootstrap/css/bootstrap.min.css" rel="stylesheet">-->
	<!--<script src="bootstrap/js/jquery.js"></script>
	<script src="bootstrap/js/bootstrap.min.js"></script>-->
	<link rel="stylesheet" href="indexLibraries.css" />
	<link rel="icon" href="../../Affichage/favicon.png">
	<!--aaaa<link href="../../bootstrap/css/bootstrap.css" rel="stylesheet">-->
	<meta charset="utf-8" />
</head>

<body>
    <?php include("../header_footer/header.php"); ?>

 	<div id="encadre_def">  

	<div id="livres" class="categorie_def">
		<img src="../image/logo/livres.png" class="image_def" alt="mes livres" />
		<h1 class = "titre_def">Mes livres</h1>
		<div class="contenucateg"> 
			<?php include("livres/livres.php"); ?>
		</div>
	</div>
	<div id="photos" class="categorie_def">
		<img src="../image/logo/photo.png" class="image_def" alt="mes photos" />
		<h1 class = "titre_def">Mes photos</h1>
		<div class="contenucateg"> 
			<?php include("photos/photos.php"); ?>
		</div>
	</div>
	<div id="musiques" class="categorie_def">
		<img src="../image/logo/musique.png" class="image_def" alt="ma musique" />
		<h1 class = "titre_def">Ma musique</h1>
		<div class="contenucateg"> 
			<?php include("musique/musique.php"); ?> 
		</div>
	</div>
	<div id ="video" class="categorie_def">
		<img src="../image/logo/video.png" class="image_def" alt="mes vidéos" />
		<h1 class = "titre_def">Mes vidéos</h1>
		<div class="contenucateg"> 
			<?php include("video/video.php"); ?>
		</div>
	</div>
	

	</div>

	<div id="contaffiche_def">
		<h1 class = "titreaffiche"></h1>
		<div class="contenuaffiche">
		</div>
	</div>

    <script src="indexLibraries.js" ></script>   

    <?php
    $lieu="bib"
    ?>
    <?php include("../header_footer/footer.php"); ?>
		
		
    </body>
</html>