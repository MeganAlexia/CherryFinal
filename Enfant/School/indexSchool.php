<!DOCTYPE html>
<html>
<head>
    <title>Cherry -- Ecole</title>
	<!--BOOTSTRAP-->
	<!-- aaa<link href="../../bootstrap/css/bootstrap.min.css" rel="stylesheet">-->
	<!--<script src="bootstrap/js/jquery.js"></script>
	<script src="bootstrap/js/bootstrap.min.js"></script>-->
	<link rel="stylesheet" href="indexSchool.css" />
	<link rel="icon" href="../../Affichage/favicon.png">
	<!--aaaa<link href="../../bootstrap/css/bootstrap.css" rel="stylesheet">-->
	<meta charset="utf-8" />
</head>

<body>
    <?php include("../header_footer/header.php"); ?>

 	<div id="encadre_def">  

	<div id="cours" class="categorie_def">
		<img src="../image/logo/livres.png" class="image_def" alt="mes cours" />
		<h1 class = "titre_def">Mes Cours</h1>
		<div class="contenucateg"> 
			<?php include("cours/cours.php"); ?>
		</div>
	</div>
	<div id="exercices" class="categorie_def">
		<img src="../image/logo/photo.png" class="image_def" alt="mes exercices" />
		<h1 class = "titre_def">Mes exercices</h1>
		<div class="contenucateg"> 
			<?php include("exercices/exercices.php"); ?>
		</div>
	</div>
	<div id="ent" class="categorie_def">
		<img src="../image/logo/chess.png" class="image_def" alt="Mon ENT" />
		<h1 class = "titre_def">Mon ENT</h1>
		<div class="contenucateg"> 
			<?php include("ent/ent.php"); ?>
		</div>
	</div>
	<div id ="examens" class="categorie_def">
		<img src="../image/logo/Luma.png" class="image_def" alt="Mes examens" />
		<h1 class = "titre_def">Mes examens</h1>
		<div class="contenucateg"> 
			<?php include("examens/examens.php"); ?>

		</div>
	</div>
	

	</div>

	<div id="contaffiche_def">
		<h1 class = "titreaffiche"></h1>
		<div class="contenuaffiche">
		</div>
	</div>

    <script src="indexSchool.js" ></script>   

    <?php
    $lieu="ecole"
    ?>

    <?php include("../header_footer/footer.php"); ?>
		
		
    </body>
</html>