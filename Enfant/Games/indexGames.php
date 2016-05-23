<!DOCTYPE html>
<html>
<head>
    <title>Cherry -- Jeux</title>
	<!--BOOTSTRAP-->
	<!-- aaa<link href="../../bootstrap/css/bootstrap.min.css" rel="stylesheet">-->
	<!--<script src="bootstrap/js/jquery.js"></script>
	<script src="bootstrap/js/bootstrap.min.js"></script>-->
	<link rel="stylesheet" href="indexGames.css" />
	<link rel="icon" href="../../Affichage/favicon.png">
	<!--aaaa<link href="../../bootstrap/css/bootstrap.css" rel="stylesheet">-->
	<meta charset="utf-8" />
</head>

<body>
    <?php include("../header_footer/header.php"); ?>

 	<div id="encadre_def">  

	<div id="jeux1" class="categorie_def">
		<img src="../image/logo/cible.png" class="image_def" alt="Jeux" />
		<h1 class = "titre_def">Jeux d'adresse</h1>
		<div class="contenucateg"> 
		<?php include("j1/j1.php"); ?>
		</div>
	</div>
	<div id="jeux2" class="categorie_def">
		<img src="../image/logo/des.png" class="image_def" alt="Jeux" />
		<h1 class = "titre_def">jeux</h1>
		<div class="contenucateg"> 
			<?php include("j2/j2.php"); ?>
		</div>
	</div>
	<div id="coloriages" class="categorie_def">
		<img src="../image/logo/chess.png" class="image_def" alt="Coloriages" />
		<h1 class = "titre_def">Coloriages</h1>
		<div class="contenucateg"> 
			<?php include("j3/j3.php"); ?>
	
		</div>
	</div>
	<div id ="Jeux3" class="categorie_def">
		<img src="../image/logo/Luma.png" class="image_def" alt="Jeux" />
		<h1 class = "titre_def">Jeux</h1>
		<div class="contenucateg"> 
			<?php include("j4/j4.php"); ?>
		</div>
	</div>
	

	</div>

	<div id="contaffiche_def">
		<h1 class = "titreaffiche"></h1>
		<div class="contenuaffiche">
		</div>
	</div>

    <script src="indexGames.js" ></script>   

    <?php
    $lieu="jeux"
    ?>
    <?php include("../header_footer/footer.php"); ?>
		
		
    </body>
</html>