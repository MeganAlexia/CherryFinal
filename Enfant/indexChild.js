var idleTime = 0;
var is_deplacement=false;

function timerIncrement() {//timer d'inactivité
    idleTime = idleTime + 1;
    if (idleTime > 5) { // 10 sec
        if (($("#m_suggestion").css("visibility")=="hidden")&&($("#m_visite").css("visibility")== "hidden")&&is_deplacement==false){
            toggle_m_suggestion();
            idleTime=0;
        }
    }
}

function set_sprites(){//mise en place des anims de sprites
    $("#cherry_image").animateSprite({
        fps: 3,
        animations: {
            lookDown:[0],
            walkDown: [1,2],
            lookUp: [3],
            walkUp: [4,5],
            lookRight: [6],
            walkRight: [7,8],
            lookLeft: [9],
            walkLeft: [10,11],
            nothing:[12,13]
        },
        loop: true,
        complete: function(){
            // use complete only when you set animations with 'loop: false'
        }
    });
    $("#perso_image").animateSprite({
        fps: 5,
        animations: {
            lookDown:[0],
            walkDown: [1,2],
            lookUp: [3],
            walkUp: [4,5],
            lookRight: [6],
            walkRight: [7,8],
            lookLeft: [9],
            walkLeft: [10,11],
            nothing:[12,13,14]
        },
        loop: true,
        complete: function(){
            // use complete only when you set animations with 'loop: false'
        }
    });
}
function set_exclamation (){//Mise en place des points d'exclamation
    var isnew=[1,0,0,1,0];
    var exclam = document.querySelectorAll('.exclam'),
    exclamLength = exclam.length;
    for (var i = 0 ; i < exclamLength ; i++) {
        if (isnew[i]==1){
            exclam[i].style.display="inline-block";
        }
        else{
            exclam[i].style.display="none";
        }
    }
}
function set_avatars(){//mise en place de l'avatar et du choix d'avatars & de véhicules
    if ($.cookie('avatar')){
       $('#perso_image').css("background-image", $.cookie('avatar')); 
       $('#mongolfiere').attr("src", $.cookie('vehicule'));
    }
    var tab_avatars=["image/sprites/personnage.png","image/sprites/perso_2.png"];
    for (i=0; i<tab_avatars.length;i++){
        $("#cadre_choix_perso").append("<div id ='choix_perso"+i+"' class='choix_perso'> </div>");
        $('#choix_perso'+i+'').css("background-image", "url("+tab_avatars[i]+")"); 
    }
    $("#cadre_choix_perso").append("<p>Choisis ton véhicule</p>");
    var tab_vehicules=["image/sprites/mongolfiere1.png","image/sprites/mongolf2.png", "image/sprites/soucoupe.png"];
    for (i=0; i<tab_vehicules.length;i++){
        $("#cadre_choix_perso").append("<img src="+tab_vehicules[i]+" class='choix_vehicule'/>"); 
    }
}
function set_cherry(){//placement Cherry
    var top=$("#personnage").position().top-23;
    $("#cherry").css({
        "left" : $("#personnage").position().left+'px',
        "top" : top+'px'
    });
}
function set_m_suggestion(){//mise en place des events pour fenetre de suggestion
    $("#cherry").click(function () {toggle_m_suggestion();});
    $("#m_suggestion div img.bouton_fermeture").click(function () {
        toggle_m_suggestion();
    });
    $("#m_suggestion div p.bouton1").click(function () {
        toggle_m_suggestion();
        deplacement_ile("cafe");
    });
}

