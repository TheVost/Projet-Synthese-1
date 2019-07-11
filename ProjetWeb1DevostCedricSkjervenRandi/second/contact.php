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
*   Description: Page php qui liste les méthode de contacter le gym
*   Fichier: contact.php
-->
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta name="description" content="Contact, Gym du Quartier, gym, forme physique, santé">
    <meta name="author" content="Cédric Devost, Randi Skjerven">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" type="text/css" href="../stylesheets/stylesSecond.css">

    <!-- Balise pour les fonts google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Balthazar" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Allerta+Stencil" rel="stylesheet">

    <!-- Balise pour les étoiles dans la barre de navigation -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <title>Contact</title>
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
                <li><a href="activites.php" title="Nos Activités">Activités</a></li>
                <li><span class="fa fa-star checked"></span></li>
                <li><a href="equipe.php" title="Notre Équipe">Notre Équipe</a></li>
                <li><span class="fa fa-star checked"></span></li>
                <li><a id="active" href="contact.php" title="Nous joindre">Nous Joindre</a></li>
            </ul>
            <ul id="petit">
                <li><a href="../index.php" title="Nos Activités">Accueil</a></li>
                <li><span class="fa fa-star checked"></span></li>
                <li><a href="activites.php" title="Nos Activités">Activités</a></li>
                <li><span class="fa fa-star checked"></span></li>
                <li><a href="equipe.php" title="Notre Équipe">Notre Équipe</a></li>
                <li><span class="fa fa-star checked"></span></li>
                <li><a id="active" href="contact.php" title="Nous joindre">Nous Joindre</a></li>
            </ul>
        </nav>
    </header>

    <!-- Contenu du site -->
    <main id="contact">
        <div>
            <h1>Coordonnées</h1>
            <article>
                <p><span>5115 rue Sherbrooke</span></p>
                <p>Montréal, Qc</p>
                <p>Téléphone (Montréal) : <span>514-559-0562</span></p>
                <p>Téléphone (Extérieur) : <span>1-888-559-0562</span></p>
                <p>À deux pas du Métro Assomption</p>
                <p>Nous joindre par courriel:</p>
                <p><span>info@gymduquartier.ca</span></p>
            </article>
        </div>
        <aside>
            
            <!-- carte google map -->
            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2793.089844608098!2d-73.55463708422721!3d45.568631834489636!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x4cc91c1776e344a1%3A0x3d36c221f24f4307!2s5115+Rue+Sherbrooke+E%2C+Montr%C3%A9al%2C+QC+H1T+3X3!5e0!3m2!1sfr!2sca!4v1548876032560" width="600" height="450" frameborder="0" style="border:0" allowfullscreen></iframe>
        </aside>
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
