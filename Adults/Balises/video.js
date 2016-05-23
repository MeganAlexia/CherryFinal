function lecture() {
    // Lit la vidéo
    lecteur.play();
}

function pause() {
    // Met la vidéo en pause
    lecteur.pause();
    
    
}

function stop() {
    // Arrête la vidéo
    // On met en pause
    lecteur.pause();
    // Et on se remet au départ
    lecteur.currentTime = 0;
}

function avancer(duree) {
    // Avance de 'duree' secondes
    // On parse en entier pour etre sur d'avoir un nombre
    lecteur.currentTime += parseInt(duree);
}

function reculer(duree) {
    // Recule de 'duree' secondes
    // On parse en entier pour etre sur d'avoir un nombre
    lecteur.currentTime -= parseInt(duree);
}

function rafraichirTemps() {
    var secondes = parseInt(lecteur.currentTime);
    var minutes = Math.trunc(secondes / 60);
    secondes = secondes%60;
    var totsecondes = parseInt(lecteur.duration);
    var totminutes = Math.trunc(totsecondes / 60);
    totsecondes = totsecondes % 60;
    spanTemps.textContent = zeroPad(minutes, 2) + ":" + zeroPad(secondes, 2)
                            + '/' +
                            zeroPad(totminutes, 2) + ":" + zeroPad(totsecondes, 2);
}

// fonction pour afficher joliment les nombres
function zeroPad(num, places) {
    var zero = places - num.toString().length + 1;
    return Array(+(zero > 0 && zero)).join("0") + num;
}

var lecteur;
var spanTemps;

function creerBoutons() {
    // Créé les boutons de gestion du lecteur
    var btnLecture = document.createElement("button");
    var btnPause = document.createElement("button");
    var btnStop = document.createElement("button");
    var btnReculer = document.createElement("button");
    var btnAvancer = document.createElement("button");
    
    var spanGauche = document.createElement("span");
    var spanDroite = document.createElement("span");
    spanDroite.className = "droite";
    spanTemps = spanDroite;
    spanTemps.textContent = "--:-- / --:--";
    
    var controlesBox = document.getElementById("controles");
    lecteur = document.getElementById("mavideo");
    
    // Ajoute un peu de textes
    btnLecture.textContent = "▶";
    btnPause.textContent = "‖";
    btnStop.textContent = "■";
    btnReculer.textContent = "<<";
    btnAvancer.textContent = ">>";
    
    // On ajoute les boutons à l'interface
    spanGauche.appendChild(btnLecture);
    spanGauche.appendChild(btnPause);
    spanGauche.appendChild(btnStop);
    spanGauche.appendChild(btnReculer);
    spanGauche.appendChild(btnAvancer);
    controlesBox.appendChild(spanGauche);
    controlesBox.appendChild(spanDroite);
    
    // On lie les fonctions aux boutons
    btnLecture.addEventListener("click", lecture, false);
    btnPause.addEventListener("click", pause, false);
    btnStop.addEventListener("click", stop, false);
    btnReculer.addEventListener("click", function(){reculer(10)}, false);
    btnAvancer.addEventListener("click", function(){avancer(10)}, false);
    
    // On affiche les nouveaux boutons et supprime l'interface original
    controlesBox.removeAttribute("hidden");
    lecteur.removeAttribute("controls");
    
    // Pour rafraichir le temps (toutes les 200 ms)
    setInterval(rafraichirTemps, 200);
}

// Créé les boutons lorsque le DOM est chargé
document.addEventListener('DOMContentLoaded', creerBoutons, false);