function deplacement_ile(destination){//choisit le déplacement adequat
    //On empeche d'autres evenements de déranger celui-ci
    is_deplacement=true;
    $(".batiment").off("click");
    $("#cherry").off("click");
    //Si le perso n'est pas deja sur l'ile correspondante, on le fait voler
    if(!($("#personnage").hasClass(destination))){
        deplacement_total(destination);
    }
    else{//sinon il marche vers la porte
        deplacement_final(destination);
    }
}
function deplacement_total(destination){//deplacement vers le bas+principal+final
    //On modifie le div de destination
    $("#dest").removeClass().addClass(destination);
    //marche 100px vers le bas
    $("#perso_image, #cherry_image").animateSprite('play', 'walkDown');
    $("#personnage, #cherry").css({ 
            "MozTransition"    : '1s linear 0.5s',
            "transition"       : '1s linear 0.5s',
            "top":"+=50"
    });
    $("#personnage").one('webkitTransitionEnd transitionend', function(e) { //fin 50px
        $("#perso_image").animateSprite('play', 'lookDown');
        $("#mongolfiere").css({"display":"inline-block"});
        $("#perso_image, #cherry_image").css({"display":"none"});
        //decollage
        $("#personnage").css({
            "MozTransition": "2s linear 0.5s",
            "transition": "2s linear 0.5s", 
            "transform": "scale(1.4,1.4)"
        });
        $("#personnage").one('webkitTransitionEnd transitionend', function(e) { //fin décollage
            deplacement_principal(destination);        
        });//fin décollage
    });//fin 100px*/
}
function deplacement_principal(destination){//mouvement d'une ile à l'autre
    //changer le temps nécessaire à l'animation selon la distance a parcourir
    var dist=Math.sqrt(Math.pow(($("#personnage").position().top - $("#dest").position().top),2)+Math.pow(($("#personnage").position().left - $("#dest").position().left),2));
    var time=dist/100;
    //on donne sa destination au personnage 
    //$("#personnage").removeClass().addClass(destination);
    $("#personnage").css({
        "MozTransition"    : time+'s linear 0.5s',
        "transition"       : time+'s linear 0.5s',
        "left" : $("#dest").position().left+'px',
        "top" : $("#dest").position().top+'px'
    });
            
    $("#personnage").one('webkitTransitionEnd transitionend', function(e) { //fin déplacement principal
        $("#personnage").one('webkitTransitionEnd transitionend', function(e) {//2eme fin dep principal (1 pour left 1 pour top)
            //atterrissage
            $("#personnage").css({
                "MozTransition": "2s ease 0.5s",
                "transition": "2s ease 0.5s",
                "transform": "scale(1,1)"
            });
            $("#personnage").one('webkitTransitionEnd transitionend', function(e) { //fin aterrissage
                deplacement_final(destination);
            });//fin atterrissage
        });
    });//fin deplacement principal
}
function deplacement_final(destination){//entrée dans un batiment
    $("#cherry").removeAttr('style');
    $("#cherry").css({
        "left" : $("#personnage").position().left+'px',
        "top" : ($("#personnage").position().top+23)+'px'
    });
    $("#perso_image, #cherry_image").css({"display":"inline-block"});
    $("#perso_image, #cherry_image").animateSprite('play', 'walkUp');
    $("#mongolfiere").css({"display":"none"});
    $("#personnage, #cherry").css({
        "MozTransition": "2s ease 0.5s",
        "transition": "2s ease 0.5s", 
        "top":"-=50"
    });
    $("#personnage").one('webkitTransitionEnd transitionend', function(e) {
        $("#perso_image").animateSprite('play', 'lookUp');
        switch(destination) {
            case "hopital":
            window.location.href = "Hospital/indexHospital.php";
            break;
            case "cafe":
            window.location.href = "Cafe/indexCafe.php";
            break;
            case "jeux":
            window.location.href = "Games/indexGames.php";
            break;
            case "bib":
            window.location.href = "Libraries/indexLibraries.php";
            break;
            case "ecole":
            window.location.href = "School/indexSchool.php";
            break;
            default:
            window.location.href = "#";
        }
    });
}

//Fonction principale
$(function () {
    var idleInterval = setInterval(timerIncrement, 2000);//2sec
    $(this).click(function (e) {idleTime = 0;});

    set_sprites();
    set_exclamation(); 
    set_avatars();
    set_cherry();
    set_m_suggestion();

    //Losqu'on clique sur un batiment, on récupère son id
    //Le personnage se déplacera vers la position du batiment
    var idBuilding;
    $(".batiment").click( function () {//déplacement perso
        idBuilding = $(this).attr("id");
        deplacement_ile(idBuilding);
    });

    //choix apparence de l'avatar
    $(".avatar").click(function () {toggle_cadre_perso();});
    $(".choix_perso").click(function () {
        var bg=$(this).css('background-image');
        $('#perso_image').css("background-image", bg);
        $.cookie('avatar', bg, { expires: 10 });
        toggle_cadre_perso();
    });
    $(".choix_vehicule").click(function () {
        var source=$(this).attr("src");
        $('#mongolfiere').attr("src", source);
        $.cookie('vehicule', source, { expires: 10 });
        toggle_cadre_perso();
    });
    //visite
    $("#im_visite").click(function () {start_visite();});
    //autres animations
    var helico_time=Math.floor(Math.random()*10000);   
    setTimeout(helicopter, helico_time);
    
    var ovis_time=Math.floor(Math.random()*10000);
    setTimeout(ovis, ovis_time);    
    
});

