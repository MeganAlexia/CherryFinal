///FONCTION DE BASE
(function() {

	//On prend toutes les images
    categ = document.querySelectorAll('.image_def'),
    categLength = categ.length;

    for (var i = 0 ; i < categLength ; i++) {
    	//Pour chaque image on écoute le clic
    	categ[i].addEventListener('click', function(e) {
    		//La node parente est la categorie
    		var cat=e.target.parentNode;

    		//On change les classes : affichage par defaut -> affichage avec sidebar et contenu
    		if (document.getElementById('contaffiche_def')){
    			document.getElementById('contaffiche_def').id="contaffiche_sidebar";
    		}
    		if (document.getElementById('encadre_def')){
    			document.getElementById('encadre_def').id="encadre_sidebar";
    		}
    		var cattab = document.querySelectorAll('.categorie_def'),
    		cattabLength = cattab.length;
    		for (var i = 0 ; i < cattabLength ; i++) {
    			cattab[i].className="categorie_sidebar";
    		}
    		var im = document.querySelectorAll('.image_def'),
    		imLength = im.length;
    		for (var i = 0 ; i < imLength ; i++) {
    			im[i].className="image_sidebar";
    		}
    		var titre = document.querySelectorAll('.titre_def'),
    		titreLength = titre.length;
    		for (var i = 0 ; i < titreLength ; i++) {
    			titre[i].className="titre_sidebar";
    		}
    		//On change la classe de la catégorie sélectionnée précédente
    		tab = document.querySelectorAll('.categorie_select'),
    		tabLength = tab.length;
    		for (var i = 0 ; i < tabLength ; i++) {
    			tab[i].className="categorie_sidebar";
    		}
    		//Changement de classe pour la classe sélectionnée
    		cat.className="categorie_select";
    		//Affichage du contenu nécessaire
    		var tit = cat.getElementsByClassName('titre_sidebar')[0].innerHTML;
    		var cont = cat.getElementsByClassName('contenucateg')[0].innerHTML;
    		document.getElementsByClassName('titreaffiche')[0].innerHTML = tit;
    		document.getElementsByClassName('contenuaffiche')[0].innerHTML = cont;
    	}, false);
    }
})();   
