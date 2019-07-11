<!--
*   Auteurs: Cédric Devost et Randi Skjerven
*   Date: 2019-01-21
*   Description: affiche les clients inscrits à la plage horaire sélectionnée avec la possibilité d’ajouter ou supprimer, permet aux administrateur de gérer les paiement des clients inscrit
*   Fichier: inscriptionsDetails.php
-->


<?php 
//Appel aux pages php requises pour le bon fonctionnement de la page
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
//redirection à la page connexion si aucun administrateur/entraineur n'est connecté
else{
    header('Location: ../connection/connexion.php');
}

//Vérifie si on a cliqué sur modifié, si oui, on lui renvoie les paramêtre nécessaire au bon fonctionnement de la fonction plus bas
if (isset($_POST['modifier'])) {
    //pour garder en mémoire la date et l'heure et donc l'Activité qu'on visualise
    $date = $_POST['date'];
    $heure = $_POST['heure']; 
    $erreurs = array();
    $modifOk = 0; 
    
    // test retour de saisie du formulaire
    // ----------------------------------- 
    if(isset($_POST['utilisateur_id'])){
        for ($i=0; $i < count($_POST['utilisateur_id']); $i++ ) {   
            $utilisateur_id = $_POST['utilisateur_id'][$i];
            $horaire_id = $_POST['horaire_id'][$i];
            $utilisateur_horaires_accepte = $_POST['utilisateur_horaires_accepte'][$i];

            $modification = array($utilisateur_id, $horaire_id, $utilisateur_horaires_accepte);

            if (count($erreurs) === 0) {
                if (sqlModification($conn, $modification, "inscription", 0) === 1) {    
                    $modifOk++;   
                    $retSQL="Modification effectuée. Merci";  

                } else {
                $retSQL ="Modification non effectuée.";    
                }
            }
        }
    }
}
else {
    //Instanciation de variables nécessaires plus loin et pour garder en mémoire l'activité qu'on visualise
    //heure et date déterminée en extractant une sous-chaine de charactère basé sur ce que j'ai envoyé dans l'url comme infos
    $heure = substr($_GET['heure'],-18, 2);
    $heure = $heure . ":00:00";
    $date = substr($_GET['heure'], -10);
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
    <h1>Modifications des inscriptions</h1>


    <!-- Contenu du site -->
    <main id="mainModifsAdmin">

        <!-- Met à jour les information du cours -->
        <h2>Inscritptions au cours de <span>
                <?php echo $details[0]['cours_activite']; ?></span></h2>
        <h3>avec <span>
                <?php echo $details[0]['cours_nom']; ?></span></h3>

        <p>
            <?php echo isset($retSQL) ? $retSQL : "&nbsp;" ?>
        </p>
        <form action="inscriptionsDetails.php" method="post">
            <table>
                <tr>
                    <th>Détails du membre</th>
                    <th>Status</th>
                    <th></th>
                </tr>

                <!-- Met à jour les inscrits du cours, en tenant compte de si on est admin ou non, puisque les droits changent un peu d'avec l'entraineur -->
                <?php foreach($users AS $usager) : ?>
                <tr>
                    <td>
                        <input type="hidden" name="utilisateur_id[]" value="<?php echo $usager['utilisateur_id'] ?>">
                        <input type="hidden" name="horaire_id[]" value="<?php echo $details[0]['cours_id'] ?>">
                        <?php echo $usager['inscrit_nom']; ?>
                    </td>
                    <td>
                        <!-- ici s'effectue la vérification -->
                        <?php if($usager['inscrit_status'] == 0) : ?>

                        <?php if(isset($_SESSION['administrateur'])) : ?>

                        <select name="utilisateur_horaires_accepte[]">
                            <option value="0" selected>En attente</option>
                            <option value="1">Payé</option>
                        </select>

                        <?php else : ?>

                        <select name="utilisateur_horaires_accepte[]" disabled>
                            <option value="0" selected>En attente</option>
                            <option value="1">Payé</option>
                        </select>

                        <?php endif; ?>

                        <?php else : ?>

                        <!-- ici s'effectue la vérification -->
                        <?php if(isset($_SESSION['administrateur'])) : ?>

                        <select name="utilisateur_horaires_accepte[]">
                            <option value="0">En attente</option>
                            <option value="1" selected>Payé</option>
                        </select>

                        <?php else : ?>

                        <select name="utilisateur_horaires_accepte[]" disabled>
                            <option value="0">En attente</option>
                            <option value="1" selected>Payé</option>
                        </select>

                        <?php endif; ?>

                        <?php endif; ?>
                    </td>
                    <td><a href="supprimerUsager.php?id=<?php echo $usager['utilisateur_id']; ?>?heure=<?php echo $heure; ?>?date=<?php echo $date; ?>">Supprimer</a></td>
                    <?php endforeach; ?>

                    <?php if(($details[0]['cours_max']) != ($details[0]['cours_inscrits'])) : ?>
                <tr>
                    <td><a href="ajouterUsager.php?heure=<?php echo $heure; ?>?date=<?php echo $date; ?>">Ajouter</a></td>
                    <td></td>
                    <td></td>
                </tr>
                <?php endif; ?>
            </table>

            <!-- input hidden pour garder en mémoire la date et l'heure meêm après rafraichissement de la page à l'envoie -->
            <input type="hidden" name="date" value="<?php echo $date; ?>">
            <input type="hidden" name="heure" value="<?php echo $heure; ?>">


            <!-- enlève le bouton pour modifier, puisqu'utile seulement pour le mode de paiment, si on est pas administrateur -->
            <?php if(isset($_SESSION['administrateur'])) : ?>

            <input type="submit" name="modifier" value="Modifier">

            <?php endif; ?>
        </form>
        <br />

        <!-- vérifie encore le type d'usager pour renvoyer à la bonne place -->
        <?php if(isset($_SESSION['administrateur'])) :  ?>

        <a href="admin.php">Retour</a>

        <?php else : ?>

        <a href="../index.php">Retour</a>

        <?php endif; ?>
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