function start_visite(){
    var i=0;
    $("#m_visite").find('*').removeAttr('style');
    $("#m_visite_contenu").html("Bonjour! Je peux te faire visiter l'Univers Cherry. Tu es prêt?");
    $("#m_visite div p.bouton1").html("Allons-y!");
    $("#m_visite").css("visibility", "visible");
    $("#m_visite div img.bouton_fermeture").click(function () {$("#m_visite, #m_visite div img.bouton_fermeture").css("visibility", "hidden");});
    $("#m_visite div p.bouton2").click(function () {$("#m_visite").css("visibility", "hidden");});
    $("#m_visite div p.bouton1").click(function () {
        i=i+1;
        switch(i) {
            case 1:
            $("#m_visite div").css({
                "left" : '300px',
                "top" : '70px',
                "width":"1000px"
            });
            $("#m_visite_contenu").html("En arrivant dans l'archipel, tu peux voir cinq batiments sur des îles : l'hôpital, le café, la bibliothèque, et le terrain de jeux. </br>Tu peux te déplacer entre les îles en cliquant sur le batiment que tu souhaites visiter. </br>Si un point d'exclamation flotte au dessus d'un batiment, c'est que tu as une nouvauté dedans. Un massage de tes amis par exemple!"); 
            $("#m_visite div p.bouton1").html("Super!");
            $("#m_visite div p").css({
                "margin-top" : '130px',
                "margin-left" : '130px',
                "margin-right":"190px"
            });
            $("#m_visite div p.bouton1").css({
                "margin-top" : '350px',
                "margin-left" : '400px'
            });
            $("#m_visite div p.bouton2").css("visibility", "hidden");
            $("#m_visite div img.im_cherry").css({
                "left" : '66%',
                "top" : '370px'
            });
            $("#m_visite div img.bouton_fermeture").css({
                "visibility":"visible",
                "left" : '60px',
                "top" : '50px',
                "width":"65px"
            });
            break;
            case 2:
            $("#m_visite div").css({
                "left" : '700px',
                "top" : '100px',
                "width":"700px"
            });
            $("#m_visite_contenu").html("En haut, tu tu peux voir ton protrait. </br>Clique dessus pour changer l'apparence de ton personnage dans l'Univers Cherry!"); 
            $("#m_visite div p.bouton1").html("D'accord.");
            $("#m_visite div p").css({
                "margin-top" : '120px',
                "margin-left" : '130px',
                "margin-right":"190px"
            });
            $("#m_visite div p.bouton1").css({
                "margin-top" : '240px',
                "margin-left" : '250px'
            });
            $("#m_visite div p.bouton2").css("visibility", "hidden");
            $("#m_visite div img.im_cherry").css({
                "left" : '66%',
                "top" : '370px'
            });
            $("#m_visite div img.bouton_fermeture").css({
                "visibility":"visible",
                "left" : '30px',
                "top" : '25px',
                "width":"65px"
            });
            $("#m_visite div img.im_fleche").css({
                "visibility":"visible",
                "left" : '-600px',
                "top" : '-20px',
                "width":"100px",
                "transform": 'rotate(-155deg)'
            });
            break;
            case 3:
            $("#m_visite div").css({
                "left" : '0px',
                "top" : '500px',
                "width":"700px"
            });
            $("#m_visite_contenu").html("A l'hopital, tu trouveras des informations sur la santé et des explications sur ton traitement. </br>Parfois, tu pourras faire des quizz pour vérifier que tu as bien tout compris."); 
            $("#m_visite div p.bouton1").html("D'accord.");
            $("#m_visite div p").css({
                "margin-top" : '100px',
                "margin-left" : '100px',
                "margin-right":"160px"
            });
            $("#m_visite div p.bouton1").css({
                "margin-top" : '240px',
                "margin-left" : '250px'
            });
            $("#m_visite div p.bouton2").css("visibility", "hidden");
            $("#m_visite div img.im_cherry").css({
                "left" : '66%',
                "top" : '200px'
            });
            $("#m_visite div img.bouton_fermeture").css({
                "visibility":"visible",
                "left" : '30px',
                "top" : '25px',
                "width":"65px"
            });
            $("#m_visite div img.im_fleche").css({
                "visibility":"visible",
                "left" : '800px',
                "top" : '-300px',
                "width":"100px",
                "transform": 'rotate(155deg)'
            });
            break;
            case 4:
            $("#m_visite div").css({
                "left" : '600px',
                "top" : '50px',
                "width":"700px"
            });
            $("#m_visite_contenu").html("A l'école, tu trouveras tes cours, et tout ce qu'il faut pour apprendre. </br>Ton professeur pourra y mettre des documents, des exercices, ou des vidéos pour toi."); 
            $("#m_visite div p.bouton1").html("D'accord.");
            $("#m_visite div p").css({
                "margin-top" : '90px',
                "margin-left" : '100px',
                "margin-right":"160px"
            });
            $("#m_visite div p.bouton1").css({
                "margin-top" : '240px',
                "margin-left" : '250px'
            });
            $("#m_visite div p.bouton2").css("visibility", "hidden");
            $("#m_visite div img.im_cherry").css({
                "left" : '66%',
                "top" : '370px'
            });
            $("#m_visite div img.bouton_fermeture").css({
                "visibility":"visible",
                "left" : '30px',
                "top" : '25px',
                "width":"65px"
            });
            $("#m_visite div img.im_fleche").css({
                "visibility":"visible",
                "left" : '-200px',
                "top" : '500px',
                "width":"100px",
                "transform": 'rotate(155deg)'
            });
            break;
            case 5:
            $("#m_visite div").css({
                "left" : '700px',
                "top" : '400px',
                "width":"700px"
            });
            $("#m_visite_contenu").html("Au cybercafé, tu pourras rencontrer tes amis et ta famille par un chat, ou en vidéo. </br>Tu pourras aussi envoyer et recevoir des emails."); 
            $("#m_visite div p.bouton1").html("D'accord.");
            $("#m_visite div p").css({
                "margin-top" : '90px',
                "margin-left" : '100px',
                "margin-right":"160px"
            });
            $("#m_visite div p.bouton1").css({
                "margin-top" : '240px',
                "margin-left" : '250px'
            });
            $("#m_visite div p.bouton2").css("visibility", "hidden");
            $("#m_visite div img.im_cherry").css({
                "left" : '66%',
                "top" : '270px'
            });
            $("#m_visite div img.bouton_fermeture").css({
                "visibility":"visible",
                "left" : '30px',
                "top" : '25px',
                "width":"65px"
            });
            $("#m_visite div img.im_fleche").css({
                "visibility":"visible",
                "left" : '-250px',
                "top" : '-300px',
                "width":"100px",
                "transform": 'rotate(180deg)'
            });
            break;
            case 6:
            $("#m_visite div").css({
                "left" : '100px',
                "top" : '200px',
                "width":"700px"
            });
            $("#m_visite_contenu").html("A la bibliothèque se trouvent tous tes documents: livres, vidéos, musiques... </br>Tu pourras aussi en trouver de nouveaux."); 
            $("#m_visite div p.bouton1").html("D'accord.");
            $("#m_visite div p").css({
                "margin-top" : '110px',
                "margin-left" : '100px',
                "margin-right":"160px"
            });
            $("#m_visite div p.bouton1").css({
                "margin-top" : '240px',
                "margin-left" : '250px'
            });
            $("#m_visite div p.bouton2").css("visibility", "hidden");
            $("#m_visite div img.im_cherry").css({
                "left" : '66%',
                "top" : '270px'
            });
            $("#m_visite div img.bouton_fermeture").css({
                "visibility":"visible",
                "left" : '30px',
                "top" : '25px',
                "width":"65px"
            });
            $("#m_visite div img.im_fleche").css({
                "visibility":"visible",
                "left" : '1050px',
                "top" : '200px',
                "width":"100px",
                "transform": 'rotate(90deg)'
            });
            break;
            case 7:
            $("#m_visite div").css({
                "left" : '300px',
                "top" : '400px',
                "width":"700px"
            });
            $("#m_visite_contenu").html("Enfin, tu peux aller au terrain de jeu pour t'amuser! </br>Coloriages, jeux d'adresse, jeux de réflexion... Il y en a pour tous les goûts."); 
            $("#m_visite div p.bouton1").html("D'accord.");
            $("#m_visite div p").css({
                "margin-top" : '100px',
                "margin-left" : '100px',
                "margin-right":"160px"
            });
            $("#m_visite div p.bouton1").css({
                "margin-top" : '240px',
                "margin-left" : '250px'
            });
            $("#m_visite div p.bouton2").css("visibility", "hidden");
            $("#m_visite div img.im_cherry").css({
                "left" : '66%',
                "top" : '270px'
            });
            $("#m_visite div img.bouton_fermeture").css({
                "visibility":"visible",
                "left" : '30px',
                "top" : '25px',
                "width":"65px"
            });
            $("#m_visite div img.im_fleche").css({
                "visibility":"visible",
                "left" : '900px',
                "top" : '-100px',
                "width":"100px",
                "transform": 'rotate(-90deg)'
            });
            break;
            case 8:
            $("#m_visite div").css({
                "left" : '400px',
                "top" : '120px',
                "width":"700px"
            });
            $("#m_visite_contenu").html("Voila, tu sais tout! </br>Moi, je serais dans le chariot, derrière toi. Si tu ne sais pas quoi faire, tu peux me demander en cliquant sur moi. </br> Amuse toi bien!"); 
            $("#m_visite div p.bouton1").html("Merci! Allons-y!");
            $("#m_visite div p").css({
                "margin-top" : '80px',
                "margin-left" : '95px',
                "margin-right":"155px"
            });
            $("#m_visite div p.bouton1").css({
                "margin-top" : '240px',
                "margin-left" : '200px'
            });
            $("#m_visite div p.bouton2").css("visibility", "hidden");
            $("#m_visite div img.im_cherry").css({
                "left" : '66%',
                "top" : '270px'
            });
            $("#m_visite div img.bouton_fermeture").css({
                "visibility":"visible",
                "left" : '30px',
                "top" : '25px',
                "width":"65px"
            });
            $("#m_visite div img.im_fleche").css({
                "visibility":"hidden",
            });

            break;
            default:
            i=0;
            $("#m_visite").find('*').removeAttr('style');
            $("#m_visite, #m_visite div img.bouton_fermeture, #m_visite div img.im_fleche").css("visibility", "hidden");
        } 
    });
}

