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
if (isset($_POST['modifier'])) {
    $erreurs = array();
    
    // contrôles des champs saisis
    // ---------------------------
    $id = $_POST['id'];
    $horaire_date = $_POST['date'];
    $horaire_duree = $_POST['horaire_duree'];
    $horaire_heure = $_POST['horaire_heure'];
    $horaires_fk_activite_id = $_POST['activite_id'];
    $horaire_fk_entraineur_id = $_POST['entraineur_id'];

    // insertion dans la base de donnée si aucune erreur
    // ----------------------------------------------- 
    $modification = array($horaire_duree, $horaire_date, $horaire_heure, $horaires_fk_activite_id, $horaire_fk_entraineur_id);
    
    if (count($erreurs) === 0) {
        if (sqlModification($conn, $modification, "cours", $id) === 1) {
            $retSQL="Modification effectuée. Merci";  
            
           
        } 
        else {
            $retSQL ="Modification non effectuée.";    
        }
        
        //permet d'Afficher les détials du cours en question
        $filtreCours = sqlFiltreDetail($conn, "cours", $id); 
    } 
}
else {
    // test retour de saisie du formulaire
    // ----------------------------------- 
    $id = isset($_GET['id']) ? $_GET['id'] : "";
    
    //permet d'Afficher les détials du cours en question
    $filtreCours = sqlFiltreDetail($conn, "cours", $id); 
    
    //récupère la date
    $horaire_date = isset($_POST['date']) ? $_POST['date'] : $filtreCours[0]['horaire_date'];
}
?>
<!--
*   Auteurs: Cédric Devost et Randi Skjerven
*   Date: 2019-01-21
*   Description: Page php qui permet de modifier les information du cours donnée à une plage donnée
*   Fichier: coursModification.php
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
    <title>Modification du Cours</title>
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
        <h2>Modification d'un cours</h2>
        <p>
            <?php echo isset($retSQL) ? $retSQL : "&nbsp;" ?>
        </p>
        <form method="post">
            <table>
                <tr>
                    <td>Numero du cours : </td>
                    <td>
                        <input type="text" name="horaire_id" value="<?php echo $id; ?>" required disabled>
                    </td>
                </tr>
                <tr>
                    <td>Date du Cours : </td>
                    <td>
                        <input type="text" name="horaire_date" value="<?php echo $horaire_date; ?>" required disabled>
                    </td>
                </tr>
                <tr>
                    <td>Durée du Cours : </td>
                    <td>
                        
                        <!-- sélectionne par défaut la durée initiale, mais permet de changer -->
                        <select name="horaire_duree">
                            <option value="010000" <?php if ($filtreCours[0]['horaire_duree']=="01:00:00") echo 'selected="selected"' ; ?>>1 heure</option>
                            <option value="020000" <?php if ($filtreCours[0]['horaire_duree']=="02:00:00") echo 'selected="selected"' ; ?>>2 heures</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>Heure du Cours : </td>
                    <td>
                        
                        <!-- sélectionne par défaut l'heure initiale, mais permet de changer -->
                        <select name="horaire_heure">
                            <option value="1:0000" <?php if ($filtreCours[0]['horaire_heure']=="10:00:00") echo 'selected="selected"' ; ?>>10:00</option>
                            <option value="110000" <?php if ($filtreCours[0]['horaire_heure']=="11:00:00") echo 'selected="selected"' ; ?>>11:00</option>
                            <option value="120000" <?php if ($filtreCours[0]['horaire_heure']=="12:00:00") echo 'selected="selected"' ; ?>>12:00</option>
                            <option value="130000" <?php if ($filtreCours[0]['horaire_heure']=="13:00:00") echo 'selected="selected"' ; ?>>13:00</option>
                            <option value="140000" <?php if ($filtreCours[0]['horaire_heure']=="14:00:00") echo 'selected="selected"' ; ?>>14:00</option>
                            <option value="150000" <?php if ($filtreCours[0]['horaire_heure']=="15:00:00") echo 'selected="selected"' ; ?>>15:00</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>Activité :</td>
                    <td>
                        
                        <!-- sélectionne par défaut l'activité initiale, mais permet de changer -->
                        <select name="activite_id">
                            
                            <?php foreach($filtreActivite as $row): ?>
                            
                                <option value="<?php echo $row['filtre_id'] ?>" <?php if ($filtreCours[0]['activite_id']==$row['filtre_id']) echo 'selected="selected"' ; ?>>
                                    <?php echo $row['filtre_nom']?>
                                </option>
                            
                            <?php endforeach; ?>
                            
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>Entraineur :</td>
                    <td>
                        
                        <!-- sélectionne par défaut l'entrianeur initiale, mais permet de changer -->
                        <select name="entraineur_id">
                            
                            <?php foreach($filtreEntraineur as $row): ?>
                            
                                <option value="<?php echo $row['filtre_id'] ?>" <?php if ($filtreCours[0]['entraineur_id']==$row['filtre_id']) echo 'selected="selected"' ; ?>>
                                    <?php echo $row['filtre_nom']?>
                                </option>
                            
                            <?php endforeach; ?>

                        </select>
                    </td>
                </tr>
            </table>
            
            <!-- input de type hidden pour gader en mémoire l'id du cours et la date -->
            <input type="hidden" name="id" value="<?php echo $id; ?>">
            <input type="hidden" name="date" value="<?php echo $horaire_date; ?>">
            <input type="submit" name="modifier" value="Modifier">
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
