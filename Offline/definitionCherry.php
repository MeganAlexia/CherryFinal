<!DOCTYPE html>
<html>
    <head>
        <title>Qu'est ce que Cherry ?</title>
		<!--BOOTSTRAP-->
		<link href="../bootstrap/css/bootstrap.min.css" rel="stylesheet">
		<!--<script src="bootstrap/js/jquery.js"></script>
		<script src="bootstrap/js/bootstrap.min.js"></script>-->
       
	   <meta charset="utf-8" />
	   <link rel="stylesheet" href="indexOffline.css" />
       <script type="text/javascript">
            document.createElement('video');document.createElement('audio');document.createElement('track');
        </script>
        ​<script src="../jwplayer.js" ></script>
        <script>jwplayer.key="Cjd8Xr6I0ioLaFl64eNFuYC0SS6y+tmBOiSkRg==";</script>
    </head>
    
    <body>
        
        <header>
          <?php include("../Affichage/header.php"); ?>
        </header>
        
        <h1>Bonjour! Je suis Cherry le robot!</h1>

        <object type="application/x-shockwave-flash" data="../dewplayer-playlist.swf" id="dewplayerpls" name="dewplayerpls" width="240" height="200">
            <param name="movie" value="../dewplayer-playlist.swf">
            <param name="flashvars" value="xml=playlist.xml">
        </object>

        <section class="parag">
            <p> 
            Je suis là pour accompagner les enfants durant leur séjour à l'hôpital. Qu'ils cherchent des informations sur la santé, des cours, ou juste des jeux, je suis là pour les aider!
            </p>
            <p> 
            Parfois, je passerai en chair et en os - ou plutôt en plastique et en moteurs - dans leur chambre. Le reste du temps, ils pourront me retrouver dans l'<strong>Univers Cherry!</strong>
            </p>

            <div id="vidplayer">Loading the player...</div>
            <script type="text/javascript">
                var playerInstance = jwplayer("vidplayer");
                playerInstance.setup({
                file: "cherry.mp4",
                /*image: "home.PNG",*/
                width: 640,
                height: 360
                });
            </script>


            <p> 
            L'<strong>Univers Cherry</strong> est là pour permettre aux enfants de rester en contact avec l'exterieur et de s'amuser tout en apprenant.
            </p>
            <p> 
            Chaque personne concernée par l'hospitalisation de l'enfant a accès à un espace de l'Univers Cherry. Ainsi, parents, équipe soignante et enseignants ont la possibilité d'adapter le contenu de l'Univers Cherry à l'enfant.
            </p>
            <p> 
            L'enfant, lui, a accès à une "ile" d'où il poura accéder à tous les contenus à sa disposition.
            </p>
            <p> 
            En arrivant sur l'ile, il découvre cinq batiments : <strong>l'hôpital, l'école, le café, la bibliothèque et le terrain de jeux.</strong> Il peut se déplacer entre batiments en cliquant dessus. Son petit bonhomme s'y rendra, enmenant Cherry avec lui.
            </p>
            <p> 
            L'enfant peut changer l'apparence de ce bonhomme comme bon lui semble en cliquant sur son <strong>profil</strong>, en haut de la page.
            </p>
            <p> 
            Dans l'<strong>hôpital</strong> se trouve des informations sur la santé, le traitement de l'enfant, et le fonctionnement de l'hôpital. Lorsque l'enfant a vu ou lu le contenu mis à disposition par les médecins, un petit quizz lui est proposé, afin de s'assurer que tout est bien compris.
            </p>
            <p> 
            Dans l'<strong>école</strong>, l'enfant poura accéder à du contenu pédagogique, sous forme de texte, de vidéos, d'exercices, etc. et aux messages et documents que lui adressera son enseignant.
            </p>
            <p> 
            Le <strong>café</strong> est le lieu de rencontre et d'échange de l'enfant. Il pourra y recevoir et envoyer son courrier (y compris par messages sonores si il ne maitrise pas l'écrit) ou discuter en temps réel via le chat avec sa famille ou ses amis. (Les parents peuvent contrôler l'accès de leur enfant à certaines fonctionalités - voir l'espace parent)
            </p>
            <p> 
            A la <strong>bibliothèque</strong> se trouvent les documents de toutes sortes que l'enfant a choisis : livres électroniques, vidéos, musiques... Il peut également accéder à la bibliothèque de l'Univers Cherry pour en trouver de nouveaux.
            </p>
            <p> 
            Enfin, <strong>au terrain de jeu</strong>, l'enfant poura se divertir grâce à toutes sortes de jeux sur ordinateur : coloriages, construction, jeux pédagogiques...
            </p>
        </section>
        
        </br> 
        </br> 
        </br> 
        </br> 

        <section class="parag2">  
            <p > 
            Que veux tu faire maintenant?
            </p>
        </section>

        <div class="row">
            <div class="col-xs-6">
                <a href="../index.html"> <img src="home.png" class="imagecarte" alt="Image carte" />
                    <section class="parag2"> 
                        <p>Visite guidée de Cherry</p>
                    </section>
                </a>
            </div>
            <div class="col-xs-6">
                <a href="../index.html"> <img src="home.png" class="imagecarte" alt="Image carte" />
                    <section class="parag2"> 
                        <p> Retour à l'accueil</p>
                    </section>
                </a>
            </div>
        </div>
 
    <?php include("../Affichage/footer-distributed.php"); ?>
		
    </body>
</html>