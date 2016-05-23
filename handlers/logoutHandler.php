<?php	
	//on détruit les variables de notre session
	session_unset();	
	
	//on détruit notre session
	session_destroy();
	
	//on redirige l'utilisateur vers la page d'accueil
	echo "<script type='text/javascript'>document.location.replace('../index.html');</script>";
?>