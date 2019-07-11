<?php 
//Appel aux pages php requises pour le bon fonctionnement de la page
require_once("../inc/connectDB.php");
require_once("../inc/sql.php");
session_start();

//Détermine si un entraineur ou un client est connecté
if(isset($_SESSION['entraineur'])){
    $user = afficherUser($conn, $_SESSION['entraineur']);
}
if(isset($_SESSION['client'])){
    $user = afficherUser($conn, $_SESSION['client']);
}
?>


<!DOCTYPE HTML>
<!--
*   Auteurs: Cédric Devost et Randi Skjerven
*   Date: 2019-01-29
*   Description: Page php qui liste les activités offertes
*   Fichier: activites.php
-->
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta name="description" content="Activités, Gym du Quartier, gym, forme physique, santé">
    <meta name="author" content="Cédric Devost, Randi Skjerven">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" type="text/css" href="../stylesheets/stylesSecond.css">

    <!-- Balise pour les fonts google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Balthazar" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Allerta+Stencil" rel="stylesheet">

    <!-- Balise pour les étoiles dans la barre de navigation -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <title>Nos Activités</title>
</head>

<body>
    <header>

        <!-- image qui sera différente selon la taille de l'écran -->
        <img id="pc" src="../images/GymQuartier.png" alt="Gym du Quartier">
        <img id="mobile" src="../images/GymQuartierPetit.png" alt="Gym du Quartier">

        <!-- Navigation -->
        <nav>
            <div>
                <!-- Inscrit le nom de l'usager connecté, s'il y a lieu, et remplace le menu dans ce cas -->
                <?php if((isset($_SESSION['entraineur'])) || (isset($_SESSION['client']))) : ?>

                <p>Connecté sous:
                    <?php echo $user; ?>
                </p>
                <p>(<a href="../connection/deconnexion.php">Déconnexion</a>)</p>

                <?php else : ?>

                <a href="../connection/connexion.php" title="Connexion">Connexion</a> / <a href="../connection/inscription.php" title="Inscription">Inscription</a>

                <?php endif; ?>
            </div>

            <!-- Gestion du menu pour qu'il soit responsive -->
            <ul id="gros">
                <li><a href="../index.php" title="Nos Activités">Accueil</a></li>
                <li><span class="fa fa-star checked"></span></li>
                <li><a id="active" href="activites.php" title="Nos Activités">Activités</a></li>
                <li><span class="fa fa-star checked"></span></li>
                <li><a href="equipe.php" title="Notre Équipe">Notre Équipe</a></li>
                <li><span class="fa fa-star checked"></span></li>
                <li><a href="contact.php" title="Nous joindre">Nous Joindre</a></li>
            </ul>
            <ul id="petit">
                <li><a href="../index.php" title="Nos Activités">Accueil</a></li>
                <li><span class="fa fa-star checked"></span></li>
                <li><a id="active" href="activites.php" title="Nos Activités">Activités</a></li>
                <li><span class="fa fa-star checked"></span></li>
                <li><a href="equipe.php" title="Notre Équipe">Notre Équipe</a></li>
                <li><span class="fa fa-star checked"></span></li>
                <li><a href="contact.php" title="Nous joindre">Nous Joindre</a></li>
            </ul>
        </nav>
    </header>

    <!-- Contenu du site pour pc-->
    <main id="mainIndex">
        <h1>Nos activités</h1>
        <article>

            <!-- Utile avec le CSS pour faire des images qui flip -->
            <div class="flip-container" ontouchstart="this.classList.toggle('hover');">
                <div class="flipper">
                    <div class="front">
                        <h2>Natation</h2>
                        <img src="../images/natation.jpg" title="Natation">
                    </div>
                    <div class="back">
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean ultricies nisi at lacus suscipit ultricies. Quisque dignissim lacus velit, id aliquam sem condimentum sit amet. Cras condimentum mollis porttitor. Sed et nisl non nibh sagittis interdum. </p>
                    </div>
                </div>
            </div>
            <div class="flip-container" ontouchstart="this.classList.toggle('hover');">
                <div class="flipper">
                    <div class="front">
                        <h2>Yoga</h2>
                        <img src="../images/yoga.jpg" title="Yoga">
                    </div>
                    <div class="back">
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean ultricies nisi at lacus suscipit ultricies. Quisque dignissim lacus velit, id aliquam sem condimentum sit amet. Cras condimentum mollis porttitor. Sed et nisl non nibh sagittis interdum. </p>
                    </div>
                </div>
            </div>
        </article>
        <article>
            <div class="flip-container" ontouchstart="this.classList.toggle('hover');">
                <div class="flipper">
                    <div class="front">
                        <h2>Haltérophilie</h2>
                        <img src="../images/haltero.jpg" title="Haltérophilie">
                    </div>
                    <div class="back">
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean ultricies nisi at lacus suscipit ultricies. Quisque dignissim lacus velit, id aliquam sem condimentum sit amet. Cras condimentum mollis porttitor. Sed et nisl non nibh sagittis interdum. </p>
                    </div>
                </div>
            </div>
            <div class="flip-container" ontouchstart="this.classList.toggle('hover');">
                <div class="flipper">
                    <div class="front">
                        <h2>Boot Camp</h2>
                        <img src="../images/boot.jpg" title="Boot Camp">
                    </div>
                    <div class="back">
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean ultricies nisi at lacus suscipit ultricies. Quisque dignissim lacus velit, id aliquam sem condimentum sit amet. Cras condimentum mollis porttitor. Sed et nisl non nibh sagittis interdum. </p>
                    </div>
                </div>
            </div>
        </article>
        <article>
            <div class="flip-container" ontouchstart="this.classList.toggle('hover');">
                <div class="flipper">
                    <div class="front">
                        <h2>Pilates</h2>
                        <img src="../images/pilates.jpg" title="Pilates">
                    </div>
                    <div class="back">
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean ultricies nisi at lacus suscipit ultricies. Quisque dignissim lacus velit, id aliquam sem condimentum sit amet. Cras condimentum mollis porttitor. Sed et nisl non nibh sagittis interdum. </p>
                    </div>
                </div>
            </div>
        </article>
    </main>

    <!-- Contenu du site pour mobile-->
    <main id="mainPortable">
        <h1>Nos activités</h1>
        <article>
            <div class="flip-container" ontouchstart="this.classList.toggle('hover');">
                <div class="flipper">
                    <div class="front">
                        <h2>Natation</h2>
                        <img src="../images/natation.jpg" title="Natation">
                    </div>
                    <div class="back">
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean ultricies nisi at lacus suscipit ultricies. Quisque dignissim lacus velit, id aliquam sem condimentum sit amet. Cras condimentum mollis porttitor. Sed et nisl non nibh sagittis interdum. </p>
                    </div>
                </div>
            </div>
            <div class="flip-container" ontouchstart="this.classList.toggle('hover');">
                <div class="flipper">
                    <div class="front">
                        <h2>Yoga</h2>
                        <img src="../images/yoga.jpg" title="Yoga">
                    </div>
                    <div class="back">
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean ultricies nisi at lacus suscipit ultricies. Quisque dignissim lacus velit, id aliquam sem condimentum sit amet. Cras condimentum mollis porttitor. Sed et nisl non nibh sagittis interdum. </p>
                    </div>
                </div>
            </div>
        </article>
        <article>
            <div class="flip-container" ontouchstart="this.classList.toggle('hover');">
                <div class="flipper">
                    <div class="front">
                        <h2>Haltérophilie</h2>
                        <img src="../images/haltero.jpg" title="Haltérophilie">
                    </div>
                    <div class="back">
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean ultricies nisi at lacus suscipit ultricies. Quisque dignissim lacus velit, id aliquam sem condimentum sit amet. Cras condimentum mollis porttitor. Sed et nisl non nibh sagittis interdum. </p>
                    </div>
                </div>
            </div>
            <div class="flip-container" ontouchstart="this.classList.toggle('hover');">
                <div class="flipper">
                    <div class="front">
                        <h2>Boot Camp</h2>
                        <img src="../images/boot.jpg" title="Boot Camp">
                    </div>
                    <div class="back">
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean ultricies nisi at lacus suscipit ultricies. Quisque dignissim lacus velit, id aliquam sem condimentum sit amet. Cras condimentum mollis porttitor. Sed et nisl non nibh sagittis interdum. </p>
                    </div>
                </div>
            </div>
        </article>
        <article>
            <div class="flip-container" ontouchstart="this.classList.toggle('hover');">
                <div class="flipper">
                    <div class="front">
                        <h2>Pilates</h2>
                        <img src="../images/pilates.jpg" title="Pilates">
                    </div>
                    <div class="back">
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean ultricies nisi at lacus suscipit ultricies. Quisque dignissim lacus velit, id aliquam sem condimentum sit amet. Cras condimentum mollis porttitor. Sed et nisl non nibh sagittis interdum. </p>
                    </div>
                </div>
            </div>
        </article>
    </main>
    <!-- Footer -->
    <footer>
        <div>
            <p>5115 rue Sherbrooke, Montréal</p>
            <p>514-555-5565</p>
            <p>info@gymduquartier.ca</p>
        </div>
    </footer>
</body>

</html>
