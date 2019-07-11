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

//valide si il y a confirmation et si oui, si on a cliqué sur oui, ou non
$confirme = isset($_POST['confirmation']) ? $_POST['confirmation'] : "";

//si cliqué sur oui
if ($confirme === "Oui") {
    
    // contrôles des champs saisis
    // ---------------------------
    $id = $_POST['id'];
    $id= (int)$id;
    $inscrits = $_POST['inscrits'];
    $filtreCours = $_POST['filtre'];
    
    //Si la suppression passe, on confirme et redirige à la page admin.php
    if (sqlSupprimer($conn, 'cours', $id, $inscrits) === 1) {
        echo "<script>alert('Suppression effectuée. Merci');
        window.location.href='admin.php';</script>";
    } 
    else {
        $retSQL ="Suppression non effectuée.";  
        
    }
    
    //permet d'Afficher les détials du cours en question
    $filtreCours = sqlFiltreDetail($conn, "cours", $id); 
}
// si cliqué sur non
else if ($confirme === "Non"){
    header("Location: admin.php");
}   
else {
    $id = isset($_GET['id']) ? $_GET['id'] : "";
    $id= (int)$id;
    
    //sert à savoir s'il y a des gens inscrits à l'Activité, parce que dans le cas où il y en a, la requête de suppression ne sera pas la même
    $inscrits = trouverNbInscrits($conn, $id);
    $filtreCours = sqlFiltreDetail($conn, "cours", $id); 
}
?>
<!--
*   Auteurs: Cédric Devost et Randi Skjerven
*   Date: 2019-01-28
*   Description: Page php qui permet de supprimer un cours d'une cellule donnée, qu'il y ait des inscrits à l'Activité ou non
*   Fichier: coursSupprimer.php
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
    <title>Supprimer le Cours</title>
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

    <!-- Contenu du site, afficher les infos du cours à supprimer-->
    <main id="mainModifsAdmin">
        <h2>Suppression d'un cours</h2>
        <form action="coursSupprimer.php" method="post">
            <h3>Voulez-vous supprimer le cours suivant : </h3>
            <p>
                <?php echo isset($retSQL) ? $retSQL : "&nbsp;" ?>
            </p>
            <table>
                <tr>
                    <td>Numero du cours : </td>
                    <td>
                        <?php echo $filtreCours[0]['horaire_id']?>
                    </td>
                </tr>
                <tr>
                    <td>Date du Cours : </td>
                    <td>
                        <?php echo $filtreCours[0]['horaire_date']?>
                    </td>
                </tr>
                <tr>
                    <td>Heure du Cours : </td>
                    <td>
                        <?php echo $filtreCours[0]['horaire_heure']?>
                    </td>
                </tr>
                <tr>
                    <td>Activité :</td>
                    <td>
                        <select name="activite_id" disabled>
                            
                            <?php foreach($filtreActivite as $row): ?>
                            
                                <!-- affiche l'activité correspondante -->
                                <option value="<?php echo $row['filtre_id'] ?>" <?php if ($filtreCours[0]['activite_id']==$row['filtre_id']) echo 'selected="selected"' ; ?> >
                                    <?php echo $row['filtre_nom']?>
                                </option>
                            
                            <?php endforeach; ?>
                            
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>Entraineur :</td>
                    <td>
                        <select name="entraineur_id" disabled>
                            
                            <?php foreach($filtreEntraineur as $row): ?>
                            
                                <!-- affiche l'entraineur correspondant -->
                                <option value="<?php echo $row['filtre_id'] ?>" <?php if ($filtreCours[0]['entraineur_id']==$row['filtre_id']) echo 'selected="selected"' ; ?>>
                                    <?php echo $row['filtre_nom']?>
                                </option>
                            
                            <?php endforeach; ?>

                        </select>
                    </td>
                </tr>
            </table>
            <br>
            
            <!-- input hidden pour garder en mémoire l'id, les infos du cours et le nombre d'inscrits -->
            <input type="hidden" name="id" value="<?php echo $id; ?>">
            <input type="hidden" name="filtre" value="<?php print_r($filtreCours); ?>">
            <input type="hidden" name="inscrits" value="<?php echo $inscrits; ?>">
            <input type="submit" name="confirmation" value="Oui">
            <input type="submit" name="confirmation" value="Non">
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
