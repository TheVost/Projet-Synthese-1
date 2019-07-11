<?php
/*appel aux scripts php nécessaire au bon fonctionnement de la page*/
require_once("../inc/connectDB.php");
require_once("../inc/sql.php");

/*test retour de saisie du formulaire*/
if (isset($_POST['connection'])) {

    //Saisie les identifiants et mot de passe entrés
    $identifiant = trim($_POST['email']);
    $mot_de_passe = trim($_POST['pwd']);

    /*détermnine si la session en cours est admin ou client et redirrige en conséquence*/
    if (sqlControlerUtilisateur($conn, $identifiant, $mot_de_passe) == "administrateur") {
        session_start();
        $_SESSION['administrateur'] = $identifiant;
        header('Location: ../admin/admin.php'); 
    }
    else if (sqlControlerUtilisateur($conn, $identifiant, $mot_de_passe) == "client") {
        session_start();
        $_SESSION['client'] = $identifiant;
        header('Location: ../index.php'); 
    } 
    else if (sqlControlerUtilisateur($conn, $identifiant, $mot_de_passe) == "entraineur") {
        session_start();
        $_SESSION['entraineur'] = $identifiant;
        header('Location: ../index.php'); 
    } 
    else {
        $erreur = "Identifiant ou mot de passe incorrect.";
    }
}
?>

<!DOCTYPE HTML>
<!--
*   Auteurs: Cédric Devost et Randi Skjerven
*   Date: 2019-01-18
*   Description: Page php responsable de la connexion pour le projet web 1
*   Fichier: connexion.php
-->
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta name="description" content="connexion">
    <meta name="author" content="Cédric Devost, Randi Skjerven">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" type="text/css" href="../stylesheets/styles.css">
    <link href="https://fonts.googleapis.com/css?family=Balthazar" rel="stylesheet">
    <title>Connexion</title>
</head>

<!-- Contenu de la page -->

<body id="connexionBody">
    <header id="connexionHeader">
        <img src="../images/GymQuartier.png" alt="Gym du Quartier" />
    </header>

    <!-- Contenu de la page -->
    <main id="connexionMain">
        <p>
            <?php echo isset($erreur) ? $erreur : "&nbsp;" ?>
        </p>
        <form action="connexion.php" method="post">

            <!-- L'identifiant est gardé en mémoire si il est entré et qu'une erreur survient -->
            <label>Identifiant: </label>
            <input type="email" name="email" placeholder="exemple@gmail.com" value="<?php if(isset($identifiant)){echo $identifiant;}?>">
            <label>Mot de passe: </label>
            <input type="password" name="pwd">
            <br />
            <input id="connecter" type="submit" name="connection" value="Se Connecter">
        </form>
        <br />

        <!-- Navigation -->
        <nav id="connexionNav">
            <p>Pas encore membre??! <a href="inscription.php" title="Inscription">Cliquez ICI!!</a></p>
            <p><a href="../index.php" title="Retour à la page principale">Retour</a></p>
        </nav>
    </main>
</body>

</html>
