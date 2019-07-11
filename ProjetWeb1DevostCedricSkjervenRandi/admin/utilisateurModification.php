<!--
*   Auteurs: Cédric Devost et Randi Skjerven
*   Date: 2019-01-21
*   Description: Page php admin pour le projet web 1, permet de modifier le contenu sensible de l'application
*   Fichier: admin.php
-->

<?php
require_once("../inc/sessionUtilisateur.php");
require_once("../inc/connectDB.php");
require_once("../inc/sql.php");

// pour affichage du nom complet de l'utilisateur connecté
// -------------------------------------------  
$user = afficherUser($conn, $_SESSION['administrateur']);

// pour afficher les mode de paiement disponible dans un selecteur
// -------------------------------------------  
$filtrePaiement = sqlFiltreSelection($conn, "paiement");


// test retour de saisie du formulaire
// -----------------------------------        
if (isset($_POST['modifier'])) {
    $id = $_POST['id'];    
    $erreurs = array();
    
    // contrôles des champs saisis
    // ---------------------------
    $utilisateur_nom = $_POST['utilisateur_nom'];
    $utilisateur_prenom = $_POST['utilisateur_prenom'];
    $utilisateur_courriel = $_POST['utilisateur_courriel'];
    $utilisateur_adresse_rue = $_POST['utilisateur_adresse_rue'];
    $utilisateur_adresse_ville = $_POST['utilisateur_adresse_ville'];
    $utilisateur_adresse_cp = $_POST['utilisateur_adresse_cp'];
    $utilisateur_type = $_POST['utilisateur_type'];
    $utilisateur_fk_paiment_id = $_POST['utilisateur_fk_paiment_id'];
   

    // insertion dans la table produit si aucune erreur
    // ----------------------------------------------- 
    $modification = array($utilisateur_nom, $utilisateur_prenom, $utilisateur_courriel, $utilisateur_adresse_rue, $utilisateur_adresse_ville, $utilisateur_adresse_cp, $utilisateur_type,   $utilisateur_fk_paiment_id);
    
    if (count($erreurs) === 0) {
        if (sqlModification($conn, $modification, "utilisateur", $id) === 1) {
            $retSQL="Modification effectuée. Merci";  

           
        } else {
            $retSQL ="Modification non effectuée.";    
        }
        //réinitialisation des champs suite à l'envoie du formulaire
        // --------------------------------------------------------- 
        $filtrePaiement = sqlFiltreSelection($conn, "paiement");
        $filtreUtilisateur = sqlFiltreDetail($conn, "utilisateur", $id);
    }
}
else {
    // test retour de saisie du formulaire
    // ----------------------------------- 
    $id = isset($_GET['id']) ? $_GET['id'] : "";
    
    //extraction de valeurs dans la BD pour remplir les champs avec associés dans le formulaire
    // ---------------------------------------------------------------------------------------- 
    $filtreUtilisateur = sqlFiltreDetail($conn, "utilisateur", $id);
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
    <h1>Modification d'un cours</h1>

    <!-- Contenu du site -->
    <main id="mainModifsAdmin">
        <h2>Modification d'un Utilisateur</h2>

        <p>
            <?php echo isset($retSQL) ? $retSQL : "&nbsp;" ?>
        </p>
        <!-- Formulaire -->
        <form action="utilisateurModification.php" method="post">
            <table>
                <tr>
                    <td>Numero de membre : </td>
                    <td>
                        <input type="text" name="utilisateur_id" value="<?php echo $filtreUtilisateur[0]['utilisateur_id']?>" required disabled>
                    </td>
                </tr>
                <tr>
                    <td>Prénom : </td>
                    <td>
                        <input type="text" name="utilisateur_prenom" value="<?php echo $filtreUtilisateur[0]['utilisateur_prenom']?>" required>
                    </td>
                </tr>
                <tr>
                    <td>Nom : </td>
                    <td>
                        <input type="text" name="utilisateur_nom" value="<?php echo $filtreUtilisateur[0]['utilisateur_nom']?>" required>
                    </td>
                </tr>
                <tr>
                    <td>Courriel : </td>
                    <td>
                        <input type="text" name="utilisateur_courriel" value="<?php echo $filtreUtilisateur[0]['utilisateur_courriel']?>" required>
                    </td>
                </tr>
                <tr>
                    <td>Adresse : </td>
                    <td>
                        <input type="text" name="utilisateur_adresse_rue" value="<?php echo $filtreUtilisateur[0]['utilisateur_adresse_rue']?>" required>
                    </td>
                </tr>
                <tr>
                    <td>Ville: </td>
                    <td>
                        <input type="text" name="utilisateur_adresse_ville" value="<?php echo $filtreUtilisateur[0]['utilisateur_adresse_ville']?>" required>
                    </td>
                </tr>
                <tr>
                    <td>Code Postal: </td>
                    <td>
                        <input type="text" name="utilisateur_adresse_cp" value="<?php echo $filtreUtilisateur[0]['utilisateur_adresse_cp']?>" required>
                    </td>
                </tr>
                <tr>
                    <td>Type : </td>
                    <td>
                        <!-- Selecteur avec tous les types d'utilisateurs existant dans la base de données -->
                        <select name="utilisateur_type">
                            <option value="client" <?php if ($filtreUtilisateur[0]['utilisateur_type']=="client" ) echo 'selected="selected"' ; ?>>Client</option>
                            <option value="entraineur" <?php if ($filtreUtilisateur[0]['utilisateur_type']=="entraineur" ) echo 'selected="selected"' ; ?>>Entraineur</option>
                            <option value="administrateur" <?php if ($filtreUtilisateur[0]['utilisateur_type']=="administrateur" ) echo 'selected="selected"' ; ?>>Administrateur</option>
                            <option value="Non-actif" <?php if ($filtreUtilisateur[0]['utilisateur_type']=="Non-actif" ) echo 'selected="selected"' ; ?>>Non-Actif</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>Mode de Paiement:</td>
                    <td>
                        <!-- Selecteur avec tous les types de paiements existant dans la base de données -->
                        <select name="utilisateur_fk_paiment_id">
                            <?php foreach($filtrePaiement as $row): ?>
                            <option value="<?php echo $row['filtre_id'] ?>" <?php if ($filtreUtilisateur[0]['utilisateur_fk_paiment_id']==$row['filtre_id']) echo 'selected="selected"' ; ?>>
                                <?php echo $row['filtre_nom']?>
                            </option>
                            <?php endforeach; ?>

                        </select></td>
                </tr>
            </table>
            <!-- Puisque le ID de l'utilisateur n'est pas sujet a une modification mais nécessaire a la requête, ce input sera de type "hidden"-->
            <input type="hidden" name="id" value="<?php echo $id; ?>">
            <input type="submit" name="modifier" value="Modifier">
        </form>
        <br />
        <a href="listeUsagers.php?type=administrateur">Retour</a>
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
