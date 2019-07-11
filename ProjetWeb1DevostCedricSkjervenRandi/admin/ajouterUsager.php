<?php
//Appel aux pages php requises pour le bon fonctionnement de la page
require_once("../inc/connectDB.php");
require_once("../inc/sql.php");
session_start();

//Détermine si un entraineur ou un admin est connecté, sinon redirrige vers la page de connexion
if(isset($_SESSION['administrateur'])){
    $user = afficherUser($conn, $_SESSION['administrateur']);
}
else if(isset($_SESSION['entraineur'])){
    $user = afficherUser($conn, $_SESSION['entraineur']);
}
else{
    header('Location: ../connection/connexion.php');
}

//sort une liste des clients du gym
$liste = listerUsagers($conn, "client");

// test retour de saisie du formulaire
// -----------------------------------        
if (isset($_POST['ajouter'])) {
    //pour garder en mémoire la date et l'heure et donc l'Activité qu'on visualise
    $date = $_POST['date'];
    $heure = $_POST['heure'];  
    
    //affiche les détails du cours en fonction de la date et heure
    $details = afficherInfoCours($conn, $date, $heure);
    
    //affiche l'id du cours et le nom de l'usager
    $coursID = $details[0]['cours_id'];
    $usager = $_POST['select'];
    
    //valide si l'inscription est confrme et affiche un message en conséquence
    $inscription = inscrireAcrtivite($conn, $usager, $coursID);
    if($inscription === 1){
        $message = "Ajout confirmée!";
    }      
    else{
        $message = "Utilisateur déjà inscrit à cet activité!!";
    }
} 
else {
    //Instanciation de variables nécessaires plus loin et pour garder en mémoire l'activité qu'on visualise
    //heure et date déterminée en extractant une sous-chaine de charactère basé sur ce que j'ai envoyé dans l'url comme infos
    $heure = substr($_GET['heure'],-24, 8);
    $date = substr($_GET['heure'], -10);
    $message = "";
    $usager = "";
    $details = afficherInfoCours($conn, $date, $heure);
} 
?>
<!--
*   Auteurs: Cédric Devost et Randi Skjerven
*   Date: 2019-01-31
*   Description: Page php qui permet a l'administrateur d'ajouter un usager à une activitée donnée
*   Fichier: ajouterUsager.php
-->

<!DOCTYPE HTML>
<html lang="fr">

<head>
    <!-- Balise meta pour les moteurs de recherche -->
    <meta charset="utf-8">
    <meta name="description" content="Page Administrateur">
    <meta name="author" content="Cédric Devost, Randi Skjerven">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <!-- Balise pour le CSS-->
    <link rel="stylesheet" type="text/css" href="../stylesheets/styles.css">

    <!-- Balise pour les fonts google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Balthazar" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Allerta+Stencil" rel="stylesheet">

    <!-- Balise pour les étoiles dans la barre de navigation -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <title>Ajout Usager</title>
</head>

<body>
    <header>
        
        <!-- Image différente selon le format de la page -->
        <img id="pc" src="../images/GymQuartier.png" alt="Gym du Quartier">
        <img id="mobile" src="../images/GymQuartierPetit.png" alt="Gym du Quartier">
        <!-- Navigation -->
        <nav>
            <div>
                <!-- Inscrit le nom de l'usager administrateur connecté -->
                <p>Connecté sous: <?php echo $user; ?></p>
                <p>(<a href="../connection/deconnexion.php">Déconnexion</a>)</p>
            </div>
            
            <!-- Gestion des menus selon le format de la page et du typoe d'utilisateur connecté-->
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
            
            <!-- si c'est un entraineur, le menu n'est pas le même -->
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
        <h2>Qui voulez-vous ajouter 
            <br /> au cours de
            <span>
                <?php echo $details[0]['cours_activite']; ?>
            </span>, <br />le <span><?php echo $date; ?></span>
        </h2>
        
        <h3>à <span><?php echo $heure; ?></span> ? </h3>

        <form method="post">
            <p>
            <select name="select">
                
                <!-- affiche un selct avec tous les usagers clients -->
                <?php foreach($liste AS $usager) : ?>
                    <option value="<?php echo $usager['utilisateur_id']; ?>"><?php echo $usager['utilisateur_nom']; ?></option>
                <?php endforeach; ?>
            </select>
            </p>
            
            <!-- input hidden pour garder en mémoire la date et l'heure meêm après rafraichissement de la page à l'envoie -->
            <input type="hidden" name="date" value="<?php echo $date; ?>">
            <input type="hidden" name="heure" value="<?php echo $heure; ?>">
            
            <!-- permet l'ajout, ou affiche le message correspondant après le click -->
            <?php if($message == "") : ?>
            
                <br />
                <input type="submit" name="ajouter" value="ajouter">
            
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
