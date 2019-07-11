<!--
*   Auteurs: Cédric Devost et Randi Skjerven
*   Date: 2019-01-21
*   Description: Page php admin pour le projet web 1, permet l'ajout d'un utilisateur par un membre de l'administration
*   Fichier: utilisateurAjout.php
-->


<?php
require_once("../inc/sessionUtilisateur.php");
require_once("../inc/connectDB.php");
require_once("../inc/sql.php");


// pour affichage du nom complet de l'utilisateur connecté
// -------------------------------------------------------  
$user = afficherUser($conn, $_SESSION['administrateur']);

// pour afficher les mode de paiement disponible dans un selecteur
// ---------------------------------------------------------------  
$filtrePaiement = sqlFiltreSelection($conn, "paiement");

// test retour de saisie du formulaire
// -----------------------------------        
if (isset($_POST['ajouter'])) {
    
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
    $utilisateur_mot_de_passe = $_POST['utilisateur_mot_de_passe'];
   

    // insertion dans la table produit si aucune erreur
    // ----------------------------------------------- 
    $ajout = array($utilisateur_nom, $utilisateur_prenom, $utilisateur_courriel, $utilisateur_adresse_rue, $utilisateur_adresse_ville, $utilisateur_adresse_cp,   $utilisateur_fk_paiment_id, $utilisateur_mot_de_passe);
    
    if (count($erreurs) === 0) {
        if (sqlAjouter($conn, $ajout, "utilisateur", $utilisateur_type) === 1) {
            $retSQL="Ajout effectuée. Merci";  
            
           
        } else {
            $retSQL ="Ajout non effectuée.";    
        }
    }
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
                <p>Connecté sous: <?php echo $user; ?></p>
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
    <h1>Ajout d'un utilisateur</h1>

    <!-- Contenu du site -->
    <main id="mainModifsAdmin">
        <h2>Ajout d'un utilisateur</h2>

        <p>
            <?php echo isset($retSQL) ? $retSQL : "&nbsp;" ?>
        </p>
        <!-- formulaire -->
        <form action="utilisateurAjout.php" method="post">
            <table>
                <tr>
                    <td>Prénom : </td>
                    <td>
                        <input type="text" name="utilisateur_prenom" required>
                    </td>
                </tr>
                <tr>
                    <td>Nom : </td>
                    <td>
                        <input type="text" name="utilisateur_nom" required>
                    </td>
                </tr>
                <tr>
                    <td>Courriel : </td>
                    <td>
                        <input type="email" name="utilisateur_courriel" required>
                    </td>
                </tr>
                <tr>
                    <td>Adresse : </td>
                    <td>
                        <input type="text" name="utilisateur_adresse_rue" required>
                    </td>
                </tr>
                <tr>
                    <td>Ville: </td>
                    <td>
                        <input type="text" name="utilisateur_adresse_ville" required>
                    </td>
                </tr>
                <tr>
                    <td>Code Postal: </td>
                    <td>
                        <input type="text" name="utilisateur_adresse_cp" required>
                    </td>
                </tr>
                <tr>
                    <td>Type : </td>
                    <td>
                        <select name="utilisateur_type">
                            <option value="client">Client</option>
                            <option value="entraineur">Entraineur</option>
                            <option value="administrateur">Administrateur</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>Mode de Paiement:</td>
                    <td>
                        <!-- Selecteur avec tous les types d'utilisateurs existant dans la base de données -->
                        <select name="utilisateur_fk_paiment_id">
                            <?php foreach($filtrePaiement as $row): ?>
                            <option value="<?php echo $row['filtre_id'] ?>">
                                <?php echo $row['filtre_nom']?>
                            </option>
                            <?php endforeach; ?>

                        </select></td>
                </tr>
                <tr>
                    <td>Mot de Passe: </td>
                    <td>
                        <input type="text" name="utilisateur_mot_de_passe" value = "password" required>
                    </td>
                </tr>
            </table>
            <input type="submit" name="ajouter" value="ajouter">
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