function toggle_cadre_perso(){
    if($("#cadre_choix_perso").hasClass("replie")){
        $("#cadre_choix_perso").removeClass().addClass("deplie");
    }
    else{
        $("#cadre_choix_perso").removeClass().addClass("replie");
    } 
}

function toggle_m_suggestion(){
    if($("#m_suggestion").css("visibility")=="visible"){
        $("#m_suggestion").css("visibility", "hidden");
    }
    else{
        $("#m_suggestion").css("visibility", "visible");
        var t=$("#cherry").position().top-200;
        var l=$("#cherry").position().left-300;
        if (t<0){t=0;}
        if (l<0){l=0;}
        $("#m_suggestion div").css({
            "top": t+"px",
            "left": l+"px"
        });
    }   
}

function helicopter(){
    if ($("#helico").position().top==260 && $("#helico").position().left==670){
        ///////ajouter calcul vitesse
        var pos=pos_bord_aleatoire();
        var x=pos[0], y=pos[1];
        if (x<50){ ///remplacer 50 par x position de départ
            $("#helico").css({
                "MozTransition": "0s linear 0s",
                "transition": "0s linear 0s",
                "-moz-transform": "rotateY(180deg)",
                "-webkit-transform": "rotateY(180deg)",
                "transform": "rotateY(180deg)"
            });
        }
        $("#helico").css({//décollage
            "MozTransition": "2s linear 1s",
            "transition": "2s linear 1s", 
            "top":"-=50"
        });
        $("#helico").one('webkitTransitionEnd transitionend', function(e) {//fin décollage
            $("#helico").css({
                "MozTransition"    : '6s linear 0.5s',
                "transition"       : '6s linear 0.5s',
                "left" : x+'%',
                "top" : y+'%',
            });
            $("#helico").one('webkitTransitionEnd transitionend', function(e) {//fin trajet
                $("#helico").one('webkitTransitionEnd transitionend', function(e) {//fin trajet
                    $("#helico").hide();
                });
            });
        });
    }
    else if (!$("#helico").is(":visible")){//retour helico 
        $("#helico").removeAttr('style');
        var pos=pos_bord_aleatoire();
        var x=pos[0], y=pos[1];
        if (x>50){ ///remplacer 50 par x position de départ
            $("#helico").css({
                "MozTransition": "0s linear 0s",
                "transition": "0s linear 0s",
                "-moz-transform": "rotateY(180deg)",
                "-webkit-transform": "rotateY(180deg)",
                "transform": "rotateY(180deg)"
            });
        }
        $("#helico").css({
            "MozTransition"    : '0s linear 0s',
            "transition"       : '0s linear 0s',
            "left" : x+'%',
            "top" : y+'%',
        });
        $("#helico").show();   
        //vers hopital
        $("#helico").css({
            "MozTransition"    : '6s linear 0.5s',
            "transition"       : '6s linear 0.5s',
            "left" : '670px',
            "top" : '210px',
        });
        $("#helico").one('webkitTransitionEnd transitionend', function(e) {//aterrissage
            if (x>50){ ///remplacer 50 par x position de départ
                $("#helico").css({
                    "MozTransition"    : '2s linear 1s',
                    "transition"       : '2s linear 1s',
                    "top" : '+=50px',
                    "-moz-transform": "rotateY(0deg)",
                    "-webkit-transform": "rotateY(0deg)",
                    "transform": "rotateY(0deg)"
                });
            }
            else{
                $("#helico").css({
                    "MozTransition"    : '2s linear 1s',
                    "transition"       : '2s linear 1s',
                    "top" : '+=50px',
                });
            }
        }); 
    }
    var time=Math.floor(Math.random()*10000)+8000;
    setTimeout(helicopter, time);
}

