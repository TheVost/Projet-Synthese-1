<!--
*   Auteurs: Cédric Devost et Randi Skjerven
*   Date: 2019-01-31
*   Description : Page php qui permet à l'administrateur ou à l’entraineur de supprimer un client inscrit à un cours sélectionner avec une confirmation 
*   Fichier: supprimerUsager.php
-->


<?php
require_once("../inc/connectDB.php");
require_once("../inc/sql.php");
session_start();

//Détermine si un entraineur ou un admin est connecté, sinon redirrige vers l'index
if(isset($_SESSION['administrateur'])){
    $user = afficherUser($conn, $_SESSION['administrateur']);
}
else if(isset($_SESSION['entraineur'])){
    $user = afficherUser($conn, $_SESSION['entraineur']);
}
else{
    header('Location: ../connection/connexion.php');
}

// test retour de saisie du formulaire : confirmation de suppression 
// ------------------------------------------------------------------        
if (isset($_POST['supprimer'])) {
    //pour garder en mémoire la date et l'heure et donc l'Activité qu'on visualise
    $date = $_POST['date'];
    $heure = $_POST['heure'];  
    $id = $_POST['id'];  
    
    //pour extraire les informations nécessaire d’un cour selon la plage horaire
    $details = afficherInfoCours($conn, $date, $heure);
    
    //pour garder en mémoire le ID du cours
    $coursID = $details[0]['cours_id'];
    
    //pour extraire le nom complet d'un client selon son ID
    $nom = trouverNom($conn, $id);
    echo $coursID;
    echo "<br>";
    echo $id;
    
    //requête de suppression
    $suppression = sqlSupprimer($conn, "utilisateur", $id, $coursID);
    if($suppression === 1){
        $message = "Supression effectuée!";
    }      
    else{
        $message = "Une erreur s'est produite...";
    }
}
// test retour de saisie du formulaire : refus de suppression 
// ----------------------------------------------------------  
else if(isset($_POST['non'])){
        header('Location: ../index.php'); 
    }  
else {
    //Instanciation de variables nécessaires plus loin et pour garder en mémoire l'activité qu'on visualise
    //heure et date déterminée en extractant une sous-chaine de charactère basé sur ce que j'ai envoyé dans l'url comme infos
    $heure = substr($_GET['id'],-24, 8);
    $date = substr($_GET['id'], -10);
    $id = substr($_GET['id'], 0, -31);

    $message = "";
    $usager = "";
    $details = afficherInfoCours($conn, $date, $heure);
    $nom = trouverNom($conn, $id);
} 
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
                <!-- Inscrit le nom de l'usager administrateur connecté -->
                <p>Connecté sous:
                    <?php echo $user; ?>
                </p>
                <p>(<a href="../connection/deconnexion.php">Déconnexion</a>)</p>
            </div>

            <?php if(isset($_SESSION['administrateur'])) : ?>

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

            <?php else :?>

            <ul id="gros">
                <li><a href="index.php" title="Nos Activités">Accueil</a></li>
                <li><span class="fa fa-star checked"></span></li>
                <li><a href="second/activites.php" title="Nos Activités">Activités</a></li>
                <li><span class="fa fa-star checked"></span></li>
                <li><a href="second/equipe.php" title="Notre Équipe">Notre Équipe</a></li>
                <li><span class="fa fa-star checked"></span></li>
                <li><a href="second/contact.php" title="Nous joindre">Nous Joindre</a></li>
            </ul>
            <ul id="petit">
                <li><a href="index.php" title="Nos Activités">Accueil</a></li>
                <li><span class="fa fa-star checked"></span></li>
                <li><a href="second/activites.php" title="Nos Activités">Activités</a></li>
                <li><span class="fa fa-star checked"></span></li>
                <li><a href="second/equipe.php" title="Notre Équipe">Notre Équipe</a></li>
                <li><span class="fa fa-star checked"></span></li>
                <li><a href="second/contact.php" title="Nous joindre">Nous Joindre</a></li>
            </ul>

            <?php endif; ?>

        </nav>
    </header>

    <!-- Pour le référencement de google -->
    <h1>Ajout d'un utilisateur</h1>

    <!-- Contenu du site -->
    <main id="mainModifsAdmin">
        <!-- Met à jour les information du cours -->
        <h2>Souhaitez-vous supprimer <br />
            <span>
                <?php echo $nom; ?></span>
            <br />
            du cours <span>
                <?php echo $details[0]['cours_activite']; ?></span>, <br />le <span>
                <?php echo $date; ?></span></h2>
        <h3>à <span>
                <?php echo $heure; ?></span> ? </h3>

        <form method="post">
            <!-- input hidden pour garder en mémoire la date et l'heure meêm après rafraichissement de la page à l'envoie -->
            <input type="hidden" name="date" value="<?php echo $date; ?>">
            <input type="hidden" name="heure" value="<?php echo $heure; ?>">
            <input type="hidden" name="id" value="<?php echo $id; ?>">

            <?php if($message == "") : ?>
            <br />
            <input type="submit" name="supprimer" value="Supprimer">
            <?php else :
            echo $message;
            
            endif;
            ?>
        </form>
        <br />
        <a href="inscriptionsDetails.php?heure=<?php echo substr($heure, -8, 2); ?>?date=<?php echo $date; ?>">Retour</a>
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
