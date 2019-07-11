<?php
//Appel aux pages php requises pour le bon fonctionnement de la page
require_once("../inc/sessionUtilisateur.php");
require_once("../inc/connectDB.php");
require_once("../inc/sql.php");

//affiche le nom de l'admin connecté
$user = afficherUser($conn, $_SESSION['administrateur']);

//permet d'affiche les détails du cours et de l'entraineur plus loin dans la page
$filtreEntraineur = sqlFiltreSelection($conn, "entraineur");
$filtreActivite = sqlFiltreSelection($conn, "activite");

// test retour de saisie du formulaire
// -----------------------------------        
if (isset($_POST['Ajouter'])) {
    
    //pour garder en mémoire la date et l'heure et donc l'Activité qu'on visualise
    $date = $_POST['horaire_date'];
    $heure = $_POST['heure'];
    
    $erreurs = array();
    
    // contrôles des champs saisis
    // ---------------------------
    $horaire_duree = $_POST['horaire_duree'];
    $horaire_date = $_POST['horaire_date'];
    $horaire_heure = $_POST['horaire_heure'];
    $horaires_fk_activite_id = $_POST['activite_id'];
    $horaire_fk_entraineur_id = $_POST['entraineur_id'];
   
    // insertion dans la base de donnée si aucune erreur
    // ----------------------------------------------- 
    $ajout = array($horaire_duree, $horaire_date, $horaire_heure, $horaires_fk_activite_id, $horaire_fk_entraineur_id);
    
    if (count($erreurs) === 0) {
        if (sqlAjouter($conn, $ajout, "cours", 0, 0) === 1) {
            $retSQL="Ajout effectuée. Merci";  
            
        } 
        else {
            $retSQL ="Ajout non effectuée.";    
        }
    }
}  
else {
    //Instanciation de variables nécessaires plus loin et pour garder en mémoire l'activité qu'on visualise
    //heure et date déterminée en extractant une sous-chaine de charactère basé sur ce que j'ai envoyé dans l'url comme infos
    $heure = substr($_GET['heure'],-18, 2);
    $date = substr($_GET['heure'], -10);
}
?>
<!--
*   Auteurs: Cédric Devost et Randi Skjerven
*   Date: 2019-01-21
*   Description: Page php permettant à l'administrateur d'ajouter un cours à une cellule donnée
*   Fichier: coursAjout.php.php
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
    <title>Ajout de Cours</title>
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

    <!-- Contenu du site -->
    <main id="mainModifsAdmin">
        <h2>Ajout d'un cours</h2>
        <p>
            <?php echo isset($retSQL) ? $retSQL : "&nbsp;" ?>
        </p>
        <form action="coursAjout.php" method="post">
            <table>
                <tr>
                    <td>Date du Cours : </td>
                    <td>
                        <input type="date" name="horaire_date" value="<?php echo $date; ?>" required>
                    </td>
                </tr>
                <tr>
                    <td>Durée du Cours : </td>
                    <td>
                        <select name="horaire_duree">
                            
                            <!-- Valeurs formater pour bien s'ajouter dans la BD -->
                            <option value="010000">1 heure</option>
                            <option value="020000">2 heures</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>Heure du Cours : </td>
                    <td>
                        
                        <!-- Permet d'ajouter un cours que pour la plage que l'on a sélectionné -->
                        <select name="horaire_heure">
                            
                            <?php if($heure == 10) : ?>
                            
                                <option value="100000">10:00</option>
                            
                            <?php endif; ?>
                            
                            <?php if($heure == 11) : ?>
                            
                                <option value="110000">11:00</option>
                            
                            <?php endif; ?>
                            
                            <?php if($heure == 12) : ?>
                            
                                <option value="120000">12:00</option>
                            
                            <?php endif; ?>
                            
                            <?php if($heure == 13) : ?>
                            
                                <option value="130000">13:00</option>
                            
                            <?php endif; ?>
                            
                            <?php if($heure == 14) : ?>
                            
                                <option value="140000">14:00</option>
                            
                            <?php endif; ?>
                            
                            <?php if($heure == 15) : ?>
                            
                                <option value="150000">15:00</option>
                            
                            <?php endif; ?>

                        </select>
                    </td>
                </tr>
                <tr>
                    <td>Activité :</td>
                    <td>
                        
                        <!-- permet de choisir n'importe quelle activité -->
                        <select name="activite_id">
                            <?php foreach($filtreActivite as $row): ?>
                            <option value="<?php echo $row['filtre_id'] ?>">
                                <?php echo $row['filtre_nom']?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>Entraineur :</td>
                    <td>
                        
                        <!-- permet de choisir n'importe quel entraineur -->
                        <select name="entraineur_id">
                            <?php foreach($filtreEntraineur as $row): ?>
                            <option value="<?php echo $row['filtre_id'] ?>">
                                <?php echo $row['filtre_nom']?>
                            </option>
                            <?php endforeach; ?>

                        </select>
                    </td>
                </tr>
            </table>
            
            <!-- input de type hidden pour garder en mémoire l'heure -->
            <input type="hidden" name="heure" value="<?php echo $heure; ?>">
            <input type="submit" name="Ajouter" value="Ajouter">
        </form>
        
        <br />
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
