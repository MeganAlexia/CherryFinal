<!DOCTYPE html>
<html>
    <head>
        <title>Cherry -- Village</title>
		<!--[if lt IE 9]>
            <script src="http://github.com/aFarkas/html5shiv/blob/master/dist/html5shiv.js"></script>
        <![endif]-->
		<!--BOOTSTRAP-->
		<link href="../bootstrap/css/bootstrap.min.css" rel="stylesheet">
		<meta charset="utf-8" />
		<link rel="stylesheet" href="indexChild.css" />
		<link rel="icon" href="../Affichage/favicon.png">
    </head>

	
    <body> 
        	
        <div class="container-fluid">

            <header>

                        <div class="page-header">
                                <img src="../Affichage/avatar1.png" class="avatar" alt="avatar" />
                                <img src="../Affichage/rouages.png" class="parametre" alt="paramètres" />
                                <img src="../Affichage/compte.png" class="compte" alt="compte" />
                        </div>

                </header>

                <!-- Images batiments -->			
                <img src="image/fond/cafe.png" id="cafe" class="batiment" alt="cafe" />
                <img src="image/logo/playground.png" id="jeux" class="batiment" alt="jeux" />
                <img src="image/fond/hopital.png" id="hopital" class="batiment" alt="Hôpital" />
                <img src="image/logo/livres.png" id="bib" class="batiment" alt="Bibliothèque" />
                <img src="image/logo/ecole.jpg" id="ecole" class="batiment" alt="école" />			

                <!-- Images iles -->			
                <img src="image/fond/cafe_ile.png" id="ile_cafe" class="ile" alt="cafe" />
                <img src="image/fond/hopital_ile.png" id="ile_hopital" class="ile" alt="Hôpital" />
                <!-- <img src="image/logo/playground.png" id="ile_jeux" class="ile" alt="jeux" />
                <img src="image/building/hospital.png" id="ile_hopital" class="ile" alt="Hôpital" />
                <img src="image/logo/livres.png" id="ile_bib" class="ile" alt="Bibliothèque" />
                <img src="image/logo/ecole.jpg" id="ile_ecole" class="ile" alt="école" /> -->

                <!-- Points d exclamation -->
                <img src="image/logo/exclam.gif" id="ex_cafe" class="exclam" alt="Nouveau contenu!" />
                <img src="image/logo/exclam.gif" id="ex_jeux" class="exclam" alt="Nouveau contenu!" />
                <img src="image/logo/exclam.gif" id="ex_hopital" class="exclam" alt="Nouveau contenu!" />
                <img src="image/logo/exclam.gif" id="ex_bib" class="exclam" alt="Nouveau contenu!" />
                <img src="image/logo/exclam.gif" id="ex_ecole" class="exclam" alt="Nouveau contenu!" />

                <!-- Destination du personnage -->
                <div id = "dest" class = "def"> </div>

        <!-- avatar, mongolfiere, cherry, lieu recupéré de l'url -->
        <?php
        if (isset($_GET['lieu'] ))
        {
                ?>
                <div id="personnage" class= "<?php echo $_GET['lieu']?>">
                <div id="perso_image"></div>
                <img src="image/sprites/mongolfiere1.png" id="mongolfiere" />
                </div>
                <div id="cherry"><div id="cherry_image"></div></div>
                <?php
        }
        else 
        {
                ?>
                <div id="personnage" class= "hopital"> 
                <div id="perso_image"></div>
                <img src="image/sprites/mongolfiere1.png" id="mongolfiere" />
                </div>
                <div id="cherry"><div id="cherry_image"></div></div>
                <?php
        }
        ?>

                <!-- autres animations -->
                <img src="image/fond/hopital_arbre.png" id="arbre_hopital" />
                <img src="image/sprites/avion.png" id="ovis" />
                <div id="dest_ovis"></div>
                <img src="image/sprites/helico.png" id="helico" />
                <img src="image/logo/help.png" id="im_visite" />

                <!-- fenetres -->
                <div id="cadre_choix_perso" class="replie"><p>Choisis ton avatar</p></div>
                <div id="m_suggestion">
                <div class="contenu">
                        <img src="image/fond/bulle.png" class="im_fond"/>
                        <p>Tu as un nouveau message au cybercafé! Et si on allait voir?</p>
                        <p class="bouton1">Allons-y!</p>
                        <img src="image/logo/cherry.png" class="im_cherry"/>
                        <img src="image/logo/close.png" class="bouton_fermeture"/>
                </div>
                </div>
                <div id="m_visite">
                <div class="contenu">
                        <img src="image/fond/bulle.png" class="im_fond"/>

                        <p id="m_visite_contenu">Bonjour! Je peux te faire visiter l'Univers Cherry. Tu es prêt?</p>

                        <p class="bouton1">Allons-y!</p>
                        <p class="bouton2">Pas maintenant.</p>
                        <img src="image/logo/fleche.png" class="im_fleche"/>
                        <img src="image/logo/cherry.png" class="im_cherry"/>
                        <img src="image/logo/close.png" class="bouton_fermeture"/>
                </div>
                </div>

                <!-- scripts -->
                <script src="../jquery.js"></script>
                <script src="../jquery.cookie.js"></script>
                <script src="jquery.animateSprite.min.js"></script>
                <script src="indexChild.js"></script> 

                <footer>
                 <p> <a href="../logout.php"> <img src="image/logo/disconnect.png" class="image1" alt="Déconnexion" /></a></p>
                </footer>

        </div>
    </body>
</html>