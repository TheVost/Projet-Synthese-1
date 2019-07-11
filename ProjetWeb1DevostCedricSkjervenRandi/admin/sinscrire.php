<!--
*   Auteurs: Cédric Devost et Randi Skjerven
*   Date: 2019-01-21
*   Description: page de confirmation d'inscription pour le client à une activité
*   Fichier: sinscrire.php
-->

<?php 
//Appel aux pages php requises pour le bon fonctionnement de la page
require_once("../inc/connectDB.php");
require_once("../inc/sql.php");
session_start();

//Détermine si un utilisateur est connecté
if(isset($_SESSION['client'])){
    $user = afficherUser($conn, $_SESSION['client']);
}
//redirection à la page connexion si aucun utilisateur est connecté
else{
    header('Location: ../connection/connexion.php');
}

//Vérifie si on a cliqué sur modifié, si oui, on lui renvoie les paramêtre nécessaire au bon fonctionnement de la fonction plus bas
if (isset($_POST['oui'])) {
    //pour garder en mémoire la date et l'heure et donc l'Activité qu'on visualise
    $date = $_POST['date'];
    $heure = $_POST['heure'];  
    
    //trouve l'id de l'utilisateur et de la plage horraire de l'Activité pour pouvoir procéder à l'inscription
    $idUser = afficherUserId($conn, $user);
    $idHoraire = afficherHoraireId($conn, $date, $heure);
    
    //inscription
    $inscription = inscrireAcrtivite($conn, $idUser, $idHoraire); 
    if($inscription === 1){
        $message = "Inscription confirmée! À bientôt";
    }      
    else{
        $message = "Votre paiement est en attente!!";
    }
}
else if(isset($_POST['non'])){
        header('Location: ../index.php'); 
    }  
else {
    //Instanciation de variables nécessaires plus loin et pour garder en mémoire l'activité qu'on visualise
    //heure et date déterminée en extractant une sous-chaine de charactère basé sur ce que j'ai envoyé dans l'url comme infos
    $heure = substr($_GET['heure'],-18, 2);
    $heure = $heure . ":00:00";
    $date = substr($_GET['heure'], -10);
    $message = "";
    $details = afficherInfoCours($conn, $date, $heure);
}

//sors les détails du cours et des usagers inscrits
$details = afficherInfoCours($conn, $date, $heure);
$users = listerInscrits($conn, $date, $heure);
?>

<!DOCTYPE HTML>

<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta name="description" content="Page Administrateur">
    <meta name="author" content="Cédric Devost, Randi Skjerven">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" type="text/css" href="../stylesheets/styles.css">

    <!-- Balise pour les fonts google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Balthazar" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Allerta+Stencil" rel="stylesheet">

    <!-- Balise pour les étoiles dans la barre de navigation -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <title>Administrateur</title>
</head>

<body>
    <header>
        <img id="pc" src="../images/GymQuartier.png" alt="Gym du Quartier">
        <img id="mobile" src="../images/GymQuartierPetit.png" alt="Gym du Quartier">

        <!-- Navigation -->
        <nav>
            <div>
                <!-- Inscrit le nom de l'usager connecté -->
                <p>Connecté sous:
                    <?php echo $user; ?>
                </p>
                <p>(<a href="../connection/deconnexion.php">Déconnexion</a>)</p>
            </div>

            <!-- Gestion du menu en fonction de la taille d'écran -->
            <ul id="gros">
                <li><a href="listeActivite.php?type=Actif" title="Nos Activités">Gestion Activités</a></li>
                <li><span class="fa fa-star checked"></span></li>
                <li><a id="active" href="listeUsagers.php?type=administrateur" title="Notre Équipe">Gestion Membres</a></li>
            </ul>
            <ul id="petit">
                <li><a href="listeActivite.php" title="Nos Activités">Gestion Activités</a></li>
                <li><span class="fa fa-star checked"></span></li>
                <li><a id="active" href="listeUsagers.php?type=administrateur" title="Notre Équipe">Gestion Membres</a></li>
            </ul>
        </nav>
    </header>

    <!-- Pour le référencement de google -->
    <h1>Modifications des inscriptions</h1>

    <!-- Contenu du site -->
    <main id="mainModifsAdmin">

        <!-- Met à jour les information du cours -->
        <h2>Voulez-vous vous inscrire au cours de <span>
                <?php echo $details[0]['cours_activite']; ?></span>, <br />le <span>
                <?php echo $date; ?></span></h2>
        <h3>avec <span>
                <?php echo $details[0]['cours_nom']; ?></span> ? <br />(débute à
            <?php echo $heure; ?>,
            <?php echo substr($details[0]['cours_duree'], -7, 1); ?>h par scéance)</h3>

        <form method="post">

            <!-- input hidden pour garder en mémoire la date et l'heure meêm après rafraichissement de la page à l'envoie -->
            <input type="hidden" name="date" value="<?php echo $date; ?>">
            <input type="hidden" name="heure" value="<?php echo $heure; ?>">

            <?php if($message == "") : ?>
            <button name="oui" type="submit">OUI</button> <button name="non" type="submit">NON</button>
            <?php else :
            echo $message;
            
            endif;
            ?>
        </form>
        <br />

        <a href="../index.php">Retour</a>

    </main>

    <!-- Footer -->
    <footer>
        <div>
            <p>Soutient Technique: 514-559-0562</p>
            <p id="foot">soutientechnique@goDaddy.com</p>
        </div>
    </footer>
</body>

</html>
