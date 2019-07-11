<?php 
//Met un timezone par défaul histoire de s'assurer que l'utilisateur a la bonne date
date_default_timezone_set('America/New_York');

//Appel aux pages php requises pour le bon fonctionnement de la page
require_once("../inc/sessionUtilisateur.php");
require_once("../inc/connectDB.php");
require_once("../inc/sql.php");

//affiche le nom de l'admin connecté
$user = afficherUser($conn, $_SESSION['administrateur']);

//Détermine si la date a été changée manuellement, sinon, met la date qui est dans l'url, ou la date du jour
if(isset($_POST['input'])){
    $date = $_POST['input'];
}
else{
    $date = isset($_GET['date']) ? $_GET['date'] : date('Y-m-d');
}

//Gestion de la date
$moins = date('Y-m-d', strtotime($date .' -1 day'));
$plus = date('Y-m-d', strtotime($date .' +1 day'));
?>

<!DOCTYPE HTML>
<!--
*   Auteurs: Cédric Devost et Randi Skjerven
*   Date: 2019-01-21
*   Description: Page php admin pour le projet web 1, permet de modifier le contenu sensible de l'application
*   Fichier: admin.php
-->
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
    <title>Administrateur</title>
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
                <li><a href="listeUsagers.php?type=administrateur" title="Notre Équipe">Gestion Membres</a></li>
            </ul>
            <ul id="petit">
                <li><a href="listeActivite.php" title="Nos Activités">Gestion Activités</a></li>
                <li><span class="fa fa-star checked"></span></li>
                <li><a href="listeUsagers.php?type=administrateur" title="Notre Équipe">Gestion Membres</a></li>
            </ul>
        </nav>
    </header>

    <!-- Contenu du site -->
    <main id="mainIndexAdmin">
        <article>

            <!-- Gestion de la date -->
            <div id="laDate">
                <a class="plusMoins" href="<?php echo '?date=' . $moins ?>"> - </a>
                <form method="post">
                    <input id="date" name="input" type="date" value="<?php echo $date; ?>" onkeydown="return false" onchange="submit()">
                    
                    <!-- Input type hidden pour garder en mémoire la date -->
                    <input type="hidden" name="date" value="<?php echo $date; ?>">
                </form>
                <a class="plusMoins" href="<?php echo '?date=' . $plus ?>"> + </a>
            </div>
            <div id="divMain">

                <!-- Cellules que l'administrateur peut modifier -->

                <!-- Boucle qui met les 6 cellulles et les populent en fonction de ce qu'elles doivent contenir -->
                <?php for($i=10; $i<16; $i++) : 
                
                    //affectation de l'heure pour l'utiliser dans la BD
                    $heure = $i . ":00:00";
                    $details = afficherInfoCours($conn, $date, $heure);
                
                    //valide si il y a un cours d'inscrit à l'horraire, si oui, rempli le div comme il faut
                    if(count($details) > 0) : 
                    
                        $duree = (int)substr($details[0]['cours_duree'], -7, 1); 
                        $fin = ($i + $duree);
                ?>
                        <div>
                            <div>
                                
                                <!-- Heure de début et fin -->
                                <?php echo $i . ":00"; ?> - <?php echo $fin . ":00"; ?>
                                <span>
                                    
                                    <!-- rajoute une étoile si le cours comporte des paiments en attente -->
                                    <?php if($details[0]['cours_attente']>0){echo '<span id="star">*</span>';} ?>
                                    <?php echo $details[0]['cours_inscrits']; ?>/<?php echo $details[0]['cours_max']; ?>
                                </span>
                            </div>
                            <div>
                                <span>
                                    <?php echo $details[0]['cours_activite']; ?> avec
                                </span>
                                <span>
                                    <?php echo $details[0]['cours_nom']; ?>
                                </span>
                            </div>

                            <!-- Le href des liens se modifie en fonction de la date et l'heure, ou de l'id du cours, pour mener à une autre page et exploiter ces données -->
                            <div>
                                <a href="coursModification.php?id=<?php echo $details[0]['cours_id']; ?>">Modifier</a> / 
                                <a href="coursSupprimer.php?id=<?php echo $details[0]['cours_id']; ?>">Supprimer</a> / 
                                <a href="inscriptionsDetails.php?heure=<?php echo $i; ?>?date=<?php echo $date; ?>">Inscription</a>
                            </div>
                        </div>

                    <!-- Dans le cas où il n'y a pas de cours, rempli la div pour dire qu'il n'y a pas de cours et qu'on peut en ajouter un -->
                    <?php else : ?>

                        <div>
                            <div>
                                <?php echo $i . ":00"; ?> - <?php echo $i+1 . ":00"; ?>
                                <span></span>
                            </div>
                            <div>
                                <span>Aucune Activité</span>
                                <span></span>
                            </div>
                            <div>
                                <a href="coursAjout.php?heure=<?php echo $i; ?>?date=<?php echo $date; ?>">Ajouter</a>
                            </div>
                        </div>

                    <?php endif; ?>

                <?php endfor; ?>
                
            </div>
        </article>
        
        <!-- Aside contenant une pub, qui sera affiché ou non, selon le format de la page -->
        <aside id="adminAside">
            <img src="../images/pubLaide.png" name="pubImage" alt="Visitez NitroTech.ca pour plus d'infos">
            <div>
                <input type="file" id="files" />
                <br />
                <button onclick="changerImage()">Modifier</button>
            </div>
        </aside>
    </main>

    <!-- Footer -->
    <footer>
        <div>
            <p>Soutient Technique: 514-559-0562</p>
            <p id="foot">soutientechnique@goDaddy.com</p>
        </div>
    </footer>
    
    <!-- Appel à la page scripts.js pour modifier l'image de la pub -->
    <script src="../js/scripts.js"></script>
</body>

</html>
