<?php 
//Met un timezone par défaul histoire de s'assurer que l'utilisateur a la bonne date
date_default_timezone_set('America/New_York');

//Appel aux pages php requises pour le bon fonctionnement de la page
require_once("inc/connectDB.php");
require_once("inc/sql.php");
session_start();

//Détermine si un entraineur ou un client est connecté
if(isset($_SESSION['entraineur'])){
    $user = afficherUser($conn, $_SESSION['entraineur']);
}
if(isset($_SESSION['client'])){
    $user = afficherUser($conn, $_SESSION['client']);
    $userID =  afficherUserId($conn, $user); 
}

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
*   Date: 2019-01-18
*   Description: Page php index pour le projet web 1, permet de s'inscrire et naviguer
*   Fichier: index.php
-->
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta name="description" content="Accuiel, Gym du Quartier, gym, forme physique, santé">
    <meta name="author" content="Cédric Devost, Randi Skjerven">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" type="text/css" href="stylesheets/styles.css">

    <!-- Balise pour les fonts google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Balthazar" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Allerta+Stencil" rel="stylesheet">

    <!-- Balise pour les étoiles dans la barre de navigation -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <title>Gym du Quartier</title>
</head>

<body>
    <header>
        
        <!-- image selon le format de la page -->
        <img id="pc" src="images/GymQuartier.png" alt="Gym du Quartier">
        <img id="mobile" src="images/GymQuartierPetit.png" alt="Gym du Quartier">

        <!-- Navigation -->
        <nav>
            <div>
                <!-- Inscrit le nom de l'usager connecté, s'il y a lieu, et remplace le menu dans ce cas -->
                <?php if((isset($_SESSION['entraineur'])) || (isset($_SESSION['client']))) : ?>

                <p>Connecté sous:
                    <?php echo $user; ?>
                </p>
                <p>(<a href="connection/deconnexion.php">Déconnexion</a>)</p>

                <?php else : ?>

                <a href="connection/connexion.php" title="Connexion">Connexion</a> / <a href="connection/inscription.php" title="Inscription">Inscription</a>

                <?php endif; ?>
            </div>

            <!-- Gestion du menu pour qu'il soit responsive -->
            <ul id="gros">
                <li><a id="active" href="index.php" title="Nos Activités">Accueil</a></li>
                <li><span class="fa fa-star checked"></span></li>
                <li><a href="second/activites.php" title="Nos Activités">Activités</a></li>
                <li><span class="fa fa-star checked"></span></li>
                <li><a href="second/equipe.php" title="Notre Équipe">Notre Équipe</a></li>
                <li><span class="fa fa-star checked"></span></li>
                <li><a href="second/contact.php" title="Nous joindre">Nous Joindre</a></li>
            </ul>
            <ul id="petit">
                <li><a id="active" href="index.php" title="Nos Activités">Accueil</a></li>
                <li><span class="fa fa-star checked"></span></li>
                <li><a href="second/activites.php" title="Nos Activités">Activités</a></li>
                <li><span class="fa fa-star checked"></span></li>
                <li><a href="second/equipe.php" title="Notre Équipe">Notre Équipe</a></li>
                <li><span class="fa fa-star checked"></span></li>
                <li><a href="second/contact.php" title="Nous joindre">Nous Joindre</a></li>
            </ul>
        </nav>
    </header>

    <!-- Pour le référencement de google -->
    <h1>Inscriptions aux activités</h1>

    <!-- Contenu du site -->
    <main id="mainIndex">
        <article>

            <!-- Gestion de la date -->
            <div id="laDate">
                <a id="moins" href="<?php echo '?date=' . $moins ?>"> - </a>
                <form method="post">
                    <input id="date" name="input" type="date" value="<?php echo $date; ?>" onkeydown="return false" onchange="submit()">
                    <input type="hidden" name="date" value="<?php echo $date; ?>">
                </form>
                <a id="plus" href="<?php echo '?date=' . $plus ?>"> + </a>
            </div>
            <div id="divMain">

                <!-- Cellules qui serviront aux inscriptions -->

                <!-- Boucle qui met les 6 cellulles et les populent en fonction de ce qu'elles doivent contenir -->
                <?php for($i=10; $i<16; $i++) : 
                    //affectation de l'heure pour l'utiliser dans la BD
                    $heure = $i . ":00:00";
                
                    //valide si il y a un cours d'inscrit à l'horraire, si oui, rempli le div comme il faut
                    $details = afficherInfoCours($conn, $date, $heure);
                    if(count($details) > 0) : 
                    
                        $duree = (int)substr($details[0]['cours_duree'], -7, 1); 
                        $fin = ($i + $duree);
                ?>

                <!-- Le href du lien se modifie en fonction de la date et l'heure pour mener à une autre page et exploiter ces données -->

                <!-- Certaines données vont aussi changer si c'est un entraineur qui est connecté -->
                        <?php if(isset($_SESSION['entraineur'])) : ?>

                            <a href="admin/inscriptionsDetails.php?heure=<?php echo $i; ?>?date=<?php echo $date; ?>">
                                <div>
                                    <?php echo $i . ":00"; ?> - <?php echo $fin . ":00"; ?>
                                    <span>
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
                                <div>
                                    <span>Visualiser</span><span>+</span>
                                </div>
                            </a>

                        <?php else : ?>

                        <!-- s'assure que le max d'admis n'est pas atteint, et que le cours a lieu le jour-même ou après, pas avant -->
                        <!-- Vérifie également avec statusInscription si l'usager est inscrit, en attente ou pas inscrit et répond en conséquence -->
                            <?php if((($details[0]['cours_max']) != ($details[0]['cours_inscrits'])) && ($date >= date('Y-m-d'))) : 

                                    if(isset($_SESSION['client'])){
                                        $statusInscription = confirmationInscription($conn, $userID, $details[0]['cours_id']);
                                    }
                                    else{
                                        $statusInscription = 2;
                                    }

                                    if ($statusInscription === 2) : 
                            ?>

                                        <a href="admin/sinscrire.php?heure=<?php echo $i; ?>?date=<?php echo $date; ?>">
                                            <div>
                                                <?php echo $i . ":00"; ?> - <?php echo $fin . ":00"; ?>
                                                <span>
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
                                            <div>
                                                <span>S'inscrire </span><span>+</span>
                                            </div>
                                        </a>

                                    <!-- Agit selon un usager inscrit -->
                                    <?php elseif ($statusInscription === 1) : ?>

                                        <a href="#">
                                            <div>
                                                <?php echo $i . ":00"; ?> - <?php echo $fin . ":00"; ?>
                                                <span>
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
                                            <div>
                                                <span>Inscrit</span><span></span><span>✔</span>
                                            </div>
                                        </a>

                                    <!-- Agit selon un usager non inscrit -->
                                    <?php elseif ($statusInscription === 0) : ?>

                                        <a href="admin/sinscrire.php?heure=<?php echo $i; ?>?date=<?php echo $date; ?>">
                                            <div>
                                                <?php echo $i . ":00"; ?> - <?php echo $fin . ":00"; ?>
                                                <span>
                                                    <?php echo $details[0]['cours_inscrits']; ?>/
                                                    <?php echo $details[0]['cours_max']; ?>
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
                                            <div>
                                                <span>En attente</span><span>?</span>
                                            </div>
                                        </a>

                                    <?php endif; ?>

                                <!-- Si la date d'inscription est terminée ou que le cours est complet -->
                                <?php else : ?>

                                    <a href="#">
                                        <div>
                                            <?php echo $i . ":00"; ?> - <?php echo $fin . ":00"; ?>
                                            <span>
                                                <?php echo $details[0]['cours_inscrits']; ?>/
                                                <?php echo $details[0]['cours_max']; ?>
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
                                        <div>
                                            <span>Aucune inscription </span><span>!</span>
                                        </div>
                                    </a>

                                <?php endif; ?>

                            <?php endif; ?>

                            <!-- Dans le cas où il n'y a pas de cours, rempli la div pour dire qu'il n'y a pas de cours et qu'on peut en ajouter un -->
                        <?php else : ?>

                            <a href="#">
                                <div>
                                    <?php echo $i . ":00"; ?> - <?php echo $i+1 . ":00"; ?>
                                    <span></span>
                                </div>
                                <div><span>Aucune Activité</span><span></span></div>
                                <div><span></span><span></span></div>
                            </a>

                        <?php endif; ?>

                    <?php endfor; ?>
            </div>
        </article>
        <aside>
            <img src="images/pubLaide.png" alt="Visitez NitroTech.ca pour plus d'infos">
        </aside>
    </main>

    <!-- Footer -->
    <footer>
        <div>
            <p>5115 rue Sherbrooke, Montréal</p>
            <p>514-555-5565</p>
            <p>info@gymduquartier.ca</p>
        </div>
    </footer>
    
    <!-- Script nécessaire pour changer le coportement des hover selon si l'utilisateur à intérêt à cliquer ou non -->
    <script src="js/scripts.js"></script>
</body>

</html>
