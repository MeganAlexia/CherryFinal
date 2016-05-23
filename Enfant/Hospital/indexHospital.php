<!DOCTYPE html>
<html>
<head>
    <title>Cherry -- Hôpital</title>
	<!--BOOTSTRAP-->
	<!-- aaa<link href="../../bootstrap/css/bootstrap.min.css" rel="stylesheet">-->
	<!--<script src="bootstrap/js/jquery.js"></script>
	<script src="bootstrap/js/bootstrap.min.js"></script>-->
	<link rel="stylesheet" href="indexHospital.css" />
	<link rel="icon" href="../../Affichage/favicon.png">
	<!--aaaa<link href="../../bootstrap/css/bootstrap.css" rel="stylesheet">-->
	<meta charset="utf-8" />
</head>

<body>
    <?php include("../header_footer/header.php"); ?>

 	<div id="encadre_def">  

	<div id="trombi" class="categorie_def">
		<img src="../image/logo/nurse.png" class="image_def" alt="trombinoscope" />
		<h1 class = "titre_def">Trombinoscope</h1>
		<div class="contenucateg"> 
			<?php include("trombinoscope/Trombi.php"); ?>
		</div>
	</div>
	<div id="sejour" class="categorie_def">
		<img src="../image/logo/heart.png" class="image_def" alt="mon séjour" />
		<h1 class = "titre_def">Mon séjour</h1>
		<div class="contenucateg"> 
			<?php include("sejour/sejour.php"); ?> 
		</div>
	</div>
	<div id="quizz" class="categorie_def">
		<img src="../image/logo/lexique.png" class="image_def" alt="Quizz" />
		<h1 class = "titre_def">Quizz</h1>
		<div class="contenucateg"> 
			 <?php include("quizz/quizz.php"); ?>
		</div>
	</div>
	<div id ="info" class="categorie_def">
		<img src="../image/logo/croix.png" class="image_def" alt="Informations" />
		<h1 class = "titre_def">Informations</h1>
		<div class="contenucateg"> 
			<?php include("info/info.php"); ?>
		</div>
	</div>
	

	</div>

	<div id="contaffiche_def">
		<h1 class = "titreaffiche"></h1>
		<div class="contenuaffiche">
		</div>
	</div>

    <script src="indexHospital.js" ></script>

    <?php
    $lieu="hopital"
    ?> 

    <?php include("../header_footer/footer.php"); ?>  
		
		
    </body>
</html>