function ovis(){
    $("#ovis").removeAttr('style');
    //image aléatoire
    var tab_ovis = ["image/sprites/avion.png","image/sprites/avion2.png","image/sprites/dirigable.png","image/sprites/mongolf2.png","image/sprites/oiseaux.png","image/sprites/ovni.png"];
    var i=Math.floor(Math.random()*tab_ovis.length);
    $("#ovis").attr('src',tab_ovis[i]);
    //trajectoire aléatoire
    var pos1=pos_bord_aleatoire();
    var x1=pos1[0], y1=pos1[1];
    var pos2=pos_bord_aleatoire();
    var x2=pos2[0], y2=pos2[1];
    if (x1==x2 && y1==y2){
        x2+=20;
        y2+=20;
    }
    else if ((x1==x2==-10) || (x1==x2==100)){
        x2=20;
    }
    else if ((y1==y2==-10) || (y1==y2==100)){
        y2=20;
    }
    $("#dest_ovis").css({
        "left" : x2+'%',
        "top" : y2+'%',
    }); 
    $("#ovis").css({
        "MozTransition"    : '0s linear 0s',
        "transition"       : '0s linear 0s',
        "left" : x1+'%',
        "top" : y1+'%',
    }); 
    //suivant image, rotation suivant trajectoire
    var angle=(Math.atan(($("#dest_ovis").position().top-$("#ovis").position().top)/($("#dest_ovis").position().left-$("#ovis").position().left))*180/Math.PI);
    var anglex=0;
    if (i==0|| i==1 || i==4 || i==5){
        angle=angle-90;
        if (x2>x1){angle=angle+180;}
    } 
    else if (i==2 && x2<x1){
        angle=angle+180;
        anglex=180;
    }
    else if (i==3){
        angle=0;   
    }
    $("#ovis").css({
        "MozTransition"    : '0s linear 0s',
        "transition"       : '0s linear 0s',
        "-moz-transform": 'rotate('+angle+'deg) rotateX('+anglex+'deg)',
        "-webkit-transform": 'rotate('+angle+'deg) rotateX('+anglex+'deg)',
        "transform": 'rotate('+angle+'deg) rotateX('+anglex+'deg)',
    });
    //trajectoire, videsse aléatoire
    var ovis_vitesse=Math.floor(Math.random()*5)+6;
    $("#ovis").show();  
    $("#ovis").css({
        "MozTransition"    : ovis_vitesse+'s linear 0.5s',
        "transition"       : ovis_vitesse+'s linear 0.5s',
        "left" : x2+'%',
        "top" : y2+'%',
    });

    $("#ovis").one('webkitTransitionEnd transitionend', function(e) {
        document.getElementById('ovis').style.display = 'none';
        var ovis_time=Math.floor(Math.random()*10000);
        setTimeout(ovis, ovis_time); 
    });  
}

function pos_bord_aleatoire(){//calcule une position aléatoire le long des bords de l'écran
    if(Math.floor(Math.random()*2)==1){//horizontal
        if (Math.floor(Math.random()*2)==1){//haut
            var x=Math.floor(Math.random()*100)-10;
            var y=-10; 
        }
        else{//bas
            var x=Math.floor(Math.random()*100)-10;
            var y=100;
        }
    }
    else {//vertical
        if (Math.floor(Math.random()*2)==1){//gauche
            var x=-10;
            var y=Math.floor(Math.random()*100)-10;
        }
        else {
            var x=100;
            var y=Math.floor(Math.random()*100)-10;
        }
    }
    return [x,y];
}


