/**
 * @file     scripts.js
 * @author   Cédric Devost et Randi Skjerven
 * @version  1.0
 * @date     2019-01-23
 * @brief    fichiers javascripts contenant des fonctions relatives au bon fonctionnement du projet web 1
 */


/**
 * @brief    prend le nom du fichier qu'on veut uploader et remplace l'image courrante avec l'image correspondant au nom du fichier, si c'Est une image dans le dossier image du site
 * @details  cette fonction n'Est qu'Esthétique pour le moment, elle ne rattache pas les images à la base de donnée
 */
function changerImage() {

    //On vérifie si l'input type file contient quelque chose. Si oui, on procède à la suite
    var image = document.getElementById("files").value;

    if (image) {

        //On récupère le nom du fichier entré dans l'input
        var startIndex = (image.indexOf('\\') >= 0 ? image.lastIndexOf('\\') : image.lastIndexOf('/'));
        var nomImage = image.substring(startIndex);
        if (nomImage.indexOf('\\') === 0 || nomImage.indexOf('/') === 0) {
            nomImage = nomImage.substring(1);
        }

        //On récupère l'image que l'on souhaite modifier et on lui affecter la nouvelle src
        var nouvelleImage = document["pubImage"];
        nouvelleImage.src = "../images/" + nomImage;
    }
}

/**
 * @brief    regarde si les liens ont comme href # et si c'Est le cas, leur enlève l'effet d'hover
 */
window.onload = function () {
    var liens = document.getElementsByTagName('a').length;

    for (var i = 0; i < liens; i++) {
        var href = document.getElementsByTagName('a')[i].href;

        if (href.substr(href.length - 1) == "#") {
            document.getElementsByTagName('a')[i].onmouseover = function () {
                this.style.borderRight = "3px solid #080201";
                this.style.borderBottom = "3px solid #080201";
                this.style.borderLeft = "3px solid #f8f8f8";
                this.style.borderTop = "3px solid #f8f8f8";
            }
        }
    }
}
