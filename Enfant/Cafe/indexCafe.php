<!DOCTYPE html>
<html>
<head>
    <title>Cherry -- Café</title>
	<!--BOOTSTRAP-->
	<!-- aaa<link href="../../bootstrap/css/bootstrap.min.css" rel="stylesheet">-->
	<!--<script src="bootstrap/js/jquery.js"></script>
	<script src="bootstrap/js/bootstrap.min.js"></script>-->
	<link rel="stylesheet" href="indexCafe.css" />
	<!--aaaa<link href="../../bootstrap/css/bootstrap.css" rel="stylesheet">-->
	<meta charset="utf-8" />
	<link rel="icon" href="../../Affichage/favicon.png">
</head>

<body>
    <?php include("../header_footer/header.php"); ?>

 	<div id="encadre_def">  

	<div id="video" class="categorie_def">
		<img src="../image/logo/webcam.png" class="image_def" alt="chat vidéo" />
		<h1 class = "titre_def">Chat vidéo</h1>
		<div class="contenucateg"> 
		<?php include("video/video.php"); ?>
		</div>
	</div>
	<div id="email" class="categorie_def">
		<img src="../image/logo/email.png" class="image_def" alt="email" />
		<h1 class = "titre_def">Mes emails</h1>
		<div class="contenucateg"> 
		<?php include("email/email.php"); ?>
		</div>
	</div>
	<div id="chat" class="categorie_def">
		<img src="../image/logo/bulle.png" class="image_def" alt="chat" />
		<h1 class = "titre_def">Chat</h1>
		<div class="contenucateg"> 
			 <?php include("chat/chat.php"); ?>
		</div>
	</div>
	<div id ="primakid" class="categorie_def">
		<img src="../image/logo/webcam.png" class="image_def" alt="primakid" />
		<h1 class = "titre_def">Primakid</h1>
		<div class="contenucateg"> 
			<?php include("primakid/primakid.php"); ?>
		</div>
	</div>
	

	</div>

	<div id="contaffiche_def">
		<h1 class = "titreaffiche"></h1>
		<div class="contenuaffiche">
		</div>
	</div>

    <script src="indexCafe.js" ></script>   

    <?php $lieu="cafe" ?>
    <?php include("../header_footer/footer.php"); ?>
		
		
    </body>
</html>