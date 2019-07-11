<!--
*   Auteurs: Cédric Devost et Randi Skjerven
*   Date: 2019-01-21
*   Description: affichage de tous les activités offertes par l’établissement avec la possibilité d’initier une modification 
*   Fichier: listeActivite.php
-->


<?php 
//Appel aux pages php requises pour le bon fonctionnement de la page
require_once("../inc/connectDB.php");
require_once("../inc/sql.php");
session_start();

//Détermine si un entraineur ou un admin est connecté, sinon redirrige vers l'index
if(isset($_SESSION['administrateur'])){
    $user = afficherUser($conn, $_SESSION['administrateur']);
    $leType = listerType($conn, "activite");
    
    // test retour de saisie du formulaire
    // ----------------------------------- 
    if(isset($_POST['select'])){
        $typeDebut = $_POST['select'];
    }
    else{
        $typeDebut = isset($_GET['type']) ? $_GET['type'] : "";
    } 
//redirection à la page connexion si aucun administrateur est connecté
}else{
    header('Location: ../connection/connexion.php');
}

$liste = afficherActivites($conn, $typeDebut);
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

            <!-- Gestion du menu selon le format de l'écran -->
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

    <!-- Pour le référencement de google -->
    <h1>Modifications des inscriptions</h1>

    <!-- Contenu du site -->
    <main id="mainModifsAdmin">

        <!-- Met à jour les information du cours -->
        <h2>Liste des activités classé par <span>
                <?php echo $typeDebut; ?></span></h2>

        <!-- Permet d'afficher les infos par le status des cours -->
        <form method="post">
            <select name="select" onchange="submit()">
                <option>Choisir une catégorie</option>
                <?php foreach($leType as $type) : ?>
                <option>
                    <?php echo $type['type']; ?>
                </option>
                <?php endforeach; ?>
                <input type="hidden" name="type" value="<?php echo $typeDebut; ?>">
            </select>
        </form>
        <table>
            <tr>
                <th>Activité</th>
                <th></th>
            </tr>
            <!-- affichage de toutes les activités par le status sélectionnés -->
            <?php foreach($liste AS $activite) : ?>
            <tr>
                <td>
                    <?php echo $activite['activite_nom']; ?>
                </td>
                <td><a href="activiteModification.php?id=<?php echo $activite['activite_id']; ?>">Modifier</a></td>
            </tr>
            <?php endforeach; ?>

            <tr>
                <td><a href="activiteAjout.php">Ajouter</a></td>
                <td></td>
            </tr>
        </table>

        <a href="admin.php">Retour</a>
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
