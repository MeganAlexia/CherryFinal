//Mise en place des points d'exclamation
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

$("#perso_image").animateSprite({
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
        alert("animation End");
    }
});


function deplacement_total(destination){//destination=idBuilding
    //On modifie le div de destination
    $("#dest").removeClass().addClass(destination);
    //marche 100px vers le bas
    $("#perso_image").animateSprite('play', 'walkDown');
    $("#personnage").css({
            "MozTransition"    : '1s linear 0.5s',
            "transition"       : '1s linear 0.5s',
            "top":"+=50"
        });
    $("#personnage").one('webkitTransitionEnd transitionend', function(e) { //fin 100px
        $("#perso_image").animateSprite('play', 'lookDown');
        $("#mongolfiere").css({"display":"inline-block"});
        $("#perso_image").css({"display":"none"});
        //decollage
        /*$("#perso_image").css({
            "MozTransition": "2s linear 0.5s",
            "transition": "2s linear 0.5s", 
            "transform": "scale(1.2,1.2)"
        });
        $("#perso_image").one('webkitTransitionEnd transitionend', function(e) { //fin décollage*/
            deplacement_principal(destination);        
        //});//fin décollage
    });//fin 100px
}//fin fonction deplacement_total

function deplacement_principal(destination){
    //changer le temps nécessaire à l'animation selon la distance aé parcourir
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
        $("#personnage").one('webkitTransitionEnd transitionend', function(e) {
        //atterrissage
        /*$("#perso_image").css({
            "MozTransition": "2s linear 0.5s",
            "transition": "2s linear 0.5s",
            "transform": "scale(1,1)"
        });
        $("#perso_image").one('webkitTransitionEnd transitionend', function(e) { //fin aterrissage*/
            deplacement_final(destination);
        //});//fin atterrissage
    });
    });//fin deplacement principal
}

function deplacement_final(destination){
    $("#perso_image").css({"display":"inline-block"});
    $("#perso_image").animateSprite('play', 'walkUp');
    $("#mongolfiere").css({"display":"none"});
    $("#personnage").css({
        "MozTransition": "2s linear 0.5s",
        "transition": "2s linear 0.5s", 
        "top":"-=50"
    });
    $("#personnage").one('webkitTransitionEnd transitionend', function(e) {
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


//Fonction pour animation
$(function () {
    var idBuilding;
    //Losqu'on clique sur un batiment, on récupère son id, qui correspond à une classe de personnage
    //Le personnage se déplacera vers la position de cette nouvelle classe selon l'animation CSS
    $(".batiment").on("click", function () {
        idBuilding = $(this).attr("id");

        //Si le perso n'est pas deja sur l'ile correspondante, on le fait voler
        if(!($("#personnage").hasClass(idBuilding))){
            deplacement_total(idBuilding);
        }
        else{
            deplacement_final(idBuilding);
        }
    });
});


