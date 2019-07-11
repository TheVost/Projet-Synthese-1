<?php
require_once("../inc/connectDB.php");
require_once("../inc/sql.php");


// test retour de saisie du formulaire
// -----------------------------------        

if (isset($_POST['inscription'])) {
    
    $erreurs = array();
    $retSQL = "";
    $pattern = "";
    
    // contrôles des champs saisis
    // ---------------------------
    $utilisateur_nom = trim($_POST['nom']);
    $pattern = '/^[A-zéèëçïÉÈËÏÇ][a-zéèëçï]+(( |-)[A-zéèëçïÉÈËÏÇ][a-zéèëçï]+){0,}$/';
    if ($_POST['nom'] == "") {
        $retSQL .= "- Veuillez entrer un nom. ";
    }
    else if(!preg_match($pattern, $utilisateur_nom)){
        $retSQL .= "- Veuillez entrer un nom VALIDE. ";
    }
    $utilisateur_prenom = trim($_POST['prenom']);
    $pattern = '/^[A-zéèëçïÉÈËÏÇ][a-zéèëçï]+(( |-)[A-zéèëçïÉÈËÏÇ][a-zéèëçï]+){0,}$/';
    if ($_POST['prenom'] == "") {
        $retSQL .= "- Veuillez entrer un prénom. ";
    }
    else if(!preg_match($pattern, $utilisateur_prenom)){
        $retSQL .= "- Veuillez entrer un prénom VALIDE. ";
    }
    $utilisateur_email = trim($_POST['email']);
    if ($_POST['email'] == "") {
        $retSQL .= "- Veuillez entrer un email. ";
    }
    $utilisateur_rue = trim($_POST['rue']);
    if ($_POST['rue'] == "") {
        $retSQL .= "- Veuillez entrer une adresse. ";
    }
    $utilisateur_ville = trim($_POST['ville']);
    if ($_POST['ville'] == "") {
        $retSQL .= "- Veuillez entrer une ville. ";
    }
    $utilisateur_cp = trim($_POST['cp']);
    $pattern = '/(^[A-Z][0-9][A-Z] ?[0-9][A-Z][0-9]$)|(^[a-z][0-9][a-z] ?[0-9][a-z][0-9]$)/';
    if ($_POST['cp'] == "") {
        $retSQL .= "- Veuillez entrer un code postal. ";
    }
    else if(!preg_match($pattern, $utilisateur_cp)){
        $retSQL .= "- Veuillez entrer un code postal VALIDE. ";
    }
    $utilisateur_pays = trim($_POST['pays']);
    if ($_POST['pays'] == "") {
        $retSQL .= "- Veuillez entrer un pays. ";
    }
    $utilisateur_paiement = $_POST['paiement'];
    if ($_POST['paiement'] == "") {
        $retSQL .= "- Veuillez entrer un mode de paiement. ";
    }
    $utilisateur_password = trim($_POST['password']);
    if ($_POST['password'] == "") {
        $retSQL .= "- Veuillez entrer un mot de passe. ";
    }
    
    //Si aucune erreur, on envoit le formlaire et on confirme l'inscription
    if($retSQL == ""){
        // Ajout des champs dans la base de donnée via fonction sqlAjouter() si aucune erreur
        // ---------------------------  
        $inscription = array($utilisateur_nom, $utilisateur_prenom, $utilisateur_email, $utilisateur_rue, $utilisateur_ville, $utilisateur_cp, $utilisateur_paiement, $utilisateur_password);

        if (count($erreurs) === 0) {
            if (sqlAjouter($conn, $inscription, "utilisateur", "client") === 1) {
                $retSQL="Inscription effectué. Merci"; 
              
            } else {
                $retSQL ="Inscription non effectué. Veuillez contacter Gym de Quartier";    
            }
            $utilisateur_nom = "";
            $utilisateur_prenom = "";
            $utilisateur_email = "";
            $utilisateur_rue = "";
            $utilisateur_ville = "";
            $utilisateur_cp = "";
            $utilisateur_pays = "";
            $utilisateur_paiement = "";
            $utilisateur_password = "";
        }
    }
}
?>


<!DOCTYPE HTML>
<!--
*   Auteurs: Cédric Devost et Randi Skjerven
*   Date: 2019-01-18
*   Description: Page php responsable de l'inscription pour le projet web 1
*   Fichier: inscription.php
-->
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta name="description" content="inscription">
    <meta name="author" content="Cédric Devost, Randi Skjerven">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" type="text/css" href="../stylesheets/styles.css">
    <link href="https://fonts.googleapis.com/css?family=Balthazar" rel="stylesheet">
    <title>Inscription</title>
</head>

<body id="connexionBody">
    <header id="connexionHeader">
        <img src="../images/GymQuartier.png" alt="Gym du Quartier" />
    </header>

    <!-- Contenu de la page -->
    <main id="inscriptionMain">
        <p>
            <?php echo isset($retSQL) ? $retSQL : "&nbsp;" ?>
        </p>
        <form method="post" action="inscription.php">
            <p>

                <!-- Chaque input renvoie la valeur entrée si elle existe -->
                <label>Prénom: </label>
                <input type="text" name="prenom" placeholder="Jacques" value="<?php if(isset($utilisateur_prenom)){echo $utilisateur_prenom;} ?>">
            </p>
            <p>
                <label>Nom: </label>
                <input type="text" name="nom" placeholder="Plante" value="<?php if(isset($utilisateur_nom)){echo $utilisateur_nom;} ?>">
            </p>
            <p>
                <label>Courriel: </label>
                <input type="email" name="email" placeholder="exemple@gmail.com" value="<?php if(isset($utilisateur_email)){echo $utilisateur_email;} ?>">
            </p>
            <p>
                <label>Adresse : </label>
                <input type="text" name="rue" placeholder="1515 rue Sherbrooke" value="<?php if(isset($utilisateur_rue)){echo $utilisateur_rue;} ?>">
            </p>
            <p>
                <label>Ville: </label>
                <input type="text" name="ville" placeholder="Montréal" value="<?php if(isset($utilisateur_ville)){echo $utilisateur_ville;} ?>">
            </p>
            <p>
                <label>Code Postal: </label>
                <input type="text" name="cp" placeholder="h1h 1h1 ou H1H 1H1" value="<?php if(isset($utilisateur_cp)){echo $utilisateur_cp;} ?>">
            </p>
            <p>
                <label>Mode de Paiement: </label>
                <select name="paiement">
                    <option value="1">Comptant</option>
                    <option value="3">Crédit</option>
                    <option value="2">Débit</option>
                    <option value="4">Carte Cadeau</option>s
                </select>
                <p>
                    <label>Mot de Passe: </label>
                    <input type="password" name="password" placeholder="******">
                </p>


                <input id="inscrire" type="submit" name="inscription" value="S'inscrire!!">

                <!-- Nous prenons pour acquis que tous nos inscrits habitent le canada, mettra l'info dans la base de donnée à la saisie -->
                <input type="hidden" name="pays" value="Canada">
        </form>
        <br />

        <!-- Navigation -->
        <nav id="connexionNav">
            <p>Déjà inscrit??! <a href="connexion.php" title="Connexion">Cliquez ICI!!</a></p>
            <p><a href="../index.php" title="Retour à la page principale">Retour</a></p>
        </nav>
    </main>
</body>

</html>
