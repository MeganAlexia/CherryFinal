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
        walkLeft: [10,11]
    },
    loop: true,
    complete: function(){
        // use complete only when you set animations with 'loop: false'
        alert("animation End");
    }
});
//$("#perso_image").animateSprite('stop');

//Fonction pour animation
$(function () {
    var idBuilding;
    //Losqu'on clique sur un batiment, on récupère son id, qui correspond à une classe de personnage
    //Le personnage se déplacera vers la position de cette nouvelle classe selon l'animation CSS
    $(".batiment").on("click", function () {
        idBuilding = $(this).attr("id");
        //On modifie le div de destination
        $("#dest").removeClass().addClass(idBuilding);

        //gestion animation de marche du sprite(--faire ac la distance à l'emplacement d'arrivée)
        var a=$("#dest").position().left, b=$("#dest").position().top;
        var x=$("#dest").position().left - $("#personnage").position().left;
        var y=$("#dest").position().top - $("#personnage").position().top; 
        if (y>=x && y>=(-x)){
            $("#perso_image").animateSprite('play', 'walkDown');
        }
        else if (y<=x && y<=(-x)){
            $("#perso_image").animateSprite('play', 'walkUp');
        }
        else if (y>=x && y<=(-x)){
            $("#perso_image").animateSprite('play', 'walkLeft');
        }
        else if (y<=x && y>=(-x)){
            $("#perso_image").animateSprite('play', 'walkRight');
        }
        else {
           $("#perso_image").animateSprite('play', 'walkDown'); 
        }

        //calculer temps nécessaire (a partir de la dist au batiment -- faire ac la distance à l'emplacement d'arrivée)
        var dist=Math.sqrt(Math.pow(($("#personnage").position().top - $("#dest").position().top),2)+Math.pow(($("#personnage").position().left - $("#dest").position().left),2));
        var time=dist/200;

        //changer le temps nécessaire à l'animation selon la distance
        $("#personnage").css({
            "MozTransition"    : time+'s linear 0.5s',
            "transition"       : time+'s linear 0.5s'
        });
        
        //on donne une destination au personnage 
        $("#personnage").removeClass().addClass(idBuilding);
        
        //a la fin de la transition, on passe à la page nécessaire
        $("#personnage").one('webkitTransitionEnd transitionend', function(e) { //fin déplacement principal
            //$("#perso_image").animateSprite('play', 'atterrissage');
            //$(#personnage).css({"MozTransition": "1 linear 0.2s","transition": "1 linear 0.2s", "transform": "scale(0.7,0.7)";});
            //$("#personnage").one('webkitTransitionEnd transitionend', function(e) {
                //$("#perso_image").animateSprite('play', 'walkUp');
                //$(#personnage).css({"MozTransition": "1 linear 0.2s","transition": "1 linear 0.2s", "top":"-=100";});
                //$("#personnage").one('webkitTransitionEnd transitionend', function(e) {
            switch(idBuilding) {
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
            //}}
            }
        });
    });
    
});


