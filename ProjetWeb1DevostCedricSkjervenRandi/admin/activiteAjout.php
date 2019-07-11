<?php
//Appel aux pages php requises pour le bon fonctionnement de la page
require_once("../inc/sessionUtilisateur.php");
require_once("../inc/connectDB.php");
require_once("../inc/sql.php");

//affiche le nom de l'admin connecté
$user = afficherUser($conn, $_SESSION['administrateur']);

// test retour de saisie du formulaire
// -----------------------------------        
if (isset($_POST['Ajouter'])) {
    $erreurs = array();
    
    // contrôles des champs saisis
    // ---------------------------
    $activite_nom = trim($_POST['activite_nom']);
    $activite_max_inscription = trim($_POST['activite_max_inscription']);

    // insertion dans la table base de donnée si aucune erreur
    // ----------------------------------------------- 
    $ajout = array($activite_nom, $activite_max_inscription);
    
    if (count($erreurs) === 0) {
        if (sqlAjouter($conn, $ajout, "activite", 0) === 1) {
            $retSQL="Ajout effectuée. Merci";  
             
        } else {
            $retSQL ="Ajout non effectuée.";    
        }
    }
}  
?>
<!--
*   Auteurs: Cédric Devost et Randi Skjerven
*   Date: 2019-01-26
*   Description: Page php pour ajouter une activité à la liste de toutes les activités offerte
*   Fichier: activiteAjout.php
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
    <title>Ajout Activité</title>
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
                <p>Connecté sous:
                    <?php echo $user; ?>
                </p>
                <p>(<a href="../connection/deconnexion.php">Déconnexion</a>)</p>
            </div>
            
            <!-- Gestion des menus selon le format de la page -->
            <ul id="gros">
                <li><a id="active" href="listeActivite.php?type=Actif" title="Nos Activités">Gestion Activités</a></li>
                <li><span class="fa fa-star checked"></span></li>
                <li><a href="listeUsagers.php?type=administrateur" title="Notre Équipe">Gestion Membres</a></li>
            </ul>
            <ul id="petit">
                <li><a id="active" href="listeActivite.php" title="Nos Activités">Gestion Activités</a></li>
                <li><span class="fa fa-star checked"></span></li>
                <li><a href="listeUsagers.php?type=administrateur" title="Notre Équipe">Gestion Membres</a></li>
            </ul>
        </nav>
    </header>

    <!-- Contenu du site -->
    <main id="mainModifsAdmin">
        <h2>Ajout d'une Activité</h2>
        <p>
            
            <!-- Apparition du message en fonction de ce qui s'est passé en haut de la page -->
            <?php echo isset($retSQL) ? $retSQL : "&nbsp;" ?>
        </p>
        <form action="activiteAjout.php" method="post">
            <table>
                <tr>
                    <td>Nom du cours : </td>
                    <td>
                        <input type="text" name="activite_nom" required>
                    </td>
                </tr>
                <tr>
                    <td>Nombre Maximal de participants</td>
                    <td>
                        <input type="number" name="activite_max_inscription" required>
                    </td>
                </tr>

            </table>
            <input type="submit" name="Ajouter" value="Ajouter">
        </form>
        <br />
        <a href="listeActivite.php?type=Actif">Retour</a>
